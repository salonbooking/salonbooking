<?php

use Salon\Util\Date;

class SLN_Helper_HolidayItem
{
    private $data;
    private $weekDayRules;

    function __construct($data, $weekDayRules = null)
    {
        $this->data              = $data;
        $this->data['from_date'] = isset($this->data['from_date']) ? $this->data['from_date'] : '0';
        $this->data['to_date']   = isset($this->data['to_date']) ? $this->data['to_date'] : '0';
        $this->data['from_time'] = isset($this->data['from_time']) ? $this->data['from_time'] : '00:00';
        $this->data['to_time']   = isset($this->data['to_time']) ? $this->data['to_time'] : '00:00';

        $this->weekDayRules = $weekDayRules;
    }

    public function isValidDate($date)
    {
	    if ( $date instanceof DateTime ) {
		    $date = $date->format( 'Y-m-d' );
	    } elseif ( $date instanceof Date ) {
		    $date = $date->toString();
	    }

        $timestampDate = strtotime($date);
        $min           = strtotime($this->data['from_date']);
        $max           = strtotime($this->data['to_date'].' 23:59:59');
        if ($timestampDate < $min || $timestampDate > $max) {
            return true;
        }

        $ret = $this->processWeekDayRules($date);
        if ($ret !== null) {
            return $ret;
        } else {
            return ($this->isValidTime($date) || $this->isValidTime($date.' 23:59:59'));
        }
    }

    private function processWeekDayRules($date)
    {
        $rules = $this->weekDayRules;
        if (empty($rules)) {
            return;
        }
        $weekDay = (int)date("w", strtotime($date));
        if (isset($rules[$weekDay]) && !empty($rules[$weekDay])) {
            $rules = $rules[$weekDay];
            for ($i = 0; $i < count($rules['from']); $i++) {
                $from = $date.' '.$rules['from'][$i];
                $to   = $date.' '.$rules['to'][$i];
                if ($this->isValidTime($from) || $this->isValidTime($to)) {
                    return true;
                }
            }

            return false;
        }
    }

    public function isValidTime($date)
    {
        $date = strtotime($date);
        $from = $this->data['from_date'].' '.$this->data['from_time'];
        $to   = $this->data['to_date'].' '.$this->data['to_time'];

        return ($date < strtotime($from) || $date >= strtotime($to));
    }

    /**
     * @param null $weekDayRules
     */
    public function setWeekDayRules($weekDayRules)
    {
        $this->weekDayRules = $weekDayRules;
    }


}
