<?php

class SLN_Action_Ajax_CheckDateAlt extends SLN_Action_Ajax_CheckDate
{
    public function getIntervalsArray() {
        if ($this->isAdmin()) {
            return parent::getIntervalsArray();
        }

        $plugin = $this->plugin;
        $ah   = $plugin->getAvailabilityHelper();

        $bb = $plugin->getBookingBuilder();
        $bservices = $bb->getAttendantsIds();

        $intervalsArray = parent::getIntervalsArray();

        $year = $intervalsArray['suggestedYear'];
        $month = $intervalsArray['suggestedMonth'];
        $day = $intervalsArray['suggestedDay'];
        foreach($intervalsArray['days'] as $k => $v) {
            $free = false;
            $tmpDate = new SLN_DateTime("$year-$month-$v");

            $ah->setDate($tmpDate);
            $times = $ah->getTimes($tmpDate);

            foreach ($times as $time) {
                $tmpDateTime = new SLN_DateTime("$year-$month-$v $time");
                $errors = $this->checkDateTimeServicesAndAttendants($bservices, $tmpDateTime);
                if (empty($errors)) {
                    $free = true;
                    break;
                }
            }

            if (!$free) {
                unset($intervalsArray['days'][$k]);
                $pos = array_search("$year-$month-$k", $intervalsArray['dates']);
                if ($pos !== false) {
                    unset($intervalsArray['dates'][$pos]);
                }
            }
        }

        foreach($intervalsArray['times'] as $k => $t) {
            $date = new SLN_DateTime("$year-$month-$day $t");
            $errors = $this->checkDateTimeServicesAndAttendants($bservices, $date);
            if (!empty($errors)) {
                unset($intervalsArray['times'][$k]);
            }
        }

        if (!isset($intervalsArray['times'][$intervalsArray['suggestedTime']]) && !empty($intervalsArray['times'])) {
            $intervalsArray['suggestedTime'] = reset($intervalsArray['times']);
        }

        return $intervalsArray;
    }

    public function isAdmin() {
        return isset($_POST['post_ID']);
    }

    public function checkDateTime()
    {
        parent::checkDateTime();
        if ($this->isAdmin()) {
            return;
        }

        $plugin = $this->plugin;
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
