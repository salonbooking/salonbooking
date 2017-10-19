<?php

namespace Salon\Util;

class TimeInterval
{
    /** @var Time $from */
    private $from;
    /** @var Time $to */
    private $to;

    public function __construct(Time $from, Time $to)
    {
        $this->from = $from;
        $this->to   = $to;
        if ($this->to == '00:00') {
            $this->to = Time::create('24:00');
        }
    }

    public function isOvernight()
    {
        return $this->to->isLt($this->from);
    }

    public function isAlways()
    {
        return $this->to == '00:00' & $this->from == '24:00';
    }

    public function isNever()
    {
        return $this->from == $this->to;
    }

    public function containsTime(Time $time)
    {
        return $this->from->isLte($time) && $this->to->isGte($time);
    }

    public function containsInterval(TimeInterval $time)
    {
        if ($time->isOvernight() && ! $this->isOvernight()) {
            $ret = $this->to->isLte($time->getFrom()) && $this->from->isGte($time->getTo());
        } else {
            $ret = $this->from->isLte($time->getFrom()) && $this->to->isGte($time->getTo());
        }

        //echo '<br/>'.$time->toString().($ret ? ' ⊂ ' : '⊄').$this->toString();

        return $ret;
    }

    /**
     * @return Time
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Time
     */
    public function getTo()
    {
        return $this->to;
    }

    public function toString()
    {
        return sprintf('[%s,%s]', $this->from, $this->to);
    }
}
