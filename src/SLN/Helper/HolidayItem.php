<?php

class SLN_Helper_HolidayItem
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;
        if (empty($this->data)) {
            $this->data[] = array(
                'from_date' => '0',
                'to_date'   => '0',
                'from_time' => '00:00',
                'to_time'   => '00:00',
                );
        }
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
