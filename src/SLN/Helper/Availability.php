<?php

class SLN_Helper_Availability
{
    const MAX_DAYS = 365;

    private $settings;
    private $date;
    /** @var  SLN_Helper_Availability_AbstractDayBookings */
    private $dayBookings;
    /** @var  SLN_Helper_HoursBefore */
    private $hoursBefore;
    private $attendantsEnabled;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->settings = $plugin->getSettings();
        $this->initialDate = $plugin->getBookingBuilder()->getEmptyValue();
        $this->attendantsEnabled = $this->settings->isAttendantsEnabled();
    }

    public function getHoursBeforeHelper()
    {
        if (!isset($this->hoursBefore)) {
            $this->hoursBefore = new SLN_Helper_HoursBefore($this->settings);
        }

        return $this->hoursBefore;
    }

    public function getHoursBeforeString()
    {
        return $this->getHoursBeforeHelper()->getHoursBeforeString();
    }

    public function getDays()
    {
        $interval = $this->getHoursBeforeHelper();
        $from = $interval->getFromDate();
        $count = SLN_Func::countDaysBetweenDatetimes($from, $interval->getToDate());
        $ret = array();
        $avItems = $this->getItems();
        $hItems = $this->getHolidaysItems();
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

        $ret = array();
        $avItems = $this->getItems();
        $hItems = $this->getHolidaysItems();
        $hb = $this->getHoursBeforeHelper();
        $from = $hb->getFromDate();
        $to = $hb->getToDate();

        foreach (SLN_Func::getMinutesIntervals() as $time) {
            $d = new SLN_DateTime($date->format('Y-m-d').' '.$time);
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
        SLN_Plugin::addLog(__CLASS__.' getTimes '.print_r($ret, true));

        return $ret;
    }

    private function minutes(DateTime $date)
    {
        $interval = $this->settings->getInterval();
        $i = $date->format('i');
        $ret = (intval($i / $interval) + 1) * $interval;

        return $ret;
    }

    public function setDate(DateTime $date, SLN_Wrapper_Booking $booking = null)
    {
        if (empty($this->date) || $this->date->format('Ymd') != $date->format('Ymd')) {
            $obj = SLN_Enum_AvailabilityModeProvider::getService(
                $this->settings->getAvailabilityMode(),
                $date,
                $booking
            );
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
        if (!$attendant->hasAllServices()) {
            if (!$attendant->hasService($service)) {
                return array(
                    __('This assistant is not available for the selected service', 'salon-booking-system'),
                );
            }
        }
    }

    public function validateAttendantServices(SLN_Wrapper_Attendant $attendant, array $services)
    {
        if ($attendant->hasAllServices()) {
            return;
        }

        /** @var SLN_Wrapper_Service $service */
        foreach ($services as $service) {
            if (!$attendant->hasService($service)) {
                return array(
                    __('This assistant is not available for any of the selected services', 'salon-booking-system'),
                );
            }
        }
    }

    public function validateAttendant(
        SLN_Wrapper_Attendant $attendant,
        DateTime $date = null,
        DateTime $duration = null
    ) {
        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? new DateTime('1970-01-01 00:00:00') : $duration;

        SLN_Plugin::addLog(
            __CLASS__.sprintf(
                ' - validate attendant %s by date(%s) and duration(%s)',
                $attendant,
                $date->format('Ymd H:i'),
                $duration->format('H:i')
            )
        );

        $startAt = clone $date;
        $endAt = clone $date;
        $endAt->modify('+'.SLN_Func::getMinutesFromDuration($duration).'minutes');

        $interval = min(SLN_Enum_Interval::toArray());
        $times = SLN_Func::filterTimes(SLN_Func::getMinutesIntervals($interval), $startAt, $endAt);
        foreach ($times as $time) {
            SLN_Plugin::addLog(__CLASS__.sprintf(' checking time %s', $time->format('Ymd H:i')));
            $time = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));
            if ($attendant->isNotAvailableOnDate($time)) {
                SLN_Plugin::addLog(
                    __CLASS__.sprintf(' - attendant %s by date(%s) not available', $attendant, $time->format('Ymd H:i'))
                );

                return array(
                    __('This attendant is unavailable ', 'salon-booking-system').$attendant->getNotAvailableString(),
                );
            }

            $ids = $this->getDayBookings()->countAttendantsByHour($time->format('H'), $time->format('i'));
            if (
                isset($ids[$attendant->getId()])
                && $ids[$attendant->getId()] >= 0
            ) {
                SLN_Plugin::addLog(
                    __CLASS__.sprintf(' - attendant %s by date(%s) busy', $attendant, $time->format('Ymd H:i'))
                );

                return array(
                    sprintf(__('This assistant is full at %s', 'salon-booking-system'), $time->format('H:i')),
                );
            }
        }
    }

    public function validateService(SLN_Wrapper_Service $service, DateTime $date = null, DateTime $duration = null)
    {
        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? $service->getDuration() : $duration;

        SLN_Plugin::addLog(
            __CLASS__.sprintf(
                ' - validate service %s by date(%s) and duration(%s)',
                $service,
                $date->format('Ymd H:i'),
                $duration->format('H:i')
            )
        );

        $startAt = clone $date;
        $endAt = clone $date;
        $endAt->modify('+'.SLN_Func::getMinutesFromDuration($duration).'minutes');

        $interval = min(SLN_Enum_Interval::toArray());
        $times = SLN_Func::filterTimes(SLN_Func::getMinutesIntervals($interval), $startAt, $endAt);
        foreach ($times as $time) {
            SLN_Plugin::addLog(__CLASS__.sprintf(' checking time %s', $time->format('Ymd H:i')));
            $time = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));

            if (!$this->isValidOnlyTime($time)) {
                SLN_Plugin::addLog(
                    __CLASS__.sprintf(' - limit of parallels bookings at date(%s)', $time->format('Ymd H:i'))
                );

                return array(
                    __('Limit of parallels bookings at ', 'salon-booking-system').$time->format('H:i'),
                );
            }

            if ($service->isNotAvailableOnDate($time)) {
                SLN_Plugin::addLog(
                    __CLASS__.sprintf(' - service %s by date(%s) not available', $service, $time->format('Ymd H:i'))
                );

                return array(
                    __('This service is unavailable ', 'salon-booking-system').'<br/>'.
                    __('Availability: ', 'salon-booking-system').$service->getNotAvailableString(),
                );
            }

            if ($ret = $this->checkServiceAttendants($service, $time)) {
                return $ret;
            }
            $ids = $this->getDayBookings()->countServicesByHour($time->format('H'), $time->format('i'));
            if (
                $service->getUnitPerHour() > 0
                && isset($ids[$service->getId()])
                && $ids[$service->getId()] >= $service->getUnitPerHour()
            ) {
                SLN_Plugin::addLog(
                    __CLASS__.sprintf(' - service %s by date(%s) busy', $service, $time->format('Ymd H:i'))
                );

                return array(
                    sprintf(__('The service for %s is currently full', 'salon-booking-system'), $time->format('H:i')),
                );
            }
        }
    }

    private function checkServiceAttendants(SLN_Wrapper_Service $service, DateTime $time)
    {
        if (!$this->attendantsEnabled) {
            return;
        }
        $attendants = $service->getAttendants();
        foreach ($attendants as $k => $attendant) {
            if ($this->validateAttendant($attendant, $time)) {
                unset($attendants[$k]);
            }
        }

        if (empty($attendants)) {
            SLN_Plugin::addLog(
                __CLASS__.sprintf(
                    ' - all of the assistants for service %s by date(%s) are busy',
                    $service,
                    $time->format('Ymd H:i')
                )
            );

            return array(
                __('No assistants available for this service at ', 'salon-booking-system').$time->format('H:i'),
            );
        }
    }

    /**
     * @param array $servicesIds
     *
     * @return array of validated services
     */
    public function returnValidatedServices(array $servicesIds)
    {
        $date = $this->date;
        $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($servicesIds, 0), $date);
        $validated = array();
        foreach ($bookingServices->getItems() as $bookingService) {
            $serviceErrors = $this->validateService($bookingService->getService(), $bookingService->getStartsAt());
            if (empty($serviceErrors)) {
                $validated[] = $bookingService->getService()->getId();
            } else {
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
    public function checkEachOfNewServicesForExistOrder($order, $newServices)
    {
        $ret = array();
        $date = $this->date;

        $s = $this->settings;
        $bookingOffsetEnabled = $s->get('reservation_interval_enabled');
        $bookingOffset = $s->get('minutes_between_reservation');
        $isMultipleAttSelection = $s->get('m_attendant_enabled');
        $interval = min(SLN_Enum_Interval::toArray());

        foreach ($newServices as $service) {
            $services = $order;
            if (!in_array($service->getId(), $services)) {
                $services[] = $service->getId();
                $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($services, 0), $date);
                $availAtts = null;
                foreach ($bookingServices->getItems() as $bookingService) {
                    $serviceErrors = array();
                    $errorMsg = '';
                    if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                        $offsetStart = $bookingService->getEndsAt();
                        $offsetEnd = clone $offsetStart;
                        $offsetEnd->modify('+'.$bookingOffset.' minutes');
                        $serviceErrors = $this->validateTimePeriod($interval, $offsetStart, $offsetEnd);
                    }
                    if (empty($serviceErrors)) {
                        $serviceErrors = $this->validateService(
                            $bookingService->getService(),
                            $bookingService->getStartsAt()
                        );
                    }
                    if (empty($serviceErrors) && $this->attendantsEnabled &&  !$isMultipleAttSelection) {
                        $availAtts = $this->getAvailableAttendantForService($availAtts, $bookingService);
                        if (empty($availAtts)) {
                            $errorMsg = __(
                                'An assistant for selected services can\'t perform this service',
                                'salon-booking-system'
                            );
                            $serviceErrors = array($errorMsg);
                        }
                    }

                    if (!empty($serviceErrors)) {
                        $ret[$service->getId()] = $this->processServiceErrors(
                            $bookingServices,
                            $bookingService,
                            $service,
                            $serviceErrors
                        );
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

    private function processServiceErrors(
        SLN_Wrapper_Booking_Services $bookingServices,
        SLN_Wrapper_Booking_Service $bookingService,
        SLN_Wrapper_Service $service,
        $serviceErrors
    ) {
        if ($bookingService->getService()->getId() == $service->getId()) {
            $error = $serviceErrors[0];
        } else {
            $tmp = $bookingServices->findByService($service->getId());
            $error = !empty($errorMsg) ? $errorMsg : __(
                    'You already selected service at',
                    'salon-booking-system'
                ).($tmp ? ' '.$tmp->getStartsAt()->format('H:i') : '');
        }

        return array($error);
    }

    private function getAvailableAttendantForService($availAtts = null, SLN_Wrapper_Booking_Service $bookingService)
    {
        if (is_null($availAtts)) {
            $availAtts = $this->getAvailableAttsIdsForServiceOnTime(
                $bookingService->getService(),
                $bookingService->getEndsAt(),
                $bookingService->getDuration()
            );
        }
        $availAtts = array_intersect(
            $availAtts,
            $this->getAvailableAttsIdsForServiceOnTime(
                $bookingService->getService(),
                $bookingService->getEndsAt(),
                $bookingService->getDuration()
            )
        );

        return $availAtts;
    }

    public function getAvailableAttsIdsForServiceOnTime(
        SLN_Wrapper_Service $service,
        DateTime $date = null,
        DateTime $duration = null
    ) {
        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? $service->getDuration() : $duration;
        $ret = array();

        $startAt = clone $date;
        $endAt = clone $date;
        $endAt->modify('+'.SLN_Func::getMinutesFromDuration($duration).'minutes');

        $attendants = $service->getAttendants();
        $interval = min(SLN_Enum_Interval::toArray());
        $times = SLN_Func::filterTimes(SLN_Func::getMinutesIntervals($interval), $startAt, $endAt);
        foreach ($times as $time) {
            foreach ($attendants as $k => $attendant) {
                if ($this->validateAttendant($attendant, $time)) {
                    unset($attendants[$k]);
                }
            }
        }
        foreach ($attendants as $attendant) {
            $ret[] = $attendant->getId();
        }

        return $ret;
    }

    public function validateTimePeriod($interval, $start, $end)
    {
        $times = SLN_Func::filterTimes(SLN_Func::getMinutesIntervals($interval), $start, $end);
        foreach ($times as $time) {
            $time = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));
            if (!$this->isValidOnlyTime($time)) {
                return array(__('Limit of parallels bookings at ', 'salon-booking-system').$time->format('H:i'));
            }
        }

        return array();
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

        return $this->isValidOnlyTime($date);
    }

    public function isValidOnlyTime($date)
    {
        $countHour = $this->settings->get('parallels_hour');

        return ($date >= $this->initialDate) && !($countHour && $this->getBookingsHourCount(
                $date->format('H'),
                $date->format('i')
            ) >= $countHour);
    }

    public function getFreeMinutes($date)
    {
        $date = clone $date;
        $ret = 0;
        $interval = $this->settings->getInterval();
        $max = 24 * 60;

        $avItems = $this->getItems();
        while ($avItems->isValidDatetime($date) && $this->isValidTime($date) && $ret <= $max) {
            $ret += $interval;
            $date->modify(sprintf('+%s minutes', $interval));
        }

        return $ret;
    }
}
