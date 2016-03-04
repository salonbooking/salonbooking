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
        $this->initialDate = $plugin->getBookingBuilder()->getEmptyValue();
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
     * @return SLN_Helper_Availability_AbstractDayBookings
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

    public function validateAttendant2(SLN_Wrapper_Attendant $attendant, DateTime $date = null, DateTime $duration = null)
    {
        $interval = min(SLN_Enum_Interval::toArray());

        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? new DateTime('1970-01-01 00:00:00') : $duration;

        $startAt = clone $date;
        $endAt = clone $date;
        $endAt = $endAt->modify('+'.SLN_Func::getMinutesFromDuration($duration).'minutes');

        $times = SLN_Func::filterTimes(SLN_Func::getMinutesIntervals($interval), $startAt, $endAt);
        foreach($times as $time) {
            $time = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));
            if ($attendant->isNotAvailableOnDate($time)) {
                return array(
                    __('This attendant is unavailable ', 'salon-booking-system') . $attendant->getNotAvailableString()
                );
            }

            $ids = $this->getDayBookings()->countAttendantsByHour($time->format('H'), $time->format('i'));
            if (
                isset($ids[$attendant->getId()])
                && $ids[$attendant->getId()] >= 0
            ) {
                return array(
                    sprintf(__('The attendant for %s is currently full', 'salon-booking-system'), $time->format('H:i'))
                );
            }
        }
    }

    public function validateService(SLN_Wrapper_Service $service, DateTime $date = null, DateTime $duration = null)
    {
        $interval = min(SLN_Enum_Interval::toArray());

        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? $service->getDuration() : $duration;

        $startAt = clone $date;
        $endAt = clone $date;
        $endAt = $endAt->modify('+'.SLN_Func::getMinutesFromDuration($duration).'minutes');

        $attendants = $service->getAttendants();
        $times = SLN_Func::filterTimes(SLN_Func::getMinutesIntervals($interval), $startAt, $endAt);
        foreach($times as $time) {
            $time = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));
            if ($service->isNotAvailableOnDate($time)) {
                return array(
                    __('This service is unavailable ', 'salon-booking-system') . $service->getNotAvailableString()
                );
            }

            foreach($attendants as $k => $attendant) {
                if ($this->validateAttendant2($attendant, $time)) {
                    unset($attendants[$k]);
                }
            }

            if (empty($attendants)) {
                return array(
                    __('No one of the assistants is available at ', 'salon-booking-system') . $time->format('H:i')
                );
            }

            $ids = $this->getDayBookings()->countServicesByHour($time->format('H'), $time->format('i'));
            if (
                $service->getUnitPerHour() > 0
                && isset($ids[$service->getId()])
                && $ids[$service->getId()] >= $service->getUnitPerHour()
            ) {
                return array(
                    sprintf(__('The service for %s is currently full', 'salon-booking-system'), $time->format('H:i'))
                );
            }
        }
    }

    public function returnValidatedServices(array $servicesIds) {
        $date = $this->date;
        $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($servicesIds, 0), $date);
        $validated = array();
        foreach($bookingServices->getItems() as $bookingService){
            $serviceErrors = $this->validateService($bookingService->getService(), $bookingService->getStartsAt());
            if (empty($serviceErrors)) {
                $validated[] = $bookingService->getService()->getId();
            }
            else {
                break;
            }
        }
        return $validated;
    }

    /**
     * @param array $order
     * @param SLN_Wrapper_Service[] $newServices
     *
     * @return array
     */
    public function checkEachOfNewServicesForExistOrder($order, $newServices) {
        $ret = array();
        $date = $this->date;
        foreach($newServices as $service) {
            $services = $order;
            if (!in_array($service->getId(), $services)) {
                $services[] = $service->getId();
                $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($services, 0), $date);
                foreach($bookingServices->getItems() as $bookingService){
                    $serviceErrors = $this->validateService($bookingService->getService(), $bookingService->getStartsAt());
                    if (!empty($serviceErrors)) {
                        if ($bookingService->getService()->getId() == $service->getId()) {
                            $error = $serviceErrors[0];
                        }
                        else {
                            $tmp = $bookingServices->findByService($service->getId());
                            $error = __('You already selected service at') . ($tmp ? ' ' . $tmp->getStartsAt()->format('H:i') : '');
                        }
                        $ret[$service->getId()] = array($error);
                        break;
                    }
                }

                if (!isset($ret[$service->getId()])) {
                    $ret[$service->getId()] = array();
                }
            }
        }

        return $ret;
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
