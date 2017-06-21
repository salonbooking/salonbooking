<?php

class SLN_Third_GoogleCalendarImport
{
    const SUCCESS_COLOR_ID = '10';
    const WARNING_COLOR_ID = '5';
    const ERROR_COLOR_ID = '11';

    private static $exceptionCodeForEmptyCalendarEvent = 322;

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
        }
    }

    public function syncFull()
    {
        $gScope = $this->gScope;

        $syncToken = self::getSyncToken();

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
            $this->importBookingsFromGoogleCalendarEvents($gEvents);

            var_dump(count($gEvents));
//        var_dump(($gEvents));
            $nextPageToken = $gCalendarEvents->getNextPageToken();
            var_dump($nextPageToken);
        } while ($nextPageToken);

        $nextSyncToken = $gCalendarEvents->getNextSyncToken();
        var_dump($nextSyncToken);

        self::updateSyncToken($nextSyncToken);
    }

    private function importBookingsFromGoogleCalendarEvents($gEvents)
    {
        if (empty($gEvents)) {
            return;
        }

        foreach ($gEvents as $gEvent) {
            $this->importBookingFromGoogleCalendarEvent($gEvent);
        }
    }

    /**
     * @param Google_Service_Calendar_Event $gEvent
     */
    private function importBookingFromGoogleCalendarEvent($gEvent)
    {
        if (!$this->getBookingIdFromEventId($gEvent->getId())) {
            try {
                $bookingDetails = $this->getBookingDetailsFromGoogleCalendarEvent($gEvent);
                print_r($bookingDetails);
                echo '<br>';

                $this->importNewBookingFromGoogleCalendarEvent($gEvent, $bookingDetails);

                $gEvent = $this->gScope->get_google_service()->events->get(
                    $this->gScope->google_client_calendar,
                    $gEvent->getId()
                );
                $this->makeGoogleCalendarEventSyncSuccessful($gEvent);
            } catch (Exception $e) {
                if ($e->getCode() !== self::$exceptionCodeForEmptyCalendarEvent) {
                    $this->makeGoogleCalendarEventSyncUnSuccessful($gEvent, $e->getMessage());
                }
            }
        }
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
            throw new ErrorException();
        }

        $booking = SLN_Plugin::getInstance()->createBooking($postId);
        $booking->getBookingServices();
        $booking->evalTotal();
        $booking->evalDuration();

        return true;
    }

    private function prepareAndValidateBookingServices($bookingDetails)
    {
        $ah   = SLN_Plugin::getInstance()->getAvailabilityHelper();
        $date = new SLN_DateTime($bookingDetails['date'].' '.$bookingDetails['time']);

        if (!empty($bookingDetails['id'])) {
            $booking = SLN_Plugin::getInstance()->createBooking($bookingDetails['id']);
            $ah->setDate($date, $booking);

        } else {
            $ah->setDate($date);
        }

        $bookingServices = SLN_Wrapper_Booking_Services::build(
            array_fill_keys(
                $bookingDetails['services'],
                0
            ),
            $date
        );
        $ah->addAttendantForServices($bookingServices);

        $this->validateBookingServices($ah, $bookingServices);

        return $bookingServices;
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

                $serviceErrors = $ah->validateService(
                    $bookingService->getService(),
                    $bookingService->getStartsAt(),
                    $bookingService->getTotalDuration(),
                    $bookingService->getBreakStartsAt(),
                    $bookingService->getBreakEndsAt()
                );
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

                    $attendantErrors = $ah->validateAttendant(
                        $bookingService->getAttendant(),
                        $bookingService->getStartsAt(),
                        $bookingService->getTotalDuration(),
                        $bookingService->getBreakStartsAt(),
                        $bookingService->getBreakEndsAt()
                    );
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

        $start = $gEvent->getStart();
        if (empty($start)) {
            throw new ErrorException('', self::$exceptionCodeForEmptyCalendarEvent);
        }

        $eventDate              = $gEvent->getStart()->getDateTime() !== null ?
            $gEvent->getStart()->getDateTime() : $gEvent->getStart()->getDate();
        $bookingDetails['date'] = date('Y-m-d', strtotime($eventDate));

        $bookingDetails = array_merge(
            $bookingDetails,
            $this->parseGoogleCalendarEventDescription($gEvent->getSummary())
        );

        $bookingDetails['user_id'] = $this->getCustomerIdByName(
            $bookingDetails['first_name'],
            $bookingDetails['last_name']
        );

        if (empty($bookingDetails['user_id'])) {
            throw new ErrorException();
        }

        foreach ($bookingDetails['services'] as $i => $name) {
            $bookingDetails['services'][$i] = $this->getServiceIdByName($name);
            if (empty($bookingDetails['services'][$i])) {
                throw new ErrorException();
            }
        }

        return $bookingDetails;
    }

    private function parseGoogleCalendarEventDescription($text)
    {
        $text = trim($text);

        $details = array(
            'time'       => '',
            'first_name' => '',
            'last_name'  => '',
            'services'   => array(),
            'email'      => '',
            'phone'      => '',
            'note'       => '',
        );

        $partsWithoutSpaces = explode(' ', $text, 2);
        $partsWithoutCommas = explode(',', $text, 2);

        if (strlen($partsWithoutSpaces[0]) < strlen($partsWithoutCommas[0])) {
            $items = array_merge(array($partsWithoutSpaces[0]), explode(',', $partsWithoutSpaces[1], 5));
        } else {
            $items = array_merge(array($partsWithoutCommas[0]), explode(',', $partsWithoutCommas[1], 5));
        }
        $items = array_map('trim', $items);

        if (count($items) < 3 || !strtotime($items[0])) {
            throw new ErrorException();
        }

        $details['time']     = date('H:i', strtotime($items[0]));
        $details['services'] = array_filter(array_map('trim', explode('+', $items[2])));
        $details['email']    = isset($items[3]) ? $items[3] : '';
        $details['phone']    = isset($items[4]) ? $items[4] : '';
        $details['note']     = isset($items[5]) ? $items[5] : '';

        $details = array_merge($details, $this->parseCustomerName(trim($items[1])));

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
    private function makeGoogleCalendarEventSyncUnSuccessful($gEvent, $error = null)
    {
        $gEvent->setColorId(self::ERROR_COLOR_ID);
        if (!empty($error)) {
            $gEvent->setDescription($error);
        }

        $updated = $this->gScope->get_google_service()->events->update(
            $this->gScope->google_client_calendar,
            $gEvent->getId(),
            $gEvent
        );
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