<?php

class SLN_Helper_TimeInterval
{
    /** @var SLN_Time $from */
    private $from;
    /** @var SLN_Time $to */
    private $to;

    public function __construct(SLN_Time $from, SLN_Time $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function containsTime(SLN_Time $time)
    {
        return $this->from->isLte($time) && $this->to->isGte($time);
    }

    public function containsInterval(SLN_Helper_TimeInterval $time)
    {
        return $this->containsTime($time->getFrom()) && $this->containsTime($this->getTo());
    }

    /**
     * @return SLN_Time
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return SLN_Time
     */
    public function getTo()
    {
        return $this->to;
    }
}
