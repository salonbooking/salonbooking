<?php

class SLN_Helper_Availability_Highend_DayBookings extends SLN_Helper_Availability_AbstractDayBookings
{

    protected static $cachedTime = array();

    /**
     * @return DateTime
     */
    public function getTime($hour = null, $minutes = null)
    {
        if (!isset($hour)) {
            $hour = $this->getDate()->format('H');
        }

        $cachedTimeKey = "$hour:$minutes";
        if (isset(self::$cachedTime[$cachedTimeKey])) {
            return clone self::$cachedTime[$cachedTimeKey];
        }

        $now = clone $this->getDate();
        $now->setTime($hour, $minutes ? $minutes : 0);

        self::$cachedTime[$cachedTimeKey] = clone $now;

        return $now;
    }

    protected function buildTimeslots()
    {
        $ret = array();
        foreach ($this->minutesIntervals as $t) {
            $ret[$t] = array('booking' => array(), 'service' => array(), 'attendant' => array());
        }

        $settings = SLN_Plugin::getInstance()->getSettings();
        $bookingOffsetEnabled = $settings->get('reservation_interval_enabled');
        $bookingOffset = $settings->get('minutes_between_reservation');

        /** @var SLN_Wrapper_Booking[] $bookings */
        $bookings = $this->bookings;
        foreach ($bookings as $booking) {
            $bookingServices = $booking->getBookingServices();
            foreach ($bookingServices->getItems() as $bookingService) {
                $times = SLN_Func::filterTimes(
                    $this->minutesIntervals,
                    $bookingService->getStartsAt(),
                    $bookingService->getEndsAt()
                );
                foreach ($times as $time) {
                    $time = $time->format('H:i');
                    $ret[$time]['booking'][] = $booking->getId();
                    @$ret[$time]['service'][$bookingService->getService()->getId()]++;
                    if ($bookingService->getAttendant()) {
                        @$ret[$time]['attendant'][$bookingService->getAttendant()->getId()]++;
                    }
                }

                if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                    $offsetStart = $bookingService->getEndsAt();
                    $offsetEnd = clone  $bookingService->getEndsAt();
                    $offsetEnd = $offsetEnd->modify('+'.$bookingOffset.' minutes');
                    $times = SLN_Func::filterTimes($this->minutesIntervals, $offsetStart, $offsetEnd);
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
