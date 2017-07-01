<?php

class SLN_Time
{
    private $time;

    public function __construct($str)
    {
        $this->time = SLN_TimeFunc::evalPickedTime($str);
    }

    public function __toString()
    {
        return $this->time;
    }

    public function isLt(SLN_Time $t)
    {
        return $this->toInt() > $t->toInt();
    }

    public function isGt(SLN_Time $t)
    {
        return $this->toInt() > $t->toInt();
    }

    public function isLte(SLN_Time $t)
    {
        return $this->toInt() <= $t->toInt();
    }

    public function isGte(SLN_Time $t)
    {
        return $this->toInt() >= $t->toInt();
    }

    public function isEq(SLN_Time $t)
    {
        return $this->toInt() == $t->toInt();
    }

    public function toInt()
    {
        return str_replace(':', '', $this->time);
    }

    public function toMinutes()
    {
        $x = explode(':', $this->time);

        return ($x[0] * 60) + $x[1];
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function toDateTime()
    {
        return new \DateTime('1970-01-01 '.$this->toString());
    }

    /**
     * @param int|SLN_Time|null $interval
     * @return SLN_Time
     */
    public function add($interval)
    {
        return self::increment($this, $interval, false);
    }

    /**
     * @param int|SLN_Time|null $interval
     * @return SLN_Time
     */
    public function sub($interval)
    {
        return self::increment($this, $interval, true);
    }


    /**
     * @param SLN_Time          $time
     * @param int|SLN_Time|null $interval
     * @param bool              $negative
     * @return SLN_Time
     */
    public static function increment(SLN_Time $time, $interval = null, $negative = false)
    {
        $interval = self::bindInterval($interval);
        if ($interval == 0) {
            return $time;
        }
        $m = $negative ? ($time->toMinutes() - $interval) : ($time->toMinutes() + $interval);
        $h = floor($m / 60);

        return new SLN_Time(SLN_Func::zerofill($h).':'.SLN_Func::zerofill($m % 60));
    }

    /**
     * @param int|SLN_Time|null $interval
     * @return int
     */
    private static function bindInterval($interval = null)
    {
        if ($interval === null) {
            $interval = SLN_Plugin::getInstance()->getSettings()->getInterval();
        } elseif ($interval instanceof SLN_Time) {
            $interval = $interval->toMinutes();
        }

        return (int)$interval;
    }

    public static function filterTimesArrayByDuration($times, SLN_Time $duration)
    {
        foreach ($times as $k => $t) {
            $t = $t instanceof SLN_Time ? $t : new SLN_Time($t);
            if (!self::checkTimeDuration($times, $t, $duration)) {
                unset($times[$k]);
            }
        }

        return $times;
    }

    public static function checkTimeDuration($times, SLN_Time $time, SLN_Time $duration)
    {
        $end     = SLN_Time::increment($time, $duration);
        $initial = clone $time;
        while ($initial->isLte($time) && $time->isLte($end)) {
            if (!isset($times[(string)$time])) {
                return false;
            }
            $time = SLN_Time::increment($time);
        }

        return true;
    }

    public static function create($time){
        if($time instanceof SLN_Time)
            return $time;
        else
            return new SLN_Time($time);
    }
}
