<?php

class SLN_Helper_Availability
{
    const MAX_DAYS = 365;

    private $settings;
    private $date;
    /** @var  SLN_Helper_AvailabilityDayBookings */
    private $dayBookings;
    /** @var  SLN_Helper_HoursBefore */
    private $hoursBefore;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->settings = $plugin->getSettings();
        $this->initialDate = $plugin->getBookingBuilder()->getDateTime();
    }

    public function getHoursBeforeHelper()
    {
        if (!isset($this->hoursBefore)) {
            $this->hoursBefore = new SLN_Helper_HoursBefore($this->settings);
        }

        return $this->hoursBefore;
    }
    public function getHoursBeforeString(){
        return $this->getHoursBeforeHelper()->getHoursBeforeString();
    }

    public function getDays()
    {
        $interval = $this->getHoursBeforeHelper();
        $from     = $interval->getFromDate();
        $count    = SLN_Func::countDaysBetweenDatetimes($from, $interval->getToDate());
        $ret      = array();
        $avItems  = $this->getItems();
        $hItems   = $this->getHolidaysItems();
        while ($count > 0) {
            $date = $from->format('Y-m-d');
            $count--;
            if ($avItems->isValidDate($date) && $hItems->isValidDate($date) && $this->isValidDate($from)) {
                $ret[] = $date;
            }
            $from->modify('+1 days');
        }

        return $ret;
    }

    public function getTimes($date)
    {

        $ret     = array();
        $avItems = $this->getItems();
        $hItems  = $this->getHolidaysItems();
        $hb      = $this->getHoursBeforeHelper();
        $from = $hb->getFromDate();
        $to = $hb->getToDate();

        foreach (SLN_Func::getMinutesIntervals() as $time) {
            $d = new SLN_DateTime($date->format('Y-m-d') . ' ' . $time);
            if (
                $avItems->isValidDatetime($d)
                && $hItems->isValidDatetime($d)
                && $this->isValidDate($d)
                && $this->isValidTime($d)
                && $d > $from && $d < $to
            ) {
                $ret[$time] = $time;
            }
        }
	SLN_Plugin::addLog(__CLASS__.' getTimes '.print_r($ret,true));

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
        if (empty($this->date) || $this->date->format('Ymd') != $date->format('Ymd')) {
            $obj = SLN_Enum_AvailabilityModeProvider::getService($this->settings->getAvailabilityMode(), $date);
            SLN_Plugin::addLog(__CLASS__.sprintf(' - started %s', get_class($obj)));
            $this->dayBookings = $obj;
        }

        $this->date = $date;

        return $this;
    }

    /**
     * @return SLN_Helper_AvailabilityDayBookings
     */
    public function getDayBookings()
    {
        return $this->dayBookings;
    }

    public function getBookingsDayCount()
    {
        return $this->getDayBookings()->countBookingsByDay();
    }

    public function getBookingsHourCount($hour = null, $minutes = null)
    {
        return $this->getDayBookings()->countBookingsByHour($hour, $minutes);
    }

    public function validateAttendantService(SLN_Wrapper_Attendant $attendant, SLN_Wrapper_Service $service)
    {
        if(!$attendant->hasAllServices()){
            if (!$attendant->hasService($service)) {
                return array(
                    __('This assistant is not available for the selected service', 'salon-booking-system')
                );
            }
        }
    }

    public function validateAttendantServices(SLN_Wrapper_Attendant $attendant, array $services)
    {
        if($attendant->hasAllServices()){
            return;
        }

        /** @var SLN_Wrapper_Service $service */
        foreach($services as $service) {
            if (!$attendant->hasService($service)) {
                return array(
                    __('This assistant is not available for any of the selected services', 'salon-booking-system')
                );
            }
        }
    }

    public function validateAttendant(SLN_Wrapper_Attendant $attendant, $duration = null)
    {

        SLN_Plugin::addLog(__CLASS__.sprintf(' - validate attendant %s by date(%s) and duration(%s)',$attendant,$this->date->format('Ymd H:i'), $duration));
        if($duration){
            $startDate = clone $this->date;
            $endDate = clone $startDate;
            $endDate->modify(sprintf('+%s minutes', SLN_Func::getMinutesFromDuration($duration)));
            $times = $this->filterTimes(SLN_Func::getMinutesIntervals(), $startDate, $endDate);
            foreach($times as $time){
                SLN_Plugin::addLog(__CLASS__.sprintf(' checking time %s', $time->format('Ymd H:i')));
                if ($attendant->isNotAvailableOnDate($time)) {
                    SLN_Plugin::addLog(__CLASS__.sprintf(' - attendant %s by date(%s) not available',$attendant,$time->format('Ymd H:i')));
                    $this->date = $startDate;
                    return array(
                        __('This assistant is not available  ', 'salon-booking-system') . $attendant->getNotAvailableString()
                    );
                } 
                $ids = $this->getDayBookings()->countAttendantsByHour($time->format('H'), $time->format('i'));
                if (isset($ids[$attendant->getId()])) {
                    SLN_Plugin::addLog(__CLASS__.sprintf(' - attendant %s by date(%s) busy',$attendant,$time->format('Ymd H:i')));
                    $this->date = $startDate;
                    return array(
                        __('This assistant is unavailable during this period', 'salon-booking-system') . $attendant->getNotAvailableString()
                    );
                } 
            }
        }else{
            if ($attendant->isNotAvailableOnDate($this->date)) {
                $this->date = $startDate;
                return array(
                    __('This assistant is not available  ', 'salon-booking-system') . $attendant->getNotAvailableString()
                );
            }
            $ids = $this->getDayBookings()->countAttendantsByHour();
            if (isset($ids[$attendant->getId()])) {
                $this->date = $startDate;
                return array(
                    __('This assistant is unavailable during this period', 'salon-booking-system') . $attendant->getNotAvailableString()
                );
            }
        }
        $this->date = $startDate;
    }

    private function filterTimes($times, $startDate, $endDate){
        $ret = array();
        foreach($times as $t){
            $t = new SLN_DateTime($startDate->format('Y-m-d').' '.$t);
            if($t->format('YmdHi') >= $startDate->format('YmdHi') && $t->format('YmdHi') < $endDate->format('YmdHi')){
               SLN_Plugin::addLog(__CLASS__.'->'.__METHOD__.' '.$t->format('YmdHi'));
               $ret[] = $t;
            }
        }
        return $ret;
    }


    public function validateService(SLN_Wrapper_Service $service)
    {
        if ($service->isNotAvailableOnDate($this->date)) {
            return array(
                __('This service is unavailable ', 'salon-booking-system') . $service->getNotAvailableString()
            );
        }
        $ids = $this->getDayBookings()->countServicesByHour();
        if (
            $service->getUnitPerHour() > 0
            && isset($ids[$service->getId()])
            && $ids[$service->getId()] >= $service->getUnitPerHour()
        ) {
            return array(
                __('The service for this hour is currently full', 'salon-booking-system') . $service->getNotAvailableString()
            );
        }
    }

    /**
     * @return SLN_Helper_AvailabilityItems
     */
    public function getItems()
    {
        if (!isset($this->items)) {
            $this->items = new SLN_Helper_AvailabilityItems($this->settings->get('availabilities'));
        }

        return $this->items;
    }

    /**
     * @return SLN_Helper_HolidayItems
     */
    public function getHolidaysItems()
    {
        if (!isset($this->holidayItems)) {
            $this->holidayItems = new SLN_Helper_HolidayItems($this->settings->get('holidays'));
        }

        return $this->holidayItems;
    }

    public function isValidDate($date)
    {
        $this->setDate($date);
        $countDay = $this->settings->get('parallels_day');

        return !($countDay && $this->getBookingsDayCount() >= $countDay);
    }

    public function isValidTime($date)
    {
        if (!$this->isValidDate($date)) {
            return false;
        }
        $countHour = $this->settings->get('parallels_hour');
        return ($date >= $this->initialDate) && !($countHour && $this->getBookingsHourCount($date->format('H'), $date->format('i')) >= $countHour);
    }

    public function getFreeMinutes($date){
        $date = clone $date;
        $ret = 0;
        $interval = $this->settings->getInterval();
        $max = 24*60;

        $avItems = $this->getItems();
        while($avItems->isValidDatetime($date) && $this->isValidTime($date) && $ret <= $max){
            $ret += $interval;
            $date->modify(sprintf('+%s minutes',$interval));
        }
        return $ret;
    }
}
