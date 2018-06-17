<?php

class SLN_Helper_Availability_Basic_DayBookings extends SLN_Helper_Availability_AbstractDayBookings
{

    /**
     * @return DateTime
     */
    public function getTime($hour = null, $minutes = null) {
        $now = clone $this->getDate();
        $now->setTime($hour, $minutes ? $minutes : 0);

        return $now;
    }

    protected function buildTimeslots() {
        $ret = array();
        $formattedDate = $this->getDate()->format('Y-m-d');
        
        foreach($this->minutesIntervals as $t) {
            $ret[$t] = array('booking' => array(), 'service' => array(), 'attendant' => array(),'holidays' => array());
            if($this->holidays){
                foreach ($this->holidays as $holiday){
                    $hData = $holiday->getData();
                    if( !$holiday->isValidTime($formattedDate.' '.$t)) $ret[$t]['holidays'][] = $hData;
                }
            }
        }

        /** @var SLN_Wrapper_Booking[] $bookings */
        $bookings = $this->bookings;
        foreach($bookings as $booking) {
            $time = $booking->getStartsAt()->format('H:i');
            $ret[$time]['booking'][] = $booking->getId();
            $bookingServices = $booking->getBookingServices();
            foreach ($bookingServices->getItems() as $bookingService) {
                @$ret[$time]['service'][$bookingService->getService()->getId()] ++;
                if ($bookingService->getAttendant()) {
                    @$ret[$time]['attendant'][$bookingService->getAttendant()->getId()]++;
                }
            }
        }

        return $ret;
    }
}
