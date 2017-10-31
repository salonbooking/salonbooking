<?php

namespace Salon\Util;

use SLN_Func;
use SLN_Plugin;

class Time
{
    private $time;

    /**
     * @param $time
     *
     * @return Time
     */
    public static function create($time)
    {
        if ($time instanceof Time) {
            $ret = $time;
        } elseif ($time instanceof \DateTime) {
            $ret = new Time($time->format('H:i'));
        } else {
            $ret = new Time($time);
        }

        return $ret;
    }

    private static function parseInt($int)
    {
        $h = floor($int / 60);

        return SLN_Func::zerofill($h).':'.SLN_Func::zerofill($int % 60);
    }

    private static function parseStr($str){
        if($str == '23:59') return 24*60;
        if ( ! strpos($str, ':')) {
            throw new \Exception('bad time value'.$str);
        }

        $x = explode(':', $str);

        return ($x[0] * 60) + $x[1];
    }

    public function __construct($str)
    {
        if (is_int($str)) {
            $this->time = self::parseStr(self::parseInt($str));
        } else {
            $this->time = self::parseStr($str);
            if (empty($this->time)) {
                $this->time = 0;
            }
        }

        if ($this->time > 24*60) {
            $this->time = $this->time % (24*60);
        }
    }


    /**
     * @return \DateTime
     */
    public function toDateTime()
    {
        return new \DateTime('1970-01-01 '.$this->toString());
    }

    /**
     * @return bool
     */
    public function isMidnight()
    {
        $t = $this->time;

        return $t == 0 || $t == (24*60);
    }

    /**
     * @param Time $t
     *
     * @return bool
     */
    public function isLt(Time $t)
    {
        return $this->time < $t->time;
    }

    /**
     * @param Time $t
     *
     * @return bool
     */
    public function isGt(Time $t)
    {
        return $this->time  > $t->time;
    }

    /**
     * @param Time $t
     *
     * @return bool
     */
    public function isLte(Time $t)
    {
        return $this->time <= $t->time;
    }

    /**
     * @param Time $t
     *
     * @return bool
     */
    public function isGte(Time $t)
    {
        return $this->time >= $t->time;
    }

    /**
     * @param Time $t
     *
     * @return bool
     */
    public function isEq(Time $t)
    {
        return $this->time == $t->time;
    }



    /**
     * @return int
     */
    public function toMinutes()
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    public function toString()
    {
        return (string)
          SLN_Func::zerofill($this->getHours())
          .':'.SLN_Func::zerofill($this->getMinutes());
    }

    /**
    * @return int
    */
    public function toInt()
    {
        return intval(str_replace(':', '', $this->toString));
    }

    /**
     * @param int|Time|null $interval
     *
     * @return Time
     */
    public function add($interval)
    {
        return self::increment($this, $interval, false);
    }

    /**
     * @param int|Time|null $interval
     *
     * @return Time
     */
    public function sub($interval)
    {
        return self::increment($this, $interval, true);
    }

    public function getHours()
    {
        return intval($this->time / 60);
    }

    public function getMinutes()
    {
        return $this->time - $this->getHours() * 60;
    }

    /**
     * @param Time          $time
     * @param int|Time|null $interval
     * @param bool          $negative
     *
     * @return Time
     */
    public static function increment(Time $time, $interval = null, $negative = false)
    {
        $interval = self::bindInterval($interval);
        if ($interval == 0) {
            return $time;
        }
        $m = $negative ? ($time->toMinutes() - $interval) : ($time->toMinutes() + $interval);
        $h = floor($m / 60);

        return new Time(SLN_Func::zerofill($h).':'.SLN_Func::zerofill($m % 60));
    }

    /**
     * @param int|Time|null $interval
     *
     * @return int
     */
    private static function bindInterval($interval = null)
    {
        if ($interval === null) {
            $interval = SLN_Plugin::getInstance()->getSettings()->getInterval();
        } elseif ($interval instanceof Time) {
            $interval = $interval->toMinutes();
        } elseif ($interval instanceof \DateTime) {
            $interval = Time::create($interval)->toMinutes();
        }

        return (int)$interval;
    }

    /**
     * @param      $times
     * @param Time $duration
     *
     * @return mixed
     */
    public static function filterTimesArrayByDuration($times, Time $duration)
    {
        foreach ($times as $k => $t) {
            $t = $t instanceof Time ? $t : Time::create($t);
            if ( ! self::checkTimeDuration($times, $t, $duration)) {
                unset($times[$k]);
            }
        }

        return $times;
    }

    /**
     * @param      $times
     * @param Time $time
     * @param Time $duration
     *
     * @return bool
     */
    public static function checkTimeDuration($times, Time $time, Time $duration)
    {
        $end = Time::increment($time, $duration);
        do {
            if ( ! isset($times[(string)$time])) {
                return false;
            }
            $time = Time::increment($time);
        } while ($time->isLt($end));

        return true;
    }
}
