<?php

class SLN_Shortcode_Saloon_DateStep extends SLN_Shortcode_Saloon_Step
{
    protected function dispatchForm()
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        $values = $_POST['sln'];
        $bb
            ->setDate(SLN_Func::filter($values['date'], 'date'))
            ->setTime(SLN_Func::filter($values['time'], 'time'))
            ->save();

        return true;
    }
}