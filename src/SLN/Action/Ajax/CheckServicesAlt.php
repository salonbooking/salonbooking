<?php
// TODO: REFACTORING
class SLN_Action_Ajax_CheckServicesAlt extends SLN_Action_Ajax_CheckServices
{
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

            $services       = isset($_POST['sln']['services']) ? $_POST['sln']['services'] : array();
            $bb             = $this->plugin->getBookingBuilder();

            $bbSecServices  = $bb->getSecondaryServicesIds();
            $services       = array_merge(array_keys($services), $bbSecServices);
            $servicesCount  = $this->plugin->getSettings()->get('services_count');
            if($servicesCount) {
                $services   = array_slice($services, 0, $servicesCount);
            }

            $bb->removeServices();

            foreach($this->getServices(true, true) as $service) {
                $error = '';
                if ($servicesCount && (count($services) >= $servicesCount && !in_array($service->getId(), $services))) {
                    $status = -1;
                    $error  = sprintf(__('You can select up to %d items', 'salon-booking-system'), $servicesCount);
                }
                elseif (in_array($service->getId(), $services)) {
                    $bb->addService($service);
                    $status = 1;
                }
                else {
                    $status = 0;
                }
                $ret[$service->getId()] = array('status' => $status, 'error' => $error);
            }

            $bb->save();
        } elseif (isset($_POST['part']) && $_POST['part'] == 'secondaryServices') { // for frontend user

            $services       = isset($_POST['sln']['services']) ? $_POST['sln']['services'] : array();
            $bb             = $this->plugin->getBookingBuilder();

            $bbPrimServices = $bb->getPrimaryServicesIds();
            $services       = array_merge($bbPrimServices, array_keys($services));
            $servicesCount  = $this->plugin->getSettings()->get('services_count');
            if($servicesCount) {
                $services   = array_slice($services, 0, $servicesCount);
            }

            $bb->removeServices();

            foreach($this->getServices(true, true) as $service) {
                $error = '';
                if ($servicesCount && (count($services) >= $servicesCount && !in_array($service->getId(), $services))) {
                    $status = -1;
                    $error  = sprintf(__('You can select up to %d items', 'salon-booking-system'), $servicesCount);
                }
                elseif (in_array($service->getId(), $services)) {
                    $bb->addService($service);
                    $status = 1;
                }
                else {
                    $status = 0;
                }
                $ret[$service->getId()] = array('status' => $status, 'error' => $error);
            }

            $bb->save();
        } else {
            $tmp = parent::execute();
            $ret = $tmp['services'];
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
        $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
        
        foreach ($repo->sortByExec($repo->getAll()) as $service) {
            if ($secondary && $service->isSecondary()) {
                $services[] = $service;
            } elseif ($primary && !$service->isSecondary()) {
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
