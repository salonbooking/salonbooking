<?php

class SLN_Shortcode_Saloon_SummaryStep extends SLN_Shortcode_Saloon_Step
{
    protected function dispatchForm()
    {
        $values = $_POST['sln'];
        $bb = $this->getPlugin()->getBookingBuilder();
        $bb->set('note', SLN_Func::filter($values['note']));
        $bb->create();

        return true;
    }
}