<?php

use Salon\Util\Date;

class SLN_Helper_Intervals
{
    /** @var  SLN_Helper_Availability */
    protected $availabilityHelper;
    protected $initialDate;
    protected $suggestedDate;

    protected $times;
    protected $years;
    protected $months;
    protected $days;
    protected $dates;
    protected $fullDays = array();

    public function __construct(SLN_Helper_Availability $availabilityHelper)
    {
        $this->availabilityHelper = $availabilityHelper;
    }

    public function setDatetime(DateTime $date, $duration = null)
    {
        $this->initialDate = $this->bindInitialDate($date);
        $ah                = $this->availabilityHelper;
        $times             = $ah->getCachedTimes(Date::create($date), $duration);
        $interval          = $ah->getHoursBeforeHelper();
        $to                = $interval->getToDate();
        $clone             = clone $date;
        while (empty($times) && $date <= $to) {
            $this->fullDays[] = $date->format('Y-m-d');
            $date->modify('+1 days');
            $times = $ah->getCachedTimes( Date::create($date), $duration);
        }
        if (empty($times)) {
            $date = $clone;
            $from = $interval->getFromDate();
            while (empty($times) && $date >= $from) {
                $this->fullDays[] = $date->format('Y-m-d');
                $date->modify('-1 days');
                $times = $ah->getCachedTimes(Date::create($date), $duration);
            }
        }
        $this->times   = $times;
        $suggestedTime = $date->format('H:i');
        $i             = SLN_Plugin::getInstance()->getSettings()->getInterval();
        $timeout = 0;
        if(!isset($times[$suggestedTime])){
            $date->setTime(0,0);
            $suggestedTime = $date->format('H:i');
            while ($timeout < 86400 && !isset($times[$suggestedTime]) && $date <= $to ) {
                $date->modify("+$i minutes");
                $suggestedTime = $date->format('H:i');
                $timeout++;
            }
        }
        $this->suggestedDate = $date;
        $this->bindDates($ah->getDays());
        ksort($this->times);
        ksort($this->years);
        ksort($this->days);
        ksort($this->months);

    }

    public function bindInitialDate($date)
    {
        $from = $this->availabilityHelper->getHoursBeforeHelper()->getFromDate();
        if ($date < $from) {
            $date = $from;
        }

        return $date;
    }

    private function bindDates($dates)
    {
        $this->years  = array();
        $this->months = array();
        $this->days   = array();
        $this->dates  = array();
        $checkDay     = $this->suggestedDate->format('Y-m-');
        $checkMonth   = $this->suggestedDate->format('Y-');
        foreach ($dates as $date) {
            list($year, $month, $day) = explode('-', $date);
            $this->years[$year] = true;
            if (strpos($date, $checkMonth) === 0) {
                $this->months[$month] = true;
            }
            if (strpos($date, $checkDay) === 0) {
                $this->days[$day] = true;
            }
            $this->dates[] = $date;
        }
        foreach ($this->years as $k => $v) {
            $this->years[$k] = $k;
        }

        $months = SLN_Func::getMonths();
        foreach ($this->months as $k => $v) {
            $this->months[$k] = $months[intval($k)];
        }
        foreach ($this->days as $k => $v) {
            $this->days[$k] = $k; //. date_i18n(' l',strtotime($checkDay.$k));
        }
        ksort($this->years);
        ksort($this->months);
        ksort($this->days);
    }

    public function toArray()
    {
        $f = SLN_plugin::getInstance()->format();
        return array(
            'years'          => $this->getYears(),
            'months'         => $this->getMonths(),
            'days'           => $this->getDays(),
            'times'          => $this->getTimes(),
            'dates'          => $this->getDates(),
            'fullDays'       => $this->getFullDays(),
            'suggestedDay'   => $this->suggestedDate->format('d'),
            'suggestedMonth' => $this->suggestedDate->format('m'),
            'suggestedYear'  => $this->suggestedDate->format('Y'),
            'suggestedDate'  => $f->date($this->suggestedDate),
            'suggestedTime'  => $f->time($this->suggestedDate),
        );
    }

    /**
     * @return mixed
     */
    public function getInitialDate()
    {
        return $this->initialDate;
    }

    /**
     * @return mixed
     */
    public function getSuggestedDate()
    {
        return $this->suggestedDate;
    }

    /**
     * @return mixed
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * @return mixed
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     * @return mixed
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @return mixed
     */
    public function getDays()
    {
        return $this->days;
    }
    public function getDates(){
        return $this->dates;
    }

    public function getFullDays(){
        return array_merge(array_unique($this->fullDays), SLN_Plugin::getInstance()->getBookingCache()->getFullDays());
    }
}
