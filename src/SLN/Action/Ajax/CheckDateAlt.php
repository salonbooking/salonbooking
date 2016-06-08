<?php

class SLN_Action_Ajax_CheckDateAlt extends SLN_Action_Ajax_CheckDate
{
    public function getIntervalsArray() {
        $plugin = $this->plugin;
        $bb = $plugin->getBookingBuilder();
        $bservices = $bb->getAttendantsIds();

        $intervals = $plugin->getIntervals($this->getDateTime())->toArray();
        foreach($intervals['times'] as $k => $t) {
            $date = new SLN_DateTime($intervals['suggestedYear'].'-'.$intervals['suggestedMonth'].'-'.$intervals['suggestedDay'].' '.$t);
            $errors = $this->checkDateTimeServicesAndAttendants($bservices, $date);
            if (!empty($errors)) {
                unset($intervals['times'][$k]);
            }
        }

        if (!isset($intervalsArray['times'][$intervals['suggestedTime']]) && !empty($intervalsArray['times'])) {
            $intervals['suggestedTime'] = reset($intervalsArray['times']);
        }

        return $intervals;
    }

    public function checkDateTime()
    {
        $plugin = $this->plugin;
        parent::checkDateTime();
        $errors = $this->getErrors();

        if (empty($errors)) {
            $date   = $this->getDateTime();

            $bb = $plugin->getBookingBuilder();
            $bservices = $bb->getAttendantsIds();

            $errors = $this->checkDateTimeServicesAndAttendants($bservices, $date);

            foreach($errors as $error) {
                $this->addError($error);
            }
        }

    }

    public function checkDateTimeServicesAndAttendants($services, $date) {
        $errors = array();

        $plugin = $this->plugin;
        $ah     = $plugin->getAvailabilityHelper();
        $ah->setDate($date);

        $isMultipleAttSelection = SLN_Plugin::getInstance()->getSettings()->get('m_attendant_enabled');
        $bookingOffsetEnabled   = SLN_Plugin::getInstance()->getSettings()->get('reservation_interval_enabled');
        $bookingOffset          = SLN_Plugin::getInstance()->getSettings()->get('minutes_between_reservation');

        $bookingServices = SLN_Wrapper_Booking_Services::build($services, $date);

        $interval               = min(SLN_Enum_Interval::toArray());

        $firstSelectedAttendant = null;


        foreach($bookingServices->getItems() as $bookingService) {
            $serviceErrors   = array();
            $attendantErrors = array();

            if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                $offsetStart   = $bookingService->getEndsAt();
                $offsetEnd     = $bookingService->getEndsAt()->modify('+'.$bookingOffset.' minutes');
                $serviceErrors = $ah->validateTimePeriod($interval, $offsetStart, $offsetEnd);
            }
            if (empty($serviceErrors)) {
                $serviceErrors = $ah->validateService($bookingService->getService(), $bookingService->getStartsAt(), $bookingService->getDuration());
            }
            if (!empty($serviceErrors)) {
                $errors[] = $serviceErrors[0];
                continue;
            }

            if ($bookingService->getAttendant() === false) {
                continue;
            }

            if (!$isMultipleAttSelection) {
                if (!$firstSelectedAttendant) {
                    $firstSelectedAttendant = $bookingService->getAttendant()->getId();
                }
                if ($bookingService->getAttendant()->getId() != $firstSelectedAttendant) {
                    $attendantErrors = array(__('Multiple attendants selection is disabled. You must select one attendant for all services.', 'salon-booking-system'));
                }
            }
            if (empty($attendantErrors)) {
                $attendantErrors = $ah->validateAttendantService($bookingService->getAttendant(), $bookingService->getService());
                if (empty($attendantErrors)) {
                    $attendantErrors = $ah->validateAttendant($bookingService->getAttendant(), $bookingService->getStartsAt(), $bookingService->getDuration());
                }
            }

            if (!empty($attendantErrors)) {
                $errors[] = $attendantErrors[0];
            }
        }

        return $errors;
    }

}
