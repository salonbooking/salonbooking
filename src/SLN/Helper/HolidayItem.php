<?php

class SLN_Helper_HolidayItem
{
    private $data;
    private $timestampBegin;
    private $timestampEnd;

    function __construct($data)
    {
        $this->data = $data;
        $this->timestampBegin = strtotime(SLN_Func::evalPickedDate($this->data['from']));
        $this->timestampEnd = strtotime(SLN_Func::evalPickedDate($this->data['to']) . ' 23:59:59');
    }

    public function isValidDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }
        $date = strtotime(SLN_Func::evalPickedDate($date));

        return ($date < $this->timestampBegin || $date > $this->timestampEnd);
    }

}
