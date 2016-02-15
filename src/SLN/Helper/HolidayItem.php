<?php

class SLN_Helper_HolidayItem
{
    private $data;

    private $days = array();
    private $times = array();

    function __construct($data)
    {
        $this->data = $data;
        if($data){
            $this->days['from'] = SLN_Func::evalPickedDate($data['from_date']);
            $this->days['to'] = SLN_Func::evalPickedDate($data['to_date']).' 23:59:59';

            if ($data['from'][0] != '00:00') {
                $this->times[] = array(
                    strtotime($data['from'][0]),
                    strtotime($data['to'][0]),
                );
            }
            if ($data['from'][1] != '00:00') {
                $this->times[] = array(
                    strtotime($data['from'][1]),
                    strtotime($data['to'][1]),
                );
            }
        }
        if (empty($this->times)) {
            $this->times[] = array(strtotime('00:00'), strtotime('23:59'));
        }
        if (empty($this->days)) {
            $this->days = array('from' => 0, 'to' => 0);
        }
    }

    public function isNeedToCheckDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }
        $date = strtotime(SLN_Func::evalPickedDate($date));

        return ($date >= strtotime($this->days['from']) && $date <= strtotime($this->days['to']));
    }

    public function isValidTime($date, $time)
    {
        if (!$this->isNeedToCheckDate($date)) {
            return true;
        }

        return $this->checkTime($time);
    }

    private function checkTime($time)
    {
        if ($time instanceof DateTime) {
            $time = $time->format('H:i');
        }
        $time = strtotime($time);
        foreach ($this->times as $t) {
            if ($t[0] <= $time && $t[1] >= $time) {
                return true;
            }
        }

        return false;
    }

}
