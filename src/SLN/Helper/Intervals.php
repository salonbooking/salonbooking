<?php

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

    public function __construct(SLN_Helper_Availability $availabilityHelper)
    {
        $this->availabilityHelper = $availabilityHelper;
    }

    public function setDatetime(DateTime $date)
    {
        $this->initialDate = $date;
        $ah                = $this->availabilityHelper;
        $times             = $ah->getTimes($date);
        while (empty($times)) {
            $date->modify('+1 days');
            $times = $ah->getTimes($date);
        }
        $this->times   = $times;
        $suggestedTime = $date->format('H:i');
        $i             = SLN_Plugin::getInstance()->getSettings()->getInterval();
        while (!isset($times[$suggestedTime])) {
            $date         = $date->modify("+$i minutes");
            $suggestedTime = $date->format('H:i');
        }
        $this->suggestedDate = $date;
        $this->bindDates($ah->getDays());

    }

    public function bindInitialDate($date)
    {
        $from = $this->availabilityHelper->getHoursBeforeDateTime()->from;
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
        }
        foreach ($this->years as $k => $v) {
            $this->years[$k] = $k;
        }

        $months = SLN_Func::getMonths();
        foreach ($this->months as $k => $v) {
            $this->months[$k] = $months[intval($k)];
        }
        foreach ($this->days as $k => $v) {
            $this->days[$k] = $k;
        }
        ksort($this->years);
        ksort($this->months);
        ksort($this->days);
    }

    public function toArray()
    {
        return array(
            'years'          => $this->getYears(),
            'months'         => $this->getMonths(),
            'days'           => $this->getDays(),
            'times'          => $this->getTimes(),
            'suggestedDay'   => $this->suggestedDate->format('d'),
            'suggestedMonth' => $this->suggestedDate->format('m'),
            'suggestedYear'  => $this->suggestedDate->format('Y'),
            'suggestedTime'  => $this->suggestedDate->format('H:i'),
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
}
