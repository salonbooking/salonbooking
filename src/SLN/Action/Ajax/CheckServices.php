<?php

class SLN_Action_Ajax_CheckServices extends SLN_Action_Ajax_Abstract
{
    const STATUS_ERROR = -1;
    const STATUS_UNCHECKED = 0;
    const STATUS_CHECKED = 1;

    /** @var  SLN_Wrapper_Booking_Builder */
    protected $bb;
    /** @var  SLN_Helper_Availability */
    protected $ah;

    protected $date;
    protected $time;
    protected $errors = array();

    public function execute()
    {
        $this->bb = $this->plugin->getBookingBuilder();
        $this->ah = $this->plugin->getAvailabilityHelper();
        $this->bindDate($_POST);

        $ret = array();
        
        $services = isset($_POST['sln']['services']) ? $_POST['sln']['services'] : array();
        if (isset($_POST['part'])) {
            $part = $_POST['part'];
            if ($part == 'primaryServices') { // for frontend user
                $ret = $this->initPrimaryServices($services);
            } elseif ($part == 'secondaryServices') { // for frontend user
                $ret = $this->initSecondaryServices($services);
            } elseif ($part == 'allServices' && ! empty($_POST['_sln_booking']['service'])) { // for admin
                $services = $_POST['_sln_booking']['service'];
                $ret = $this->initAllServicesForAdmin($services,$_POST['_sln_booking']);
            }
        }

        $ret = array(
            'success'  => 1,
            'services' => $ret,
        );

        return $ret;
    }

    protected function initPrimaryServices($services)
    {
        return $this->innerInitServices($services, $this->bb->getSecondaryServices(), $this->getPrimaryServices());
    }


    protected function initSecondaryServices($services)
    {
        return $this->innerInitServices($services, $this->bb->getPrimaryServices(), $this->getSecondaryServices());
    }


    private function bindDate($data)
    {
        if ( ! isset($this->date)) {
            if (isset($data['sln'])) {
                $this->date = $data['sln']['date'];
                $this->time = $data['sln']['time'];
            }
            if (isset($data['_sln_booking_date'])) {
                $this->date = $data['_sln_booking_date'];
                $this->time = $data['_sln_booking_time'];
            }
        }
    }

    protected function innerInitServices($services, $merge, $newServices)
    {
        
        $ret      = array();
        $mergeIds = array();
        foreach($merge as $s){
            $mergeIds[] = $s->getId();
        }
        
        $services = array_merge(
            array_keys($services),
            $mergeIds
        ); // merge primary services from form & secondary services from booking builder
        $this->ah->setDate($this->bb->getDateTime());
        $validated = $this->ah->returnValidatedServices($services);
        $validatedPrimary = array_intersect($this->getPrimaryServicesIds(), $validated);

        $this->bb->removeServices();

        if ( ! empty($validatedPrimary)) { // if order primary services count > 0  --->  set validated services
            foreach ($validated as $sId) {
                $this->bb->addService($this->plugin->createService($sId));
                $ret[$sId] = array('status' => self::STATUS_CHECKED, 'error' => '');
            }
        } else {
            $validated = array();
        }
        $this->bb->save();

        $servicesErrors = $this->ah->checkEachOfNewServicesForExistOrder($validated, $newServices);
        foreach ($servicesErrors as $sId => $error) {
            if (empty($error)) {
                $ret[$sId] = array('status' => self::STATUS_UNCHECKED, 'error' => '');
            } else {
                $ret[$sId] = array('status' => self::STATUS_ERROR, 'error' => $error[0]);
            }
        }

        $servicesExclusiveErrors = $this->ah->checkExclusiveServices( $validated, array_merge( $merge, $newServices ) );
        foreach ($servicesExclusiveErrors as $sId => $error) {
            if (empty($error)) {
                $ret[$sId] = array('status' => self::STATUS_UNCHECKED, 'error' => '');
            } else {
                $ret[$sId] = array('status' => self::STATUS_ERROR, 'error' => $error[0]);
            }
        }
        return $ret;
    }

    protected function initAllServicesForAdmin($services,$bookingData)
    {
        $date = $this->getDateTime();
        $this->ah->setDate($date, $this->plugin->createBooking($_POST['post_ID']));

        $data = array();
        foreach ($services as $sId) {
            $data[$sId] = array(
                'service'        => $sId,
                'attendant'      => $bookingData['attendants'][$sId],
                'price'          => $bookingData['price'][$sId],
                'duration'       => SLN_Func::convertToHoursMins($bookingData['duration'][$sId]),
                'break_duration' => SLN_Func::convertToHoursMins($bookingData['break_duration'][$sId]),
            );
        }
        $ret             = array();
        $bookingServices = SLN_Wrapper_Booking_Services::build($data, $date);
        $settings = $this->plugin->getSettings();
        $servicesCount          = $settings->get('services_count');
        $bookingOffsetEnabled   = $settings->get('reservation_interval_enabled');
        $bookingOffset          = $settings->get('minutes_between_reservation');
        $isMultipleAttSelection = $settings->get('m_attendant_enabled');

        $firstSelectedAttendant = null;
        foreach ($bookingServices->getItems() as $bookingService) {
            $serviceErrors   = array();
            $attendantErrors = array();

            if ($servicesCount && $bookingServices->getPosInQueue($bookingService) > $servicesCount) {
                $serviceErrors[] = sprintf(__('You can select up to %d items', 'salon-booking-system'), $servicesCount);
            } else {
                $serviceErrors = $this->ah->validateServiceFromOrder($bookingService->getService(), $bookingServices);

                if (empty($serviceErrors) && $bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                    $offsetStart   = $bookingService->getEndsAt();
                    $offsetEnd     = $bookingService->getEndsAt()->modify('+'.$bookingOffset.' minutes');
                    $serviceErrors = $this->ah->validateTimePeriod($offsetStart, $offsetEnd);
                }

                if (empty($serviceErrors)) {
                    $serviceErrors = $this->ah->validateBookingService($bookingService);
                }

                if ( ! $isMultipleAttSelection) {
                    if ( ! $firstSelectedAttendant) {
                        $firstSelectedAttendant = ($bookingService->getAttendant() ? $bookingService->getAttendant(
                        )->getId() : false);
                    }
                    if ($bookingService->getAttendant() && $bookingService->getAttendant()->getId(
                        ) != $firstSelectedAttendant
                    ) {
                        $attendantErrors = array(
                            __(
                                'Multiple attendants selection is disabled. You must select one attendant for all services.',
                                'salon-booking-system'
                            ),
                        );
                    }
                }
                if (empty($attendantErrors) && $bookingService->getAttendant()) {
                    $attendantErrors = $this->ah->validateAttendantService(
                        $bookingService->getAttendant(),
                        $bookingService->getService()
                    );
                    if (empty($attendantErrors)) {
                        $attendantErrors = $this->ah->validateBookingAttendant($bookingService);
                    }
                }
            }

            if($bookingService->getService()->isExclusive() && count($bookingServices->getItems()) > 1) {
                $serviceErrors[] = __('This service is exclusive. Please remove other services.', 'salon-booking-system');
            }

            $errors = array();
            if ( ! empty($attendantErrors)) {
                $errors[] = $attendantErrors[0];
            }
            if ( ! empty($serviceErrors)) {
                $errors[] = $serviceErrors[0];
            }

            $ret[$bookingService->getService()->getId()] = array(
                'status'   => empty($errors) ? self::STATUS_CHECKED : self::STATUS_ERROR,
                'errors'   => $errors,
                'startsAt' => $this->plugin->format()->time($bookingService->getStartsAt()),
                'endsAt'   => $this->plugin->format()->time($bookingService->getEndsAt()),
            );
        }
        return $ret;
    }

    /**
     * @param bool $primary
     * @param bool $secondary
     *
     * @return SLN_Wrapper_Service[]
     */
    protected function getServices($primary = true, $secondary = false)
    {
        $services = array();
        /** @var SLN_Repository_ServiceRepository $repo */
        $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);

        foreach ($repo->sortByExec($repo->getAll()) as $service) {
            if ($secondary && $service->isSecondary()) {
                $services[] = $service;
            } elseif ($primary && ! $service->isSecondary()) {
                $services[] = $service;
            }
        }

        return $services;
    }

    protected function getPrimaryServicesIds()
    {
        $ret = array();
        foreach ($this->getServices(true, false) as $service) {
            if ( ! $service->isSecondary()) {
                $ret[] = $service->getId();
            }
        }

        return $ret;
    }

    protected function getPrimaryServices()
    {
        return $this->getServices(true, false);
    }

    protected function getSecondaryServices()
    {
        return $this->getServices(false, true);
    }

    protected function getDateTime()
    {
        $date = $this->date;
        $time = $this->time;
        $ret  = new SLN_DateTime(
            SLN_Func::filter($date, 'date').' '.SLN_Func::filter($time, 'time'.':00')
        );

        return $ret;
    }
}
