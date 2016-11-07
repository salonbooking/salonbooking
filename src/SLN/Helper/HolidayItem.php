<?php

class SLN_Helper_HolidayItem
{
    private $data;
    private $weekDayRules;

    function __construct($data, $weekDayRules = null)
    {
        $this->data = $data;
        $this->data['from_date'] = isset($this->data['from_date']) ? $this->data['from_date'] : '0';
        $this->data['to_date']   = isset($this->data['to_date'])   ? $this->data['to_date']   : '0';
        $this->data['from_time'] = isset($this->data['from_time']) ? $this->data['from_time'] : '00:00';
        $this->data['to_time']   = isset($this->data['to_time'])   ? $this->data['to_time']   : '00:00';

        $this->weekDayRules = $weekDayRules;
    }

    public function isValidDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }

        $timestampDate = strtotime($date);
        if ($timestampDate < strtotime($this->data['from_date']) || $timestampDate > strtotime($this->data['to_date'].' 23:59:59')) {
            return true;
        }

        if (!empty($this->weekDayRules)) {
            $weekDay = (int) date("w", $timestampDate);
            if ( isset( $this->weekDayRules[ $weekDay]) && !empty( $this->weekDayRules[ $weekDay])) {
                $rules = $this->weekDayRules[ $weekDay];
                for($i = 0; $i < count($rules['from']); $i++) {
                    if ($this->isValidTime($date.' '.$rules['from'][$i]) || $this->isValidTime($date.' '.$rules['to'][$i])) {
                        return true;
                    }
                }
                return false;
            }
        }

        return ($this->isValidTime($date) || $this->isValidTime($date.' 23:59:59'));
    }

    public function isValidTime($date)
    {
        $date = strtotime($date);
        return ($date < strtotime($this->data['from_date'].' '.$this->data['from_time']) || $date >= strtotime($this->data['to_date'].' '.$this->data['to_time']));
    }

}
