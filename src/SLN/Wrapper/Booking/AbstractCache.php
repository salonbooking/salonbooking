<?php

use Salon\Util\Date;

class SLN_Wrapper_Booking_AbstractCache
{
    const KEY = 'salon_cache';

    /** @var SLN_Plugin */
    protected $plugin;
    protected $settings;
    /** @var SLN_Helper_Availability */
    protected $ah;

    public function load()
    {
        $this->settings = get_option($this->getKey());
    }

    public function save()
    {
        update_option($this->getKey(), $this->settings, false);

        return $this;
    }

    public function getKey(){
        return self::KEY;
    }

    public function removeOld()
    {
        $ah   = $this->ah;
        $hb   = $ah->getHoursBeforeHelper();
        $from = $hb->getFromDate()->format('Ymd');
        foreach (array_keys($this->settings) as $k) {
            $tmp = str_replace('-', '', $k);
            if ($tmp < $from) {
                unset($this->settings[$k]);
            }
        }
    }

    /**
     * @return $this
     */
    public function refreshAll()
    {
        $this->settings = array();
        $ah             = $this->plugin->getAvailabilityHelper();
        $hb             = $ah->getHoursBeforeHelper();
        $from           = Date::create($hb->getFromDate());
        $to             = Date::create($hb->getToDate());
        while ($from->isLte($to)) {
            $this->processDate($from);
            $from = $from->getNextDate();
        }
        $this->save();

        return $this;
    }

    public function processDate(Date $day)
    {
        $ah   = $this->ah;
        $data = array();
        $ah->setDate($day->getDateTime());
        $data['free_slots'] = array_values($ah->getTimes($day));
        $day = new Date($day);
        if (!$data['free_slots']) {
            if (!$ah->getItems()->isValidDate($day)) {
                $data['status'] = 'booking_rules';
            } elseif (!$ah->getHolidaysItems()->isValidDate($day)) {
                $data['status'] = 'holiday_rules';
            } else {
                $data['status'] = 'full';
            }
        } else {
            $data['status'] = 'free';
        }

        foreach ($ah->getDayBookings()->getTimeslots() as $k => $v) {
            if ($v['booking'] || $v['service'] || $v['attendant']) {
                $data['busy_slots'][$k] = $v;
            }
        }
        $this->settings[$day->toString()] = $data;

        return $data;
    }

    public function getFullDays()
    {
        if (!$this->settings) {
            $this->refreshAll();
        }
        $ret = array();
        $now = date("Y-m-d");
        foreach ($this->settings as $day => $v) {
            if ($v['status'] == 'full' && $now <= $day) {
                $ret[] = $day;
            }
        }
        return $ret;
    }

    public function processBooking(SLN_Wrapper_Booking $booking, $isNew = false)
    {
        $id = $booking->getId();
        $this->removeOld();
        if (!$isNew) {
            foreach ($this->settings as $day => $v) {
                if (isset($v['busy_slots'])) {
                    $dayHasBooking = false;
                    foreach ($v['busy_slots'] as $slot) {
                        if (in_array($id, $slot['booking'])) {
                            $dayHasBooking = true;
                        }
                    }
                    if ($dayHasBooking && $booking->getDate()->format('Y-m-d') != $day) {
                        $this->processDate(Date::create($day));
                    }
                }
            }
        }
        $this->processDate(Date::create($booking->getDate()));
        $this->save();
    }

    public function getDay(Date $day)
    {
    	$k = $day->toString();
        if (!isset($this->settings[$k])) {
            $ret = null;
        } else {
            $ret = $this->settings[$k];
        }
        return $ret;
    }
}
