<?php

class SLN_Shortcode_Saloon_DateStep extends SLN_Shortcode_Saloon_Step
{
    private $errors;

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

        if ($this->checkDateTime($date, $time)) {
            $bb->save();
            return true;
        }
    }

    protected function checkDateTime($date, $time)
    {
        if (strtotime($date) <= strtotime('today')) {
            $this->errors[] = __('The date is too old');

            return false;
        }

        return true;
    }

    public function getViewData()
    {
        $ret = parent::getViewData();

        return array_merge(
            $ret,
            array(
                'errors' => $this->errors
            )
        );
    }
}