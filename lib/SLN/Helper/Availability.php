<?php

class SLN_Helper_Availability
{
    private $settings;
    private $availabilities;
    private $date;
    private $bookings;

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
            $ret->from = $now->add(new DateInterval($ret->from));
        } else {
            $ret->from = $now;
        }
        if ($ret->to) {
            $ret->to = $now2->add(new DateInterval($ret->to));
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
        $args  = array(
            'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
            'nopaging'  => true,
            'meta_query' => array(
                array(
                    'key'     => '_sln_booking_date',
                    'value'   => $date->format('Y-m-d'),
                    'compare' => '=',
                )
            )
        );
        $query = new WP_Query($args);
        $this->bookings   = array();
        foreach ($query->get_posts() as $p) {
            $this->bookings[] = SLN_Plugin::getInstance()->createBooking($p);
        }
        wp_reset_query();
        wp_reset_postdata();
    }

    /**
     * @return SLN_Wrapper_Booking[]
     */
    public function getBookings(){
       return $this->bookings;
    }
    public function getBookingsDayCount(){
        return count($this->bookings);
    }
    public function getBookingsHourCount(){
        $hour = $this->date->format('H');
        $ret = 0;
        foreach($this->getBookings() as $b){
            $t = explode(':',$b->getTime());
            if($t == $hour){
                $ret++;
            }
        }
        return $ret;
    }
}