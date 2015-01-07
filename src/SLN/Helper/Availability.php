<?php

class SLN_Helper_Availability
{
    private $settings;
    private $availabilities;
    private $date;
    private $bookings;
    private $servicesHourCount;

    public function __construct(SLN_Settings $settings)
    {
        $this->settings = $settings;
    }

    public function __toString()
    {
        $ret = '';

        return (string)$ret;
    }

    public function getAvailabilities()
    {
        if (!isset($this->availabilities)) {
            foreach ($this->settings->get('availabilities') as $item) {
                $this->availabilities = new SLN_Helper_AvailabilityItem($item);
            }
        }
    }

    public function getHoursBefore()
    {
        $from = $this->settings->get('hours_before_from');
        $to   = $this->settings->get('hours_before_to');

        return (object)compact('from', 'to');
    }

    public function getHoursBeforeString()
    {
        $txt = SLN_Func::getIntervalItems();
        $ret = $this->getHoursBefore();
        if ($ret->from) {
            $ret->from = $txt[$ret->from];
        }
        if ($ret->to) {
            $ret->to = $txt[$ret->to];
        }

        return $ret;
    }

    public function getHoursBeforeDateTime()
    {
        $ret     = $this->getHoursBefore();
        $now     = new DateTime();
        $minutes = $this->minutes($now);
        $now->setTime($now->format('H'), $minutes);
        $now2 = clone $now;
        if ($ret->from) {
            $ret->from = $now->modify($ret->from);
        } else {
            $ret->from = $now->modify('+30 minutes');
        }
        if ($ret->to) {
            $ret->to = $now2->modify($ret->to);
        }

        return $ret;
    }

    private function minutes(DateTime $date)
    {
        $interval = $this->settings->getInterval();
        $i        = $date->format('i');
        $ret      = (intval($i / $interval) + 1) * $interval;

        return $ret;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
        $this->buildBookings();

        return $this;
    }

    private function buildBookings()
    {
        $args           = array(
            'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
            'nopaging'   => true,
            'meta_query' => array(
                array(
                    'key'     => '_sln_booking_date',
                    'value'   => $this->date->format('Y-m-d'),
                    'compare' => '=',
                )
            )
        );
        $query          = new WP_Query($args);
        $this->bookings = array();
        foreach ($query->get_posts() as $p) {
            $this->bookings[] = SLN_Plugin::getInstance()->createBooking($p);
        }
        wp_reset_query();
        wp_reset_postdata();
    }

    /**
     * @return SLN_Wrapper_Booking[]
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    public function getBookingsDayCount()
    {
        return count($this->bookings);
    }

    public function getBookingsHourCount()
    {
        return count($this->getBookingsHour());
    }

    /**
     * @return SLN_Wrapper_Booking[]
     */
    public function getBookingsHour()
    {
        $hour = $this->date->format('H');
        $ret  = array();
        foreach ($this->getBookings() as $b) {
            $t = explode(':', $b->getTime());
            if ($t == $hour) {
                $ret[] = $b;
            }
        }

        return $ret;
    }

    public function getServicesHourCount()
    {
        if (!$this->servicesHourCount) {
            $ret = array();
            foreach ($this->getBookingsHour() as $b) {
                foreach ($b->getServicesIds() as $id) {
                    if (isset($ret[$id])) {
                        $ret[$id]++;
                    } else {
                        $ret[$id] = 1;
                    }
                }
            }
            $this->servicesHourCount = $ret;
        }

        return $this->servicesHourCount;
    }

    public function validateService(SLN_Wrapper_Service $service)
    {
        if ($service->isNotAvailableOnDate($this->date)) {
            return array(
                __('this service is not available ', 'sln') . $service->getNotAvailableString()
            );
        }
        $ids = $this->getServicesHourCount();
        if (
            $service->getUnitPerHour() > 0
            && isset($ids[$service->getId()])
            && $ids[$service->getId()] >= $service->getUnitPerHour()
        ) {
            return array(
                __('this service is full in this hour', 'sln') . $service->getNotAvailableString()
            );
        }
    }
}
