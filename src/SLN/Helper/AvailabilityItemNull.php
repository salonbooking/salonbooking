<?php

class SLN_Helper_AvailabilityItemNull extends SLN_Helper_AvailabilityItem
{
    public function isValidDate($date)
    {
        return true;
    }

    public function isValidTime(SLN_Time $time)
    {
        return true;
    }

    public function isValidTimeInterval(SLN_Helper_TimeInterval $interval)
    {
        return true;
    }

    public function __toString()
    {
        return 'Not defined';
    }
}
