<?php

use Salon\Util\Date;
use Salon\Util\Time;

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
    private $items;
    private $itemsWithoutServiceOffset;
    private $holidayItemsWithWeekDayRules;
    private $holidayItems;
    private $offset;
    protected $initialDate;

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
        $from = Date::create($interval->getFromDate());
        $count = $interval->getCountDays();
        $ret = array();
        $avItems = $this->getItems();
        $hItems  = $this->getHolidaysItemsWithWeekDayRules($avItems->getWeekDayRules());
        while ($count > 0) {
            $count--;
            if ($avItems->isValidDate($from) && $hItems->isValidDate($from) && $this->isValidDate($from)) {
                $ret[] = $from->toString();
            }
            $from = $from->getNextDate();
        }

        return $ret;
    }

    public function getCachedTimes(Date $date, Time $duration = null)
    {
        $bc = SLN_Plugin::getInstance()->getBookingCache();
        if ($bc->hasFullDay($date)) {
            return array();
        }
        $ret = $this->getTimes($date);
        if($duration){
            $duration = Time::increment($duration, -1 * $this->getOffset());
            $ret = Time::filterTimesArrayByDuration($ret, $duration);
        }
        if(empty($ret)){
            $bc->processDate($date);
            $bc->save();
        }
        return $ret;
    }

    public function getTimes(Date $date)
    {
        $ret = array();
        $avItems = $this->getItems();
        $hItems = $this->getHolidaysItems();
        $hb = $this->getHoursBeforeHelper();
        $from = $hb->getFromDate();
        $to = $hb->getToDate();
        foreach (SLN_Func::getMinutesIntervals() as $time) {
        	$d = new SLN_DateTime($date->toString().' '.$time);
            if (
                $avItems->isValidDatetime($d)
                && $hItems->isValidDatetime($d)
                && $this->isValidDate($date)
                && $this->isValidTime($d)
                && $d >= $from && $d <= $to
            ) {
                $ret[$time] = $time;
            }
        }
        SLN_Plugin::addLog(__CLASS__.' getTimes '.print_r($ret, true));

        return $ret;
    }

    public function  setDate(DateTime $date, SLN_Wrapper_Booking $booking = null)
    {
        if (empty($this->date) || ($this->date->format('Ymd') != $date->format('Ymd'))) {
            $obj = SLN_Enum_AvailabilityModeProvider::getService(
                $this->settings->getAvailabilityMode(),
                $date,
                $booking
            );
            SLN_Plugin::addLog(__CLASS__.sprintf(' - started %s', get_class($obj)));
            $this->dayBookings = $obj;
        }
        $this->dayBookings->setTime($date->format('H'), $date->format('i'));
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

    public function getMinutesIntervals()
    {
        return $this->getDayBookings()->getMinutesIntervals();
    }

    public function validateAttendantService(SLN_Wrapper_AttendantInterface $attendant, SLN_Wrapper_ServiceInterface $service)
    {
        if (!$attendant->hasAllServices()) {
            if (!$attendant->hasService($service)) {
                return array(
                    __('This assistant is not available for the selected service', 'salon-booking-system'),
                );
            }
        }
    }

    public function validateAttendant(
        SLN_Wrapper_AttendantInterface $attendant,
        DateTime $date = null,
        DateTime $duration = null,
        DateTime $breakStartsAt = null,
        DateTime $breakEndsAt = null
    ) {
        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? new DateTime('1970-01-01 00:00:00') : $duration;

        $noBreak = $this->getDayBookings()->isIgnoreServiceBreaks() || $breakStartsAt == $breakEndsAt || !$breakStartsAt || !$breakEndsAt;

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

        $times = SLN_Func::filterTimes($this->getMinutesIntervals(), $startAt, $endAt);
        foreach ($times as $time) {
            $bTime = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));
            if ($noBreak || ($bTime < $breakStartsAt || $bTime >= $breakEndsAt)) {
                if ($ret = $this->validateAttendantOnTime($attendant, $time)) {
                    return $ret;
                }
            }
        }
    }

    private function validateAttendantOnTime(SLN_Wrapper_AttendantInterface $attendant, SLN_DateTime $time)
    {
        SLN_Plugin::addLog(__CLASS__.sprintf(' checking time %s', $time->format('Ymd H:i')));
        $time = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));
        if ($attendant->isNotAvailableOnDate($time)) {
            return SLN_Helper_Availability_ErrorHelper::doAttendantNotAvailable($attendant, $time);
        }

        $ids = $this->getDayBookings()->countAttendantsByHour($time->format('H'), $time->format('i'));
        if (
            isset($ids[$attendant->getId()])
            && $ids[$attendant->getId()] >= 0
        ) {
            return SLN_Helper_Availability_ErrorHelper::doAttendantBusy($attendant, $time);
        }
    }

    public function validateBookingService(SLN_Wrapper_Booking_Service $bookingService)
    {
        return $this->validateService(
            $bookingService->getService(),
            $bookingService->getStartsAt(),
            $bookingService->getTotalDuration(),
            $bookingService->getBreakStartsAt(),
            $bookingService->getBreakEndsAt()
        );
    }

    public function validateBookingAttendant(SLN_Wrapper_Booking_Service $bookingService){
        return $this->validateAttendant(
            $bookingService->getAttendant(),
            $bookingService->getStartsAt(),
            $bookingService->getTotalDuration(),
            $bookingService->getBreakStartsAt(),
            $bookingService->getBreakEndsAt()
        );
    }


    public function validateService(SLN_Wrapper_ServiceInterface $service, DateTime $date = null, DateTime $duration = null, DateTime $breakStartsAt = null, DateTime $breakEndsAt = null)
    {
        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? $service->getTotalDuration() : $duration;

        $noBreak = $this->getDayBookings()->isIgnoreServiceBreaks() || $breakStartsAt == $breakEndsAt || !$breakStartsAt || !$breakEndsAt;

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

        $times = SLN_Func::filterTimes($this->getMinutesIntervals(), $startAt, $endAt);
        if($times && $ret = $this->validateServiceOnTime($service, $times[0], true)){
            return $ret;
        }
        foreach ($times as $time) {
            $bTime = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));
            if ($noBreak || ($bTime < $breakStartsAt || $bTime >= $breakEndsAt)) {
                if ($ret = $this->validateServiceOnTime($service, $time, false)) {
                    return $ret;
                }
            }
        }
    }

    private function validateServiceOnTime(SLN_Wrapper_ServiceInterface $service, SLN_DateTime $time, $checkDuration = true)
    {
        SLN_Plugin::addLog(__CLASS__.sprintf(' checking time %s', $time->format('Ymd H:i')));
        $time = $this->getDayBookings()->getTime($time->format('H'), $time->format('i'));

        $avItems = $this->getItemsWithoutServiceOffset();
        $hItems  = $this->getHolidaysItems();
        if($checkDuration) {
            $duration = $service->getDuration();
            $check = (!$avItems->isValidDatetimeDuration($time, $duration) || !$hItems->isValidDatetimeDuration($time, $duration)); 
        } else {
            $check = (!$avItems->isValidDatetime($time) || !$hItems->isValidDatetime($time));
        }
        if ($check) {
            return SLN_Helper_Availability_ErrorHelper::doServiceNotEnoughTime($service, $time);
        }

        if (!$this->isValidOnlyTime($time)) {
            return SLN_Helper_Availability_ErrorHelper::doLimitParallelBookings($time);
        }
        if ($checkDuration && $service->isNotAvailableOnDate($time)) {
            return SLN_Helper_Availability_ErrorHelper::doServiceNotAvailableOnDate($service, $time);
        }
        if ($ret = $this->validateServiceAttendantsOnTime($service, $time)) {
            return $ret;
        }
        $ids = $this->getDayBookings()->countServicesByHour($time->format('H'), $time->format('i'));
        if (
            $service->getUnitPerHour() > 0
            && isset($ids[$service->getId()])
            && $ids[$service->getId()] >= $service->getUnitPerHour()
        ) {
            return SLN_Helper_Availability_ErrorHelper::doServiceFull($service, $time);
        }
    }

    private function validateServiceAttendantsOnTime(SLN_Wrapper_ServiceInterface $service, DateTime $time)
    {
        if (!$this->attendantsEnabled) {
            return;
        }
        if (!$service->isAttendantsEnabled()) {
            return;
        }
        $attendants = $service->getAttendants();
        foreach ($attendants as $k => $attendant) {
            if ($this->validateAttendant($attendant, $time)) {
                unset($attendants[$k]);
            }
        }

        if (empty($attendants)) {
            return SLN_Helper_Availability_ErrorHelper::doServiceAllAttendantsBusy($service, $time);
        }
    }

    public function validateServiceFromOrder(SLN_Wrapper_ServiceInterface $service, SLN_Wrapper_Booking_Services $bookingServices)
    {
        if($service->isSecondary()) {
            $serviceDisplayMode = $service->getMeta('secondary_display_mode');
            if ($serviceDisplayMode !== 'always') {
                foreach($bookingServices->getItems() as $bookingService) {
                    if ($bookingService->getService()->getId() !== $service->getId() && !$bookingService->getService()->isSecondary()) {
                        if ($serviceDisplayMode === 'service') {
                            if(in_array($bookingService->getService()->getId(), (array)$service->getMeta('secondary_parent_services'))) {
                                return array();
                            }
                        }
                        elseif ($serviceDisplayMode === 'category') {
                            $serviceCategories = wp_get_post_terms($service->getId(), 'sln_service_category', array( "fields" => "ids" ) );
                            $serviceCategory   = reset($serviceCategories);

                            $bServiceCategories = wp_get_post_terms($bookingService->getService()->getId(), 'sln_service_category', array( "fields" => "ids" ) );
                            if(in_array($serviceCategory, $bServiceCategories)) {
                                return array();
                            }
                        }
                    }
                }

                if ($serviceDisplayMode === 'service') {
                    return SLN_Helper_Availability_ErrorHelper::doSecondaryServiceNotAvailableWOParentService($service);
                }
                elseif ($serviceDisplayMode === 'category') {
                    return SLN_Helper_Availability_ErrorHelper::doSecondaryServiceNotAvailableWOSameCategoryPrimaryService($service);
                }
            }
        }

	    return array();
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
        $servicesCount  = $this->settings->get('services_count');

        foreach ($bookingServices->getItems() as $bookingService) {
            if($servicesCount && count($validated) >= $servicesCount) {
                break;
            }
            $serviceErrors = $this->validateService(
                $bookingService->getService(),
                $bookingService->getStartsAt(),
                null,
                $bookingService->getBreakStartsAt(),
                $bookingService->getBreakEndsAt()
            );
            if (empty($serviceErrors)) {
                $validated[] = $bookingService->getService()->getId();
            } else {
                break;
            }
        }

        $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($validated, 0), $date);
        $validated       = array();
        foreach ($bookingServices->getItems() as $bookingService) {
            $serviceErrors = $this->validateServiceFromOrder($bookingService->getService(), $bookingServices);
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
     * @param SLN_Wrapper_ServiceInterface[] $newServices
     *
     * @return array
     */
    public function checkEachOfNewServicesForExistOrder($order, $newServices, $altMode = false)
    {
        $ret = array();
        $date = $this->date;

        $s = $this->settings;
        $bookingOffsetEnabled = $s->get('reservation_interval_enabled');
        $bookingOffset = $s->get('minutes_between_reservation');
        $isMultipleAttSelection = $s->get('m_attendant_enabled');
        $interval = $this->settings->getInterval();
        $servicesCount = $this->settings->get('services_count');

        foreach ($newServices as $service) {
            $services = $order;
            if (!in_array($service->getId(), $services)) {
                if($servicesCount && count($services) >= $servicesCount) {
                    $ret[$service->getId()] = array(sprintf(__('You can select up to %d items', 'salon-booking-system'), $servicesCount));
                    continue;
                }
                $services[] = $service->getId();
                $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($services, 0), $date);
                $availAtts = null;
                foreach ($bookingServices->getItems() as $bookingService) {
                    $serviceErrors = array();
                    $errorMsg = '';

	                $serviceErrors = $this->validateServiceFromOrder($bookingService->getService(), $bookingServices);

	                if (!$altMode) {
		                if (empty($serviceErrors) && $bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
			                $offsetStart = $bookingService->getEndsAt();
			                $offsetEnd = clone $offsetStart;
			                $offsetEnd->modify('+'.$bookingOffset.' minutes');
			                $serviceErrors = $this->validateTimePeriod($offsetStart, $offsetEnd);
		                }

		                if (empty($serviceErrors)) {
			                $serviceErrors = $this->validateService(
					                $bookingService->getService(),
					                $bookingService->getStartsAt(),
					                null,
					                $bookingService->getBreakStartsAt(),
					                $bookingService->getBreakEndsAt()
			                );
		                }

		                if (empty($serviceErrors) && $this->attendantsEnabled && !$isMultipleAttSelection && $bookingService->getService()->isAttendantsEnabled()) {
			                $availAtts = $this->getAvailableAttendantForService($availAtts, $bookingService);
			                if (empty($availAtts)) {
				                $errorMsg = __(
						                'An assistant for selected services can\'t perform this service',
						                'salon-booking-system'
				                );
				                $serviceErrors = array($errorMsg);
			                }
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

    public function checkExclusiveServices($order, $services) {
        $ret = array();
        $exclusiveServiceId = false;
        foreach ($services as $service) {
            if (in_array($service->getId(), $order)) {
                if($service->isExclusive()) {
                    $exclusiveServiceId = $service->getId();
                    break;
                }
            }
        }
        foreach ($services as $service) {
            if($exclusiveServiceId && $exclusiveServiceId != $service->getId()) {
                $errorMsg = __(
                    'This service is not available with exclusive service.',
                    'salon-booking-system'
                );
                $ret[$service->getId()] = array($errorMsg);
            }
        }
        return $ret;
    }

    private function processServiceErrors(
        SLN_Wrapper_Booking_Services $bookingServices,
        SLN_Wrapper_Booking_Service $bookingService,
        SLN_Wrapper_ServiceInterface $service,
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

    public function getAvailableAttendantForService($availAtts = null, SLN_Wrapper_Booking_Service $bookingService)
    {
        $intersect = $this->getAvailableAttsIdsForBookingService($bookingService);
        if (is_null($availAtts)) {
            $availAtts = $intersect;
        }
        $availAtts = array_intersect($availAtts, $intersect);

        return $availAtts;
    }

    public function getAvailableAttsIdsForBookingService(SLN_Wrapper_Booking_Service $bs)
    {
        return $this->getAvailableAttsIdsForServiceOnTime(
            $bs->getService(),
            $bs->getStartsAt(),
            $bs->getTotalDuration(),
            $bs->getBreakStartsAt(),
            $bs->getBreakEndsAt()
        );
    }

    public function getAvailableAttsIdsForServiceOnTime(
        SLN_Wrapper_ServiceInterface $service,
        DateTime $date = null,
        DateTime $duration = null,
        DateTime $breakStartsAt = null,
        DateTime $breakEndsAt = null
    ) {
        $date = empty($date) ? $this->date : $date;
        $duration = empty($duration) ? $service->getTotalDuration() : $duration;
        $ret = array();

        $startAt = clone $date;
        $endAt = clone $date;
        $endAt->modify('+'.SLN_Func::getMinutesFromDuration($duration).'minutes');

        $attendants = $service->getAttendants();
        $times = SLN_Func::filterTimes($this->getMinutesIntervals(), $startAt, $endAt);
        foreach ($times as $time) {
            foreach ($attendants as $k => $attendant) {
                if ($this->validateAttendant($attendant, $time, null, $breakStartsAt, $breakEndsAt)) {
                    unset($attendants[$k]);
                }
            }
        }
        foreach ($attendants as $attendant) {
            $ret[] = $attendant->getId();
        }

        return $ret;
    }

    public function addAttendantForServices(SLN_Wrapper_Booking_Services $bookingServices)
    {
        if ($this->settings->isMultipleAttendantsEnabled()) {
            $this->addMultipleAttendantForServices($bookingServices);
        } else {
            $this->addSingleAttendantForServices($bookingServices);
        }
    }

    private function addMultipleAttendantForServices(SLN_Wrapper_Booking_Services $bookingServices)
    {
        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();
            if (!$service->isAttendantsEnabled()) {
                continue;
            }

            $availAttsForEachService[$service->getId()]
                = $availAttsForCurrentService = $this->getAvailableAttsIdsForBookingService($bookingService);
            if (empty($availAttsForCurrentService)) {
                throw new SLN_Exception(
                    sprintf(
                        __('No one of the attendants isn\'t available for %s service', 'salon-booking-system'),
                        $service->getName()
                    )
                );
            }

            if (is_object($bookingService->getAttendant())) {
                $selectedAttId = $bookingService->getAttendant()->getId();
                if (!in_array($selectedAttId, $availAttsForCurrentService)) {
                    throw new SLN_Exception(
                        sprintf(
                            __('Attendant %s isn\'t available for %s service', 'salon-booking-system'),
                            $bookingService->getAttendant()->getName(),
                            $service->getName()
                        )
                    );
                }
            } else {
                SLN_Plugin::getInstance();
                $selectedAttId = $availAttsForCurrentService[array_rand($availAttsForCurrentService)];
                $attendant     = SLN_Plugin::getInstance()->createAttendant($selectedAttId);
                $bookingService->setAttendant($attendant);
            }
        }
    }

    private function addSingleAttendantForServices(SLN_Wrapper_Booking_Services $bookingServices)
    {
        $availAttsForAllServices = null;
        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();
            if (!$service->isAttendantsEnabled()) {
                continue;
            }

            $availAttsForAllServices = $this->getAvailableAttendantForService(
                $availAttsForAllServices,
                $bookingService
            );
            if (is_object($bookingService->getAttendant())) {
                $availAttsForAllServices = array_intersect(
                    $availAttsForAllServices,
                    array($bookingService->getAttendant()->getId())
                );
            }

            if (empty($availAttsForAllServices)) {
                throw new SLN_Exception(
                    __('No one of the attendants isn\'t available for selected services', 'salon-booking-system')
                );
            }
        }

        $selectedAttId = $availAttsForAllServices[array_rand($availAttsForAllServices)];
        $attendant     = SLN_Plugin::getInstance()->createAttendant($selectedAttId);
        foreach ($bookingServices->getItems() as $bookingService) {
            $service = $bookingService->getService();
            if ($service->isAttendantsEnabled()) {
                $bookingService->setAttendant($attendant);
            }
        }
    }

    public function validateTimePeriod($start, $end)
    {
        $times = SLN_Func::filterTimes($this->getMinutesIntervals(), $start, $end);
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
            $this->items = $this->settings->getNewAvailabilityItems();
            $this->items->setOffset($this->getOffset());
        }

        return $this->items;
    }

    private function getOffset()
    {
        if(!$this->offset){
            $duration = SLN_Plugin::getInstance()->getRepository(
                SLN_Plugin::POST_TYPE_SERVICE
            )->getMinPrimaryServiceDuration();
            $this->offset = SLN_Func::getMinutesFromDuration($duration);
        }
        return $this->offset;
    }

    public function resetItems()
    {
        $this->hoursBefore                  = null;
        $this->items                        = null;
        $this->itemsWithoutServiceOffset    = null;
        $this->holidayItems                 = null;
        $this->holidayItemsWithWeekDayRules = null;
    }

    /**
     * @return SLN_Helper_AvailabilityItems
     */
    public function getItemsWithoutServiceOffset()
    {
        if (!isset($this->itemsWithoutServiceOffset)) {
            $this->itemsWithoutServiceOffset = $this->settings->getAvailabilityItems();
        }

        return $this->itemsWithoutServiceOffset;
    }

    /**
     * @return SLN_Helper_HolidayItems
     */
    public function getHolidaysItems()
    {
        if(!isset($this->holidayItems)){
            $this->holidayItems = $this->settings->getHolidayItems();
        }
        return $this->holidayItems;
    }

    /**
     * @return SLN_Helper_HolidayItems
     */
    public function getHolidaysItemsWithWeekDayRules($weekDayRules)
    {
        if (!isset($this->holidayItemsWithWeekDayRules)) {
            $this->holidayItemsWithWeekDayRules = $this->settings->getNewHolidayItems();
            $this->holidayItemsWithWeekDayRules->setWeekDayRules($weekDayRules);
        }

        return $this->holidayItemsWithWeekDayRules;
    }

    public function isValidDate(Date $date)
    {
        $this->setDate($date->getDateTime());
        $countDay = $this->settings->get('parallels_day');

        return !($countDay && $this->getBookingsDayCount() >= $countDay);
    }

    public function isValidTime($date)
    {
        if (!$this->isValidDate(Date::create($date))) {
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
