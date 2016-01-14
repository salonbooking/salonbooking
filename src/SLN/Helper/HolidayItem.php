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
        $date = strtotime($date);

        return ($date < strtotime($this->data['from']) || $date > strtotime($this->data['to'] . ' 23:59:59'));
    }

}
