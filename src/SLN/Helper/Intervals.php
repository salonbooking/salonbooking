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
            $times = $this->availabilityHelper->getTimes($date);
        }
        $this->times         = $times;
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
        $check        = $this->suggestedDate->format('Y-m');
        foreach ($dates as $date) {
            list($year, $month, $day) = explode('-', $date);
            $this->years[intval($year)]   = true;
            $this->months[intval($month)] = true;
            if (strpos($date, $check) === 0) {
                $this->days[$day] = true;
            }
        }
        foreach ($this->years as $k => $v) {
            $this->years[$k] = $k;
        }

        $months = SLN_Func::getMonths();
        foreach ($this->months as $k => $v) {
            $this->months[$k] = $months[$k];
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
            'years'         => $this->getYears(),
            'months'        => $this->getMonths(),
            'days'          => $this->getDays(),
            'initialDate'   => $this->initialDate,
            'suggestedDate' => $this->suggestedDate,
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
