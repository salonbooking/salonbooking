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
        return ($x[0]*60)+$x[1];
    }


    public static function increment(SLN_Time $time, $interval = null)
    {
        if($interval instanceof SLN_Time) {
            $interval = $interval->toMinutes();
        } elseif (!$interval) {
            $interval = SLN_Plugin::getInstance()->getSettings()->getInterval();
        }
        $m = $time->toMinutes() + $interval;
        $h = floor($m/60);
        return new SLN_Time($h.':'.($h % 60));
    }
}
