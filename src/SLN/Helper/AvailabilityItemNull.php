<?php

use Salon\Util\Date;
use Salon\Util\Time;
use Salon\Util\TimeInterval;

class SLN_Helper_AvailabilityItemNull extends SLN_Helper_AvailabilityItem
{
    public function isValidDate( Date $date)
    {
        return true;
    }

    public function isValidTime( Time $time)
    {
        return true;
    }

    public function isValidTimeInterval( TimeInterval $interval)
    {
        return true;
    }

    public function __toString()
    {
        return 'Follow general timetable';
    }
}
