<?php

class SLN_Shortcode_Salon_DateStep extends SLN_Shortcode_Salon_Step
{

    protected function dispatchForm()
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        $values = $_POST['sln'];
        $date   = SLN_Func::filter($values['date'], 'date');
        $time   = SLN_Func::filter($values['time'], 'time');
        $bb
            ->removeLastID()
            ->setDate($date)
            ->setTime($time);
        $this->checkDateTime($date, $time);
        if (!$this->getErrors()) {
            $bb->save();

            return true;
        }
    }

    public function doAjaxValidate()
    {
        $this->checkDateTime($_POST['sln']['date'], $_POST['sln']['time']);
        if ($errors = $this->getErrors()) {
            return compact('errors');
        } else {
            return array('success' => 1);
        }
    }

    public function checkDateTime($date, $time)
    {
        $date  = new DateTime(
            SLN_Func::filter($date, 'date') . ' ' . SLN_Func::filter($time, 'time')
        );
        $ah    = $this->getPlugin()->getAvailabilityHelper();
        $range = $ah->getHoursBeforeDateTime();
        if ($date < $range->from) {
            $txt = $this->getPlugin()->format()->datetime($range->from);
            $this->addError(sprintf(__('the date is too old, the minimum allowed is %s', 'sln'), $txt));
        } elseif ($range->to && $date > $range->to) {
            $txt = $this->getPlugin()->format()->datetime($range->to);
            $this->addError(sprintf(__('the date is too far, the maximum allowed is %s', 'sln'), $txt));
        } else {
            $ah->setDate($date);
            $countDay = $this->getPlugin()->getSettings()->get('parallels_day');
            $countHour = $this->getPlugin()->getSettings()->get('parallels_hour');
            if ($countDay && $ah->getBookingsDayCount() >= $countDay) {
                $this->addError(
                    __('you can\'t book in this day because there aren\'t free places, please choose a different day', 'sln')
                );
            }elseif ($countHour && $ah->getBookingsHourCount() >= $countHour) {
                $this->addError(
                    __('you can\'t book in this hour because there aren\'t free places, please choose a different hour', 'sln')
                );
            }
        }
    }
}