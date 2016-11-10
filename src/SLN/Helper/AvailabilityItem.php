<?php

class
SLN_Helper_AvailabilityItem
{
    private $data;
    private $offset;
    private $times = array();
    private $timesTxt = array();

    function __construct($data, $offset = 0)
    {
        $this->data = $data;
        if ($data) {
            if ($data['from'][0] != '00:00') {
                $this->times[] = array(
                    strtotime($data['from'][0]),
                    strtotime($data['to'][0]),
                );
                $this->timesTxt[] = array(
                    $data['from'][0],
                    $data['to'][0],
                );

            }
            if ($data['from'][1] != '00:00') {
                $this->times[] = array(
                    strtotime($data['from'][1]),
                    strtotime($data['to'][1]),
                );
                $this->timesTxt[] = array(
                    $data['from'][1],
                    $data['to'][1],
                );
            }
        }
        if (empty($this->times)) {
            $this->times[] = array(strtotime('00:00'), strtotime('23:59'));
        }
        $this->offset = $offset;
    }

    public function isValidDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }
        $dayOfTheWeek = date("w", strtotime($date)) + 1;

        return isset($this->data['days'][$dayOfTheWeek]) ? true : false;
    }

    public function isValidTime($date, $time)
    {
        if (!$this->isValidDate($date)) {
            return false;
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
            $temp = $t[1] - $this->offset;
            if ($t[0] <= $time && $temp >= $time) {
                return true;
            }
        }

        return false;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function __toString()
    {
        $days = SLN_Func::getDays();
        $ret = array();
        if (isset($this->data['days'])) {
            foreach ($this->data['days'] as $d => $v) {
                $ret[] = $days[$d];
            }
        }
        $allDays = empty($ret);
        $ret = implode('-', $ret);
        $format = SLN_Plugin::getInstance()->format();
        foreach ($this->timesTxt as $t) {
            $ret .= sprintf(' %s/%s', $format->time(new DateTime('1970-01-01 '.$t[0])), $format->time(new DateTime('1970-01-01 '.$t[1])));
        }
        if (empty($ret)) {
            $ret = __('Always', 'salon-booking-system');
        }
        if ($allDays) {
            $ret = __('All days', 'salon-booking-system').$ret;
        }

        return $ret;
    }
}
