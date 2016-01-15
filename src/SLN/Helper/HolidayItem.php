<?php

class SLN_Helper_HolidayItem
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function isValidDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }
        $date = strtotime(SLN_Func::evalPickedDate($date));

        return ($date < strtotime(SLN_Func::evalPickedDate($this->data['from'])) || $date > strtotime(SLN_Func::evalPickedDate($this->data['to']) . ' 23:59:59'));
    }

}
