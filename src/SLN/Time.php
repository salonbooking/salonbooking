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


    public static function increment(SLN_Time $time, $minutes = null)
    {
        if($minutes instanceof SLN_Time) {
            $minutes = $minutes->toMinutes();
        } elseif (!$minutes) {
            $minutes = SLN_Plugin::getInstance()->getSettings()->getInterval();
        }
        $c = new DateTime('1970-01-01 '.$time->time);
        $c->modify('+'.$minutes.' minutes');

        return new SLN_Time($c->format('H:i'));
    }
}
