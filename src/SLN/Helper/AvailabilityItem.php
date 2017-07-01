<?php

class SLN_Helper_AvailabilityItem
{
    private $data;
    /** @var SLN_Helper_TimeInterval[] */
    private $times = array();

    private $fromDate;
    private $toDate;


    function __construct($data)
    {
        $this->data = $data;
        if ($data) {
            for($i = 0; $i <= 1; $i++) {
                if ($data['from'][$i] != '00:00') {
                    $this->times[] = new SLN_Helper_TimeInterval(
                        new SLN_Time($data['from'][$i]),
                        new SLN_Time($data['to'][$i])
                    );
                }
            }
            $this->fromDate = isset($data['from_date']) ? strtotime($data['from_date'].' 00:00:00') : null;
            $this->toDate   = isset($data['to_date']) ? strtotime($data['to_date'].' 23:59:59') : null;
        }
        if (empty($this->times)) {
            $this->times[] = new SLN_Helper_TimeInterval(
                new SLN_Time('00:00'),
                new SLN_Time('24:00')
            );
        }
    }

    /**
     * @param $date
     * @return bool
     */
    public function isValidDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }

        return $this->isValidDayOfPeriod($date) && $this->isValidDayOfWeek($date);
    }

    /**
     * @param $date
     * @return bool
     */
    private function isValidDayOfPeriod($date)
    {
        $timestampDate = strtotime($date);
        return !(
            ($this->fromDate && $timestampDate < $this->fromDate)
            || ($this->toDate && $timestampDate > $this->toDate)
        );
    }

    /**
     * @param $date
     * @return bool
     */
    private function isValidDayOfWeek($date)
    {
        $dayOfTheWeek = date("w", strtotime($date)) + 1;

        return isset($this->data['days'][$dayOfTheWeek]) ? true : false;
    }

    /**
     * @param SLN_Time $time
     * @return bool
     */
    public function isValidTime(SLN_Time $time)
    {
        //#SBP-470
        $time2 = $time->toString() == '00:00' ? null : new SLN_Time('24:00');
        foreach ($this->times as $t) {
            if ($t->containsTime($time)) {
                return true;
            }
        }
        //#SBP-470
        return $time2 ? $this->isValidTime($time2) : false;
    }

    /**
     * @param SLN_Helper_TimeInterval $interval
     * @return bool
     */
    public function isValidTimeDuration(SLN_Helper_TimeInterval $interval)
    {
        foreach ($this->times as $t) {
            if ($t->containsInterval($interval)) {
                return true;
            }
        }
        return false;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array|string|void
     */
    public function __toString()
    {
        $days = SLN_Func::getDays();
        $ret  = array();
        if (isset($this->data['days'])) {
            foreach ($this->data['days'] as $d => $v) {
                $ret[] = $days[$d];
            }
        }
        $allDays = empty($ret);
        $ret     = implode('-', $ret);
        $format  = SLN_Plugin::getInstance()->format();
        foreach ($this->times as $t) {
            $ret .= sprintf(
                ' %s/%s',
                $format->time(new DateTime('1970-01-01 '.$t->getFrom()->toString())),
                $format->time(new DateTime('1970-01-01 '.$t->getTo()->toString()))
            );
        }
        if (empty($ret)) {
            $ret = __('Always', 'salon-booking-system');
        }
        if ($allDays) {
            $ret = __('All days', 'salon-booking-system').$ret;
        }

        return $ret;
    }

    /**
     * @return SLN_Helper_TimeInterval[]
     */
    public function getTimes(){
        return $this->times;
    }
}
