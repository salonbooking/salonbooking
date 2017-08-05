<?php

use Salon\Util\Date;

class SLN_Wrapper_Booking_Cache extends SLN_Wrapper_Booking_AbstractCache
{
    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->ah     = $plugin->getAvailabilityHelper();
        $this->load();
    }

    public function processBooking(SLN_Wrapper_Booking $booking, $isNew = false)
    {
        do_action('sln.booking_cache.processBooking', $booking, $isNew);

        return parent::processBooking($booking, $isNew);
    }

    public function getDay(Date $day)
    {
        $ret = parent::getDay($day);

        return apply_filters('sln.booking_cache.getDay', $ret, $day);
    }

    public function getFullDays()
    {
        $ret = parent::getFullDays();

        return apply_filters('sln.booking_cache.getFullDays', $ret);
    }

    public function hasFullDay(Date $date){
    	$fullDays = $this->getFullDays();
	    return $fullDays && in_array($date->toString(), $fullDays);
    }

    public function refreshAll()
    {
        do_action('sln.booking_cache.refreshAll');

        return parent::refreshAll();
    }
}
