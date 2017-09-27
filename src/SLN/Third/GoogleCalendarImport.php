<?php

class SLN_Third_GoogleCalendarImport
{
    const SUCCESS_COLOR_ID = '10';
    const WARNING_COLOR_ID = '6';
    const ERROR_COLOR_ID = '11';

    const EXCEPTION_CODE_FOR_EMPTY_CALENDAR_EVENT       = 1001;
    const EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT     = 1002;
    const EXCEPTION_CODE_FOR_CONFLICTING_CALENDAR_EVENT = 1003;

    private static $googleClientCalendarSyncToken = 'salon_google_client_calendar_sync_token';

    private static $instance;
    /** @var SLN_GoogleScope */
    private $gScope;

    public static function launch($gScope)
    {
        if (empty(self::$instance)) {
            self::$instance = new self($gScope);
        }
    }

    /**
     * SLN_Third_GoogleCalendarImport constructor.
     * @param $gScope
     */
    private function __construct($gScope)
    {
        $this->gScope = $gScope;

        if (defined('DOING_CRON') && $_GET['action'] === 'sln_sync_from_google_calendar') {
            add_action('wp_loaded', array($this, 'syncFull'));
            add_filter('user_has_cap', array($this, 'userHasCapCallback'), 10, 4);
        }
    }

    public function userHasCapCallback($allcaps, $caps, $args, $user)
    {
        if (in_array('edit_others_sln_bookings', $caps)) {
            $allcaps['edit_others_sln_bookings'] = true;
        }

        return $allcaps;
    }

    public function syncFull()
    {
        $gScope = $this->gScope;

        $syncToken = self::getSyncToken();
        $this->printMsg(sprintf("Current token '%s'", $syncToken));

        $params = array();
        if (!empty($syncToken)) {
            $params['syncToken'] = $syncToken;
        }
        try {
            $gCalendarEvents = $gScope->get_google_service()->events->listEvents(
                $gScope->google_client_calendar,
                $params
            );
        } catch (Google_Service_Exception $e) {
            $gCalendarEvents = $gScope->get_google_service()->events->listEvents($gScope->google_client_calendar);
        }

        $nextPageToken = null;
        do {
            $gCalendarEvents->setNextPageToken($nextPageToken);

            $gEvents = $gCalendarEvents->getItems();
            $this->printMsg(sprintf("Need to process %s events", count($gEvents)));

            $this->importBookingsFromGoogleCalendarEvents($gEvents);

            $nextPageToken = $gCalendarEvents->getNextPageToken();
            $this->printMsg(sprintf("Next page token '%s'", $nextPageToken));
        } while ($nextPageToken);

        $nextSyncToken = $gCalendarEvents->getNextSyncToken();
        $this->printMsg(sprintf("Next sync token '%s'", $syncToken));

        self::updateSyncToken($nextSyncToken);
    }

    private function importBookingsFromGoogleCalendarEvents($gEvents)
    {
        if (empty($gEvents)) {
            return;
        }

        foreach ($gEvents as $gEvent) {
            $this->eventError = '';
            $this->printMsg(str_repeat('*', 100));
            $this->importBookingFromGoogleCalendarEvent($gEvent);
            $this->printMsg(str_repeat('*', 100));
        }
    }

    /**
     * @param Google_Service_Calendar_Event $gEvent
     */
    private function importBookingFromGoogleCalendarEvent($gEvent)
    {
        $this->printMsg(sprintf("Start processing event '%s' with title '%s'", $gEvent->getId(), $gEvent->getSummary()));

        $bookingId = $this->getBookingIdFromEventId($gEvent->getId());
        if (!$bookingId) {
            try {
                $bookingDetails = $this->getBookingDetailsFromGoogleCalendarEvent($gEvent);
                if (empty($bookingDetails['user_id'])) {
                    $this->printMsg("Start creating new user");
                    $bookingDetails['user_id'] = $this->createCustomer($bookingDetails);
                    $this->printMsg("User created");
                }

                $this->printMsg("Event parsed details:");
                $this->printMsg(print_r($bookingDetails, true));
                $this->printMsg("Start creating booking");

                $this->importNewBookingFromGoogleCalendarEvent($gEvent, $bookingDetails);

                $gEvent = $this->gScope->get_google_service()->events->get(
                    $this->gScope->google_client_calendar,
                    $gEvent->getId()
                );

                if (empty($this->eventError)) {
                    $this->makeGoogleCalendarEventSyncSuccessful($gEvent);
                } else {
                    $this->makeGoogleCalendarEventSyncWarning($gEvent, $this->eventError);
                }
            } catch (Exception $e) {
                switch ($e->getCode()) {
                    case self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT:
                        $this->makeGoogleCalendarEventSyncWrong($gEvent, $e->getMessage());
                        $this->printMsg(sprintf("ERROR: %s", $e->getMessage()));
                        break;
                    case self::EXCEPTION_CODE_FOR_EMPTY_CALENDAR_EVENT:
                        break;
                }
            }
        }
        else {
            $this->printMsg(sprintf("Booking for this event already exist '%s'", $bookingId));
        }

        $this->printMsg(sprintf("End processing event '%s'", $gEvent->getId()));
    }

    /**
     * @param Google_Service_Calendar_Event $gEvent
     * @param array $bookingDetails
     * @return bool
     */
    private function importNewBookingFromGoogleCalendarEvent($gEvent, $bookingDetails)
    {
        $date            = new SLN_DateTime($bookingDetails['date'].' '.$bookingDetails['time']);
        $bookingServices = $this->prepareAndValidateBookingServices($bookingDetails);

        // create booking
        $user = get_userdata($bookingDetails['user_id']);

        $name       = trim($bookingDetails['first_name'].' '.$bookingDetails['last_name']);
        $dateString = SLN_Plugin::getInstance()->format()->datetime($date);

        $postArr = array(
            'post_author' => $bookingDetails['user_id'],
            'post_type'   => SLN_Plugin::POST_TYPE_BOOKING,
            'post_title'  => $name.' - '.$dateString,
            'post_status' => SLN_Enum_BookingStatus::PAY_LATER,
            'meta_input'  => array(
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_date'      => $bookingDetails['date'],
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_time'      => $bookingDetails['time'],
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_firstname' => $bookingDetails['first_name'],
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_lastname'  => $bookingDetails['last_name'],
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_email'     => !empty($bookingDetails['email']) ? $bookingDetails['email'] : $user->user_email,
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_phone'     => $bookingDetails['phone'],
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_address'   => '',
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_note'      => $bookingDetails['note'],
                '_sln_calendar_event_id'                       => $gEvent->getId(),
                '_'.SLN_Plugin::POST_TYPE_BOOKING.'_services'  => $bookingServices->toArrayRecursive(),
            ),
        );
        $postId  = wp_insert_post($postArr);

        if ($postId instanceof WP_Error) {
            throw new ErrorException($postId->get_error_message(), self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT);
        }
        $this->printMsg(sprintf("Booking created '%s'", $postId));

        $booking = SLN_Plugin::getInstance()->createBooking($postId);
        $booking->getBookingServices();
        $booking->evalTotal();
        $booking->evalDuration();

        return true;
    }

    private function prepareAndValidateBookingServices($bookingDetails)
    {
        $date = new SLN_DateTime($bookingDetails['date'].' '.$bookingDetails['time']);
        $this->validateBookingStartTime($date);

        $ah = SLN_Plugin::getInstance()->getAvailabilityHelper();
        $ah->setDate($date);

        $bookingServices = SLN_Wrapper_Booking_Services::build(
            array_fill_keys(
                $bookingDetails['services'],
                0
            ),
            $date
        );

        try {
            $ah->addAttendantForServices($bookingServices);

            $this->validateBookingServices($ah, $bookingServices);
        } catch (Exception $e) {
            $this->eventError = $e->getMessage();
            $this->printMsg(sprintf("WARNING: %s", $this->eventError));
        }

        return $bookingServices;
    }

    private function validateBookingStartTime($date) {
        $interval    = SLN_Plugin::getInstance()->getSettings()->getInterval();
        $startInMins = (int) SLN_Func::getMinutesFromDuration($date);
        if ($startInMins % $interval) {
            throw new ErrorException(sprintf("Event start time is not multiple of %s minutes", $interval), self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT);
        }

        return true;
    }

    /**
     * @param SLN_Helper_Availability $ah
     * @param SLN_Wrapper_Booking_Services $bookingServices
     * @throws SLN_Exception
     */
    private function validateBookingServices($ah, $bookingServices)
    {
        $settings               = SLN_Plugin::getInstance()->getSettings();
        $servicesCount          = $settings->get('services_count');
        $bookingOffsetEnabled   = $settings->get('reservation_interval_enabled');
        $bookingOffset          = $settings->get('minutes_between_reservation');
        $isMultipleAttSelection = $settings->get('m_attendant_enabled');

        $firstSelectedAttendant = null;
        foreach ($bookingServices->getItems() as $bookingService) {
            if ($servicesCount && $bookingServices->getPosInQueue($bookingService) > $servicesCount) {
                throw new SLN_Exception(
                    sprintf(__('You can select up to %d items', 'salon-booking-system'), $servicesCount)
                );
            } else {
                $serviceErrors = $ah->validateServiceFromOrder($bookingService->getService(), $bookingServices);
                if (!empty($serviceErrors)) {
                    throw new SLN_Exception(reset($serviceErrors));
                }

                if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                    $offsetStart   = $bookingService->getEndsAt();
                    $offsetEnd     = $bookingService->getEndsAt()->modify('+'.$bookingOffset.' minutes');
                    $serviceErrors = $ah->validateTimePeriod($offsetStart, $offsetEnd);

                    if (!empty($serviceErrors)) {
                        throw new SLN_Exception(reset($serviceErrors));
                    }
                }

                $serviceErrors = $ah->validateBookingService($bookingService);
                if (!empty($serviceErrors)) {
                    throw new SLN_Exception(reset($serviceErrors));
                }

                if (!$isMultipleAttSelection) {
                    if (!$firstSelectedAttendant) {
                        $firstSelectedAttendant = $bookingService->getAttendant() ?
                            $bookingService->getAttendant()->getId() : false;
                    }
                    if ($bookingService->getAttendant() &&
                        $bookingService->getAttendant()->getId() != $firstSelectedAttendant
                    ) {
                        throw new SLN_Exception(
                            __(
                                'Multiple attendants selection is disabled. You must select one attendant for all services.',
                                'salon-booking-system'
                            )
                        );
                    }
                }
                if ($bookingService->getAttendant()) {
                    $attendantErrors = $ah->validateAttendantService(
                        $bookingService->getAttendant(),
                        $bookingService->getService()
                    );
                    if (!empty($attendantErrors)) {
                        throw new SLN_Exception(reset($attendantErrors));
                    }

                    $attendantErrors = $ah->validateBookingAttendant($bookingService);
                    if (!empty($attendantErrors)) {
                        throw new SLN_Exception(reset($attendantErrors));
                    }
                }
            }
        }
    }


    /**
     * @param Google_Service_Calendar_Event $gEvent
     * @return array
     */
    private function getBookingDetailsFromGoogleCalendarEvent($gEvent)
    {
        $bookingDetails = array();

        if (null == $gEvent->getStart()) {
            throw new ErrorException('Event start datetime is null', self::EXCEPTION_CODE_FOR_EMPTY_CALENDAR_EVENT);
        }

        $eventDateTime = $gEvent->getStart()->getDateTime();
        if (empty($eventDateTime)) {
            throw new ErrorException('Event start time is null', self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT);
        }
        $eventDateTimeString = date('Y-m-d H:i', strtotime($eventDateTime));
        $localDateTime       = new DateTime($eventDateTimeString);
        $localTimezone       = SLN_DateTime::getWpTimezoneString();
        $localDateTime->setTimezone(new DateTimeZone($localTimezone));

        $bookingDetails['date'] = $localDateTime->format('Y-m-d');
        $bookingDetails['time'] = $localDateTime->format('H:i');

        $bookingDetails = array_merge(
            $bookingDetails,
            $this->parseGoogleCalendarEventDescription($gEvent->getSummary())
        );

        $bookingDetails['user_id'] = $this->getCustomerIdByName(
            $bookingDetails['first_name'],
            $bookingDetails['last_name']
        );

        if (empty($bookingDetails['user_id']) && empty($bookingDetails['email'])) {
            throw new ErrorException(
                sprintf(
                    "Invalid username '%s'",
                    trim($bookingDetails['first_name'].' '.$bookingDetails['last_name'])
                ),
                self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT
            );
        }

        foreach ($bookingDetails['services'] as $i => $name) {
            $bookingDetails['services'][$i] = $this->getServiceIdByName($name);
            if (empty($bookingDetails['services'][$i])) {
                throw new ErrorException(sprintf("Invalid service name '%s'", $name), self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT);
            }
        }

        return $bookingDetails;
    }

    private function parseGoogleCalendarEventDescription($text)
    {
        $details = array(
            'first_name' => '',
            'last_name'  => '',
            'services'   => array(),
            'email'      => '',
            'phone'      => '',
            'note'       => '',
        );

        $items = explode(',', trim($text), 5);
        $items = array_map('trim', $items);

        if (count($items) < 2) {
            throw new ErrorException("Invalid string. 'First_name last_name, service name' not found", self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT);
        }

        $details['services'] = array_filter(array_map('trim', explode('+', $items[1])));
        $details['email']    = isset($items[2]) ? $items[2] : '';
        $details['phone']    = isset($items[3]) ? $items[3] : '';
        $details['note']     = isset($items[4]) ? $items[4] : '';

        $details = array_merge($details, $this->parseCustomerName(trim($items[0])));

        return $details;
    }

    private function parseCustomerName($customerName)
    {
        $ret = array(
            'first_name' => '',
            'last_name'  => '',
        );

        $nameParts = explode(' ', $customerName);
        if (count($nameParts) > 1) {
            $ret['last_name'] = array_pop($nameParts);
        }
        $ret['first_name'] = implode(' ', $nameParts);

        return $ret;
    }

    private function getCustomerIdByName($firstName, $lastName)
    {
        $args  = array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'first_name',
                    'value'   => $firstName,
                    'compare' => '=',
                ),
                array(
                    'key'     => 'last_name',
                    'value'   => $lastName,
                    'compare' => '=',
                ),
            ),
        );
        $query = new WP_User_Query($args);

        if (!$query->get_total()) {
            return false;
        }

        $users = $query->get_results();
        $user  = reset($users);

        return $user->ID;
    }

    private function createCustomer($values) {
        if (email_exists($values['email'])) {
            throw new ErrorException(
                sprintf(
                    "E-mail '%s' exists",
                    $values['email']
                ),
                self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT
            );
        }

        if (empty($values['password'])) {
            $values['password'] = wp_generate_password();
        }

        $user_id = wp_create_user($values['email'], $values['password'], $values['email']);

        if (is_wp_error($user_id)) {
            throw new ErrorException(
                $user_id->get_error_message(),
                self::EXCEPTION_CODE_FOR_INVALID_CALENDAR_EVENT
            );
        }

        wp_update_user(
            array('ID' => $user_id, 'first_name' => $values['first_name'], 'last_name' => $values['last_name'], 'role' => SLN_Plugin::USER_ROLE_CUSTOMER)
        );
        add_user_meta($user_id, '_sln_phone', $values['phone']);
        add_user_meta($user_id, '_sln_address', isset($values['address']) ? $values['address'] : '');

        wp_new_user_notification($user_id, null, 'both');

        return $user_id;
    }

    private function getBookingIdFromEventId($gEventId)
    {
        $args  = array(
            'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
            'meta_query' => array(
                array(
                    'key'   => '_sln_calendar_event_id',
                    'value' => $gEventId,
                ),
            ),
        );
        $query = new WP_Query($args);

        $posts = $query->get_posts();
        wp_reset_query();
        $post = reset($posts);

        return (!empty($post) ? $post->ID : null);
    }

    private function getServiceIdByName($serviceName)
    {
        $serviceName = trim($serviceName);
        if (empty($serviceName)) {
            return false;
        }

        $args  = array(
            'title'     => $serviceName,
            'post_type' => SLN_Plugin::POST_TYPE_SERVICE,
        );
        $query = new WP_Query($args);

        if (!$query->post_count) {
            return false;
        }
        $posts = $query->get_posts();
        wp_reset_query();

        $post = reset($posts);

        return $post->ID;
    }

    /**
     * @param Google_Service_Calendar_Event $gEvent
     */
    private function makeGoogleCalendarEventSyncSuccessful($gEvent)
    {
        $gEvent->setColorId(self::SUCCESS_COLOR_ID);

        $updated = $this->gScope->get_google_service()->events->update(
            $this->gScope->google_client_calendar,
            $gEvent->getId(),
            $gEvent
        );
    }

    /**
     * @param Google_Service_Calendar_Event $gEvent
     * @param string|null $error
     */
    private function makeGoogleCalendarEventSyncWrong($gEvent, $error = null)
    {
        $gEvent->setColorId(self::ERROR_COLOR_ID);
        if (!empty($error)) {
            $gEvent->setDescription("ERROR: {$error}");
        }

        $updated = $this->gScope->get_google_service()->events->update(
            $this->gScope->google_client_calendar,
            $gEvent->getId(),
            $gEvent
        );
    }

    /**
     * @param Google_Service_Calendar_Event $gEvent
     * @param string|null $error
     */
    private function makeGoogleCalendarEventSyncWarning($gEvent, $error = null)
    {
        $gEvent->setColorId(self::WARNING_COLOR_ID);
        if (!empty($error)) {
            $error = $gEvent->getDescription()."\n\nWARNING: {$error}";
            $gEvent->setDescription($error);
        }

        $updated = $this->gScope->get_google_service()->events->update(
            $this->gScope->google_client_calendar,
            $gEvent->getId(),
            $gEvent
        );
    }

    private function printMsg($text) {
        echo "{$text}<br/>";
    }

    private static function updateSyncToken($syncToken)
    {
        update_option(self::$googleClientCalendarSyncToken, $syncToken);
    }

    private static function getSyncToken()
    {
        return get_option(self::$googleClientCalendarSyncToken);
    }
}