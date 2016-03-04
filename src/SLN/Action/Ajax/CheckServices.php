<?php
// TODO: REFACTORING
class SLN_Action_Ajax_CheckServices extends SLN_Action_Ajax_Abstract
{
    private $date;
    private $time;
    private $errors = array();

    public function execute()
    {
        if($timezone = get_option('timezone_string'))
            date_default_timezone_set($timezone);

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
        }
        elseif (isset($_POST['part']) && $_POST['part'] == 'secondaryServices') { // for frontend user
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

            $servicesErrors = $ah->checkEachOfNewServicesForExistOrder($validated, $this->getServices());
            foreach($servicesErrors as $sId => $error) {
                if (empty($error)) {
                    $ret[$sId] = array('status' => 0, 'error' => '');
                }
                else {
                    $ret[$sId] = array('status' => -1, 'error' => $error[0]);
                }
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
        foreach (SLN_Plugin::getInstance()->getServicesOrderByExec() as $service) {
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

//    protected function getServices()
//    {
//
//        $plugin = $this->plugin;
//        $date   = $this->getDateTime();
//        $ah   = $plugin->getAvailabilityHelper();
//        $ah->setDate($date);
//        $ret = array();
//        foreach($plugin->getServices() as $s){
//            $ret[$s->getId()] = $ah->validateService($s);
//        }
//        return $ret;
//    }

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
