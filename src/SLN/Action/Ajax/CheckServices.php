<?php
// TODO: REFACTORING
class SLN_Action_Ajax_CheckServices extends SLN_Action_Ajax_Abstract
{
    private $date;
    private $time;
    private $errors = array();

    public function execute()
    {
        if (!isset($this->date)) {
            if(isset($_POST['sln'])){
                $this->date = $_POST['sln']['date'];
                $this->time = $_POST['sln']['time'];
            }
            if(isset($_POST['_sln_booking_date'])) {
                $this->date = $_POST['_sln_booking_date'];
                $this->time = $_POST['_sln_booking_time'];
            }
        }
        $ret = array();

        if (isset($_POST['part']) && $_POST['part'] == 'primaryServices') { // for frontend user
            $services = isset($_POST['sln']['services']) ? $_POST['sln']['services'] : array();
            $bb = SLN_Plugin::getInstance()->getBookingBuilder();
            $bbSecServices = $bb->getSecondaryServicesIds();
            $services = array_merge(array_keys($services), $bbSecServices); // merge primary services from form & secondary services from booking builder

            $date = $bb->getDateTime();

            $ah = SLN_Plugin::getInstance()->getAvailabilityHelper();
            $ah->setDate($date);
            $validated = $ah->returnValidatedServices($services); // return ids for validated services
            $validatedPrimary = array_intersect($this->getPrimaryServicesIds(), $validated);

            $bb->clearServices();
            if (!empty($validatedPrimary)) { // if order primary services count > 0  --->  set validated services
                foreach($validated as $sId) {
                    $bb->addService(new SLN_Wrapper_Service($sId));
                    $ret[$sId] = array('status' => 1, 'error' => '');
                }
            }
            else {
                $validated = array();
            }
            $bb->save();

            $servicesErrors = $ah->checkEachOfNewServicesForExistOrder($validated, $this->getPrimaryServices());
            foreach($servicesErrors as $sId => $error) {
                if (empty($error)) {
                    $ret[$sId] = array('status' => 0, 'error' => '');
                }
                else {
                    $ret[$sId] = array('status' => -1, 'error' => $error[0]);
                }
            }
        } elseif (isset($_POST['part']) && $_POST['part'] == 'secondaryServices') { // for frontend user
            $services = isset($_POST['sln']['services']) ? $_POST['sln']['services'] : array();
            $bb = SLN_Plugin::getInstance()->getBookingBuilder();
            $bbPrimServices = $bb->getPrimaryServicesIds();
            $bbSecServices = $bb->getSecondaryServicesIds();
            $services = array_merge(array_keys($services), $bbPrimServices);

            $date = $bb->getDateTime();

            $ah = SLN_Plugin::getInstance()->getAvailabilityHelper();
            $ah->setDate($date);
            $validated = $ah->returnValidatedServices($services);
            $validatedPrimary = array_intersect($this->getPrimaryServicesIds(), $validated);
            $bb->clearServices();
            if (!empty($validatedPrimary)) { // if order primary services count > 0  --->  set validated services
                foreach($validated as $sId) {
                    $bb->addService(new SLN_Wrapper_Service($sId));
                    $ret[$sId] = array('status' => 1, 'error' => '');
                }
            }
            else {
                $validated = array();
            }
            $bb->save();

            $servicesErrors = $ah->checkEachOfNewServicesForExistOrder($validated, $this->getSecondaryServices());
            foreach($servicesErrors as $sId => $error) {
                if (empty($error)) {
                    $ret[$sId] = array('status' => 0, 'error' => '');
                }
                else {
                    $ret[$sId] = array('status' => -1, 'error' => $error[0]);
                }
            }
        } elseif (isset($_POST['part']) && $_POST['part'] == 'allServices' && !empty($_POST['_sln_booking']['service'])) { // for admin
            $services = $_POST['_sln_booking']['service'];

            $date = new SLN_DateTime($this->date.' '.$this->time);
            $ah = SLN_Plugin::getInstance()->getAvailabilityHelper();
            $ah->setDate($date, SLN_Plugin::getInstance()->createBooking($_POST['post_ID']));

            $data = array();
            foreach($services as $sId) {
                $data[$sId] = array(
                    'attendant' => $_POST['_sln_booking']['attendants'][$sId],
                    'price'     => $_POST['_sln_booking']['price'][$sId],
                    'duration'  => SLN_Func::convertToHoursMins($_POST['_sln_booking']['duration'][$sId]),
                );
            }

            $ret = array();
            $bookingServices = SLN_Wrapper_Booking_Services::build($data, $date);

            $bookingOffsetEnabled   = SLN_Plugin::getInstance()->getSettings()->get('reservation_interval_enabled');
            $bookingOffset          = SLN_Plugin::getInstance()->getSettings()->get('minutes_between_reservation');
            $isMultipleAttSelection = SLN_Plugin::getInstance()->getSettings()->get('m_attendant_enabled');
            $interval               = min(SLN_Enum_Interval::toArray());

            $firstSelectedAttendant = null;

            foreach($bookingServices->getItems() as $bookingService) {
                $serviceErrors   = array();
                $attendantErrors = array();

                if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                    $offsetStart   = $bookingService->getEndsAt();
                    $offsetEnd     = $bookingService->getEndsAt()->modify('+'.$bookingOffset.' minutes');
                    $serviceErrors = $ah->validateTimePeriod($interval, $offsetStart, $offsetEnd);
                }
                if (empty($serviceErrors)) {
                    $serviceErrors = $ah->validateService($bookingService->getService(), $bookingService->getStartsAt(), $bookingService->getDuration());
                }

                if (!$isMultipleAttSelection) {
                    if (!$firstSelectedAttendant) {
                        $firstSelectedAttendant = $bookingService->getAttendant()->getId();
                    }
                    if ($bookingService->getAttendant()->getId() != $firstSelectedAttendant) {
                        $attendantErrors = array(__('Multiple attendants selection is disabled. You must select one attendant for all services.', 'salon-booking-system'));
                    }
                }
                if (empty($attendantErrors)) {
                    $attendantErrors = $ah->validateAttendantService($bookingService->getAttendant(), $bookingService->getService());
                    if (empty($attendantErrors)) {
                        $attendantErrors = $ah->validateAttendant($bookingService->getAttendant(), $bookingService->getStartsAt(), $bookingService->getDuration());
                    }
                }

                $errors = array();
                if (!empty($attendantErrors)) {
                    $errors[] = $attendantErrors[0];
                }
                if (!empty($serviceErrors)) {
                    $errors[] = $serviceErrors[0];
                }

                $ret[$bookingService->getService()->getId()] = array(
                    'status' => empty($errors) ? 1 : -1,
                    'errors' => $errors,
                    'startsAt' => SLN_Plugin::getInstance()->format()->time($bookingService->getStartsAt()),
                    'endsAt' => SLN_Plugin::getInstance()->format()->time($bookingService->getEndsAt()),
                );
            }
        }

        $ret = array(
            'success' => 1,
            'services' => $ret
        );
        
        return $ret;
    }

    /**
     * @param bool $primary
     * @param bool $secondary
     *
     * @return SLN_Wrapper_Service[]
     */
    private function getServices($primary = true, $secondary = false)
    {
        $services = array();
        /** @var SLN_Repository_ServiceRepository $repo */
        $repo = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
        
        foreach ($repo->sortByExec($repo->getAll()) as $service) {
            if ($secondary && $service->isSecondary()) {
                $services[] = $service;
            }
            elseif ($primary && !$service->isSecondary()) {
                $services[] = $service;
            }
        }

        return $services;
    }

    protected function getPrimaryServicesIds() {
        $ret = array();
        foreach($this->getServices(true, false) as $service) {
            if (!$service->isSecondary()) {
                $ret[] = $service->getId();
            }
        }
        return $ret;
    }

    protected function getPrimaryServices() {
        return $this->getServices(true, false);
    }

    protected function getSecondaryServices() {
        return $this->getServices(false, true);
    }

    protected function getDateTime()
    {
        $date = $this->date;
        $time = $this->time;
        $ret = new SLN_DateTime(
            SLN_Func::filter($date, 'date') . ' ' . SLN_Func::filter($time, 'time'.':00')
        );
        return $ret;
    }
}
