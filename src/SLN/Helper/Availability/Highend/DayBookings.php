<?php

class SLN_Helper_Availability_Highend_DayBookings extends SLN_Helper_Availability_AbstractDayBookings
{
    /**
     * @return DateTime
     */
    public function getTime($hour = null, $minutes = null)
    {
        if (!isset($hour)) {
            $hour = $this->getDate()->format('H');
        }
        $now = clone $this->getDate();
        $now->setTime($hour, $minutes ? $minutes : 0);

        return $now;
    }

    protected function buildTimeslots()
    {
        $ret = array();
        $interval = min(SLN_Enum_Interval::toArray());
        foreach (SLN_Func::getMinutesIntervals($interval) as $t) {
            $ret[$t] = array('booking' => array(), 'service' => array(), 'attendant' => array());
        }

        $bookingOffsetEnabled = SLN_Plugin::getInstance()->getSettings()->get('reservation_interval_enabled');
        $bookingOffset = SLN_Plugin::getInstance()->getSettings()->get('minutes_between_reservation');

        /** @var SLN_Wrapper_Booking[] $bookings */
        $bookings = $this->bookings;
        foreach ($bookings as $booking) {
            $bookingServices = $booking->getBookingServices();
            foreach ($bookingServices->getItems() as $bookingService) {
                $times = SLN_Func::filterTimes(
                    SLN_Func::getMinutesIntervals($interval),
                    $bookingService->getStartsAt(),
                    $bookingService->getEndsAt()
                );
                foreach ($times as $time) {
                    $time = $time->format('H:i');
                    $ret[$time]['booking'][] = $booking->getId();
                    @$ret[$time]['service'][$bookingService->getService()->getId()]++;
                    @$ret[$time]['attendant'][$bookingService->getAttendant()->getId()]++;
                }

                if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                    $offsetStart = $bookingService->getEndsAt();
                    $offsetEnd = clone  $bookingService->getEndsAt();
                    $offsetEnd = $offsetEnd->modify('+'.$bookingOffset.' minutes');
                    $times = SLN_Func::filterTimes(SLN_Func::getMinutesIntervals($interval), $offsetStart, $offsetEnd);
                    foreach ($times as $time) {
                        $time = $time->format('H:i');
                        $ret[$time]['booking'][] = $booking->getId();
                    }
                }
            }
        }

        return $ret;
    }
}
