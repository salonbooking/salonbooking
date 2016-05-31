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

    protected function buildTimeslots() {
        $ret = array();
        foreach($this->minutesIntervals as $t) {
            $ret[$t] = array('booking' => array(), 'service' => array(), 'attendant' => array());
        }

        /** @var SLN_Wrapper_Booking[] $bookings */
        $bookings = $this->bookings;
        foreach($bookings as $booking) {
            $time = $booking->getStartsAt()->format('H:i');
            $ret[$time]['booking'][] = $booking->getId();
            $bookingServices = $booking->getBookingServices();
            foreach ($bookingServices->getItems() as $bookingService) {
                $sid = $bookingService->getService()->getId();
                $ret[$time]['service'][$sid] = (isset($ret[$time]['service'][$sid]) ? $ret[$time]['service'][$sid] : 0) +1;
                if($bookingService->getAttendant()){
                    $aid = $bookingService->getAttendant()->getId();
                    $ret[$time]['attendant'][$aid] = (isset($ret[$time]['attendant'][$aid]) ? $ret[$time]['attendant'][$aid] : 0) +1;
                }
            }
        }

        return $ret;
    }
}
