<?php

class SLN_Wrapper_Booking_Cache
{
    const KEY = 'salon_cache';

    /** @var SLN_Plugin */
    private $plugin;
    private $settings;
    /** @var SLN_Helper_Availability */
    private $ah;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->ah = $plugin->getAvailabilityHelper();
        $this->load();
    }

    public function load()
    {
        $this->settings = get_option(self::KEY);
    }

    public function save()
    {
        update_option(self::KEY, $this->settings, false);

        return $this;
    }

    public function removeOld()
    {
        $ah = $this->ah;
        $hb = $ah->getHoursBeforeHelper();
        $from = $hb->getFromDate()->format('Ymd');
        foreach (array_keys($this->settings) as $k) {
            $tmp = str_replace('-', '',$k);
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
        $ah = $this->plugin->getAvailabilityHelper();
        $hb = $ah->getHoursBeforeHelper();
        $from = $hb->getFromDate();
        $to = $hb->getToDate();
        $clone = clone $from;
        while ($clone <= $to) {
            $this->processDate($clone, $ah);
            $clone->modify('+1 days');
        }
        $this->save();

        return $this;
    }

    private function processDate($day)
    {
        $ah = $this->ah;
        $data = array();
        $data['free_slots'] = array_values($ah->getTimes($day));
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

        foreach ($ah->getDayBookings($day)->getTimeslots() as $k => $v) {
            if ($v['booking'] || $v['service'] || $v['attendant']) {
                $data['busy_slots'][$k] = $v;
            }
        }
        $this->settings[$day->format('Y-m-d')] = $data;

        return $data;
    }

    public function getFullDays()
    {
        if (!$this->settings) {
            $this->refreshAll();
        }
        $ret = array();
        foreach ($this->settings as $day => $v) {
            if ($v['status'] == 'full') {
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
                        $this->processDate(new DateTime($day));
                    }
                }
            }
        }
        $this->processDate($booking->getDate());
        $this->save();
    }
}
