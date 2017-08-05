<?php

use Salon\Util\Date;
use Salon\Util\Time;

class SLN_Action_Ajax_CheckDate extends SLN_Action_Ajax_Abstract
{
    protected $date;
    protected $time;
    protected $errors = array();
    protected $duration;

    public function setDuration(Time $duration){
        $this->duration = $duration;
        return $this;
    }

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
        $this->checkDateTime();
        if ($errors = $this->getErrors()) {
            $ret = compact('errors');
        } else {
            $ret = array('success' => 1);
        }
        $ret['intervals'] = $this->getIntervalsArray();
        $isFromAdmin = isset($_POST['_sln_booking_date']);
        if(!$isFromAdmin){
        if ($ret['intervals']['suggestedDate'] !== $this->date || $ret['intervals']['suggestedTime'] !== $this->time) {
            unset($ret['errors']);
            $ret['success'] = 1;
        }
        }

        return $ret;
    }

    public function getIntervalsArray() {
        return $this->plugin->getIntervals($this->getDateTime(), $this->duration)->toArray();
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
            $txt = $plugin->format()->datetime($from);
            $this->addError(sprintf(__('The date is too near, the minimum allowed is:', 'salon-booking-system') . '<br /><strong>%s</strong>', $txt));
        } elseif (!$hb->isValidTo($date)) {
            $txt = $plugin->format()->datetime($to);
            $this->addError(sprintf(__('The date is too far, the maximum allowed is:', 'salon-booking-system') . '<br /><strong>%s</strong>', $txt));
        } elseif (!$ah->getItems()->isValidDatetime($date) || !$ah->getHolidaysItems()->isValidDatetime($date)) {
            $txt = $plugin->format()->datetime($date);
            $this->addError(sprintf(__('We are unavailable at:', 'salon-booking-system') . '<br /><strong>%s</strong>', $txt));
        } else {
            $ah->setDate($date);
            if (!$ah->isValidDate( Date::create($date))) {
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
            }
        }
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
