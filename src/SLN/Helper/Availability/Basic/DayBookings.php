<?php

class SLN_Helper_Availability_Basic_DayBookings extends SLN_Helper_Availability_AbstractDayBookings
{

    /**
     * @return DateTime
     */
    public function getTime($hour = null, $minutes = null) {
        $now = clone $this->getDate();
        return $now;
    }

    /**
     * @return SLN_Wrapper_Booking[]
     */
    public function getBookingsByHour($hour = null, $minutes = null)
    {
        // for Basic mode $now always BookingBuilder Start Time
        $now = $this->getTime($hour, $minutes);
        $time = $now->format('H:i');
        $ret = array();
        $bookings = $this->timeslots[$time]['booking'];
        foreach($bookings as $bId) {
            $ret[] = new SLN_Wrapper_Booking($bId);
        }

        if(!empty($ret)){
            SLN_Plugin::addLog(__CLASS__.' - checking hour('.$hour.')');
            SLN_Plugin::addLog(__CLASS__.' - found('.count($ret).')');
            foreach($ret as $b){
                SLN_Plugin::addLog(' - ' . $b->getId(). ' => '.$b->getStartsAt()->format('H:i').' - '.$b->getEndsAt()->format('H:i'));
            }
        }else{
            SLN_Plugin::addLog(__CLASS__.' - checking hour('.$hour.') EMPTY');
        }
        return $ret;
    }

    /**
     * @return array
     */
    public function getCountAttendantsByHour( $hour = null, $minutes = null )
    {
        // for Basic mode $now always BookingBuilder Start Time
        $now = $this->getTime($hour, $minutes);
        $time = $now->format('H:i');
        $ret = $this->timeslots[$time]['attendant'];
        return $ret;
    }

    /**
     * @return array
     */
    public function getCountServicesByHour($hour = null, $minutes = null)
    {
        // for Basic mode $now always BookingBuilder Start Time
        $now = $this->getTime($hour, $minutes);
        $time = $now->format('H:i');
        $ret = $this->timeslots[$time]['service'];
        return $ret;
    }

    protected function buildTimeslots() {
        $ret = array();
        $interval = min(SLN_Enum_Interval::toArray());
        foreach(SLN_Func::getMinutesIntervals($interval) as $t) {
            $ret[$t] = array('booking' => array(), 'service' => array(), 'attendant' => array());
        }

        /** @var SLN_Wrapper_Booking[] $bookings */
        $bookings = $this->bookings;
        foreach($bookings as $booking) {
            $time = $booking->getStartsAt()->format('H:i');
            $ret[$time]['booking'][] = $booking->getId();
            $bookingServices = $booking->getBookingServices();
            foreach ($bookingServices->getItems() as $bookingService) {
                @$ret[$time]['service'][$bookingService->getService()->getId()] ++;
                @$ret[$time]['attendant'][$bookingService->getAttendant()->getId()] ++;
            }
        }

        return $ret;
    }
}
