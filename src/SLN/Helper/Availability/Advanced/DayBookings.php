<?php

class SLN_Helper_Availability_Advanced_DayBookings extends SLN_Helper_Availability_AbstractDayBookings
{
    /**
     * @return SLN_Wrapper_Booking[]
     */
    public function getBookingsByHour($hour, $minutes = null)
    {
        if (!isset($hour)) {
            $hour = $this->getDate()->format('H');
        }
        $ret = array();
        foreach ($this->getBookings() as $b) {
            $t = $b->getTime();
            if ($t instanceof DateTime) {
                $t = $t->format('H');
            } else {
                $t = explode(':', $b->getTime());
                $t = $t[0];
            }
            if ($t == $hour) {
                $ret[] = $b;
            }
        }

        return $ret;
    }

    public function countAttendantsByHour($hour = null, $minutes = null)
    {
        $ret = array();
        foreach ($this->getBookingsByHour($hour) as $b) {
            $id = $b->getAttendantId();
            $ret[$id] = 1 + (isset($ret[$id]) ? $ret[$id] : 0);
        }

        return $ret;
    }

    public function countServicesByHour($hour = null, $minutes = null)
    {
        $ret = array();
        foreach ($this->getBookingsByHour($hour) as $b) {
            foreach ($b->getServicesIds() as $id) {
                if (isset($ret[$id])) {
                    $ret[$id]++;
                } else {
                    $ret[$id] = 1;
                }
            }
        }

        return $ret;
    }
}
