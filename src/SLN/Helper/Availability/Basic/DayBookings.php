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
