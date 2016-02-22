<?php

class SLN_Helper_HolidayItem
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;
        $this->data['from_date'] = isset($this->data['from_date']) ? $this->data['from_date'] : '0';
        $this->data['to_date']   = isset($this->data['to_date'])   ? $this->data['to_date']   : '0';
        $this->data['from_time'] = isset($this->data['from_time']) ? $this->data['from_time'] : '00:00';
        $this->data['to_time']   = isset($this->data['to_time'])   ? $this->data['to_time']   : '00:00';
    }

    public function isValidDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }

        return ($this->isValidTime($date) || $this->isValidTime($date.'23:59:59'));
    }

    public function isValidTime($date)
    {
        $date = strtotime($date);
        return ($date < strtotime($this->data['from_date'].$this->data['from_time']) || $date >= strtotime($this->data['to_date'].$this->data['to_time']));
    }

}
