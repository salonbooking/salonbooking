<?php

class SLN_Action_Ajax_CheckDate extends SLN_Action_Ajax_Abstract
{
    private $date;
    private $time;
    private $errors = array();

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

        if ($this->plugin->getSettings()->isFormStepsAltOrder()) {
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
            if ($errors = $this->getErrors()) {
                $ret['errors'] = $errors;
            } else {
                $ret['success'] = 1;
            }
        }
        else {
            $this->checkDateTime();
            if ($errors = $this->getErrors()) {
                $ret = compact('errors');
            } else {
                $ret = array('success' => 1);
            }
            $ret['intervals'] = $this->plugin->getIntervals($this->getDateTime())->toArray();
        }

        return $ret;
    }

    public function checkDateTime()
    {

        $plugin = $this->plugin;
        $date   = $this->getDateTime();
//        $this->addError($plugin->format()->datetime($date));
        $ah   = $plugin->getAvailabilityHelper();
        $hb   = $ah->getHoursBeforeHelper();
        $from = $hb->getFromDate();
        $to   = $hb->getToDate();
        if (!$hb->isValidFrom($date)) {
            $txt = $plugin->format()->datetime(new SLN_DateTime($from));
            $this->addError(sprintf(__('The date is too near, the minimum allowed is:', 'salon-booking-system') . '<br /><strong>%s</strong>', $txt));
        } elseif (!$hb->isValidTo($date)) {
            $txt = $plugin->format()->datetime($to);
            $this->addError(sprintf(__('The date is too far, the maximum allowed is:', 'salon-booking-system') . '<br /><strong>%s</strong>', $txt));
        } elseif (!$ah->getItems()->isValidDatetime($date) || !$ah->getHolidaysItems()->isValidDatetime($date)) {
            $txt = $plugin->format()->datetime($date);
            $this->addError(sprintf(__('We are unavailable at:', 'salon-booking-system') . '<br /><strong>%s</strong>', $txt));
        } else {
            $ah->setDate($date);
            if (!$ah->isValidDate($date)) {
                $this->addError(
                    __(
                        'There are no time slots available today - Please select a different day',
                        'salon-booking-system'
                    )
                );
            } elseif (!$ah->isValidTime($date)) {
                $this->addError(
                    __(
                        'There are no time slots available for this period - Please select a  different hour',
                        'salon-booking-system'
                    )
                );
            } else {
                if ($plugin->getSettings()->isFormStepsAltOrder()) {
                    $errors = $this->checkDateTimeServicesAndAttendants($date);

                    foreach($errors as $error) {
                        $this->addError($error);
                    }
                }

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
            $ret = $obj->dispatchSingle($bb->getAttendantsIds());
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

    protected function addError($err)
    {
        $this->errors[] = $err;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param mixed $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    protected function getDateTime()
    {
        $date = $this->date;
        $time = $this->time;
        $ret = new SLN_DateTime(
            SLN_Func::filter($date, 'date') . ' ' . SLN_Func::filter($time, 'time'.':00')
        );
        return $ret;
    }
}
