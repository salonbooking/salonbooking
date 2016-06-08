<?php

class SLN_Action_Ajax_CheckDateAlt extends SLN_Action_Ajax_CheckDate
{
    public function execute()
    {

        if (!isset($this->date)) {
            if(isset($_POST['sln'])){
                $this->date = $_POST['sln']['date'];
                $this->time = $_POST['sln']['time'];
            }
            if(isset($_POST['_sln_booking_date'])) {
                $this->date = $_POST['_sln_booking_date'];
                $this->time = $_POST['_sln_booking_time'];
            }
        }
        $ret = array();

        $bb = $this->plugin->getBookingBuilder();
        $bservices = $bb->getAttendantsIds();

        $ret['intervals'] = $this->plugin->getIntervals($this->getDateTime())->toArray();
        foreach($ret['intervals']['times'] as $k => $t) {
            $date = new SLN_DateTime($ret['intervals']['suggestedYear'].'-'.$ret['intervals']['suggestedMonth'].'-'.$ret['intervals']['suggestedDay'].' '.$t);
            $errors = $this->checkDateTimeServicesAndAttendants($date);
            if (!empty($errors)) {
                unset($ret['intervals']['times'][$k]);
            }
        }
        $bb->setServicesAndAttendants($bservices);

        $this->checkDateTime();
        $bb->save();
        if ($errors = $this->getErrors()) {
            $ret['errors'] = $errors;
        } else {
            $ret['success'] = 1;
        }

        return $ret;
    }

    public function checkDateTime()
    {
        parent::checkDateTime();
        $errors = $this->getErrors();

        if (empty($errors)) {
            $date   = $this->getDateTime();
            $errors = $this->checkDateTimeServicesAndAttendants($date);

            foreach($errors as $error) {
                $this->addError($error);
            }
        }

    }

    public function checkDateTimeServicesAndAttendants($date) {
        $errors = array();

        $plugin = $this->plugin;
        $ah     = $plugin->getAvailabilityHelper();
        $ah->setDate($date);

        $bb   = $plugin->getBookingBuilder();
        $bb->setDate(SLN_Func::filter($date, 'date'))->setTime(SLN_Func::filter($date, 'time'));
        $isMultipleAttSelection = SLN_Plugin::getInstance()->getSettings()->get('m_attendant_enabled');

        $obj = new SLN_Shortcode_Salon_AttendantStep($plugin, new SLN_Shortcode_Salon($plugin, array()),'');
        if ($isMultipleAttSelection) {
            $ret = $obj->dispatchMultiple($bb->getAttendantsIds());
        } else {
            $tmp = $bb->getAttendantsIds();
            $ret = $obj->dispatchSingle(reset($tmp));
        }

        $bookingServices =  $bb->getBookingServices();

        $bookingOffsetEnabled   = SLN_Plugin::getInstance()->getSettings()->get('reservation_interval_enabled');
        $bookingOffset          = SLN_Plugin::getInstance()->getSettings()->get('minutes_between_reservation');
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
