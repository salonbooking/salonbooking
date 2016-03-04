<?php


abstract class SLN_Helper_Availability_AbstractDayBookings
{
    protected $bookings;
    protected $timeslots;
    protected $date;

    /** @return SLN_Wrapper_Booking[] :q*/
    abstract public function getBookingsByHour($hour = null, $minutes = null);
    /**
     * @return array
     */
    abstract public function getCountAttendantsByHour($hour = null, $minutes = null);

    /**
     * @return array
     */
    abstract public function getCountServicesByHour($hour = null, $minutes = null);

    /**
     * @return array
     */
    abstract protected function buildTimeslots();

    abstract public function getTime($hour = null, $minutes = null);


    public function __construct(DateTime $date)
    {
        $this->date = $date;
        $this->bookings = $this->buildBookings();
        $this->timeslots = $this->buildTimeslots();
    }



    private function buildBookings()
    {
        $args = array(
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
        $query = new WP_Query($args);
        $ret = array();
        $noTimeStatuses = SLN_Enum_BookingStatus::$noTimeStatuses;
        foreach ($query->get_posts() as $p) {
            $tmp = SLN_Plugin::getInstance()->createBooking($p);
            if(!$tmp->hasStatus($noTimeStatuses))
                $ret[] = $tmp;
        }
        wp_reset_query();
        wp_reset_postdata();

        SLN_Plugin::addLog(__CLASS__.' - buildBookings('.$this->date->format('Y-m-d').')');
        foreach($ret as $b)
            SLN_Plugin::addLog(' - '.$b->getId());
        return $ret;
    }

    public function countBookingsByDay()
    {
        return count($this->bookings);
    }

    public function countBookingsByHour($hour = null, $minutes = null)
    {
        return count($this->getBookingsByHour($hour, $minutes));
    }

    public function countAttendantsByHour($hour = null, $minutes = null)
    {
        SLN_Plugin::addLog(get_class($this).' - count attendants by hour('.$hour.') minutes('.$minutes.')');
        $ret = $this->getCountAttendantsByHour($hour, $minutes);
        SLN_Plugin::addLog(print_r($ret, true));

        return $ret;
    }

    public function countServicesByHour($hour = null, $minutes = null)
    {
        SLN_Plugin::addLog(get_class($this).' - count services by hour('.$hour.') minutes('.$minutes.')');
        $ret = $this->getCountServicesByHour($hour, $minutes);
        SLN_Plugin::addLog(print_r($ret, true));
        return $ret;
    }

    /**
     * @return DateTime
     */
    protected function getDate()
    {
        return $this->date;
    }

    /**
     * @return SLN_Wrapper_Booking[]
     */
    protected function getBookings()
    {
        return $this->bookings;
    }
}
