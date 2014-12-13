<?php

class SLN_Shortcode_Saloon_DetailsStep extends SLN_Shortcode_Saloon_Step
{
    protected function dispatchForm()
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        $values = $_POST['sln'];
        $fields = array(
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'phone'     => ''
        );
        foreach ($fields as $field => $filter) {
            $bb->set($field, SLN_Func::filter($values[$field], $filter));
        }

        $bb->save();

        return true;
    }
}