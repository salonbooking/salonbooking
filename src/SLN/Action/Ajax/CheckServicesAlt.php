<?php

class SLN_Action_Ajax_CheckServicesAlt extends SLN_Action_Ajax_CheckServices
{
    protected function innerInitServices($services, $merge, $newServices)
    {
        $ret      = array();
        $builder  = $this->bb;
        $this->ah->setDate($this->bb->getDateTime());

        $mergeIds = array();
        foreach($merge as $s){
            $mergeIds[] = $s->getId();
        }
        $services      = array_merge(array_keys($services), $mergeIds);
        $servicesCount = $this->plugin->getSettings()->get('services_count');
        if ($servicesCount) {
            $services = array_slice($services, 0, $servicesCount);
        }
        $builder->removeServices();

        foreach ($this->getServices(true, true) as $service) {
            $error = '';
            if (in_array($service->getId(), $services)) {
                $bookingServices = SLN_Wrapper_Booking_Services::build(array_fill_keys($services, 0), $this->getDateTime());
                $serviceErrors   = $this->ah->validateServiceFromOrder($service, $bookingServices);
                if(empty($serviceErrors)) {
                    $builder->addService($service);
                    $status = self::STATUS_CHECKED;
                }
                else {
                    unset($services[array_search($service->getId(), $services)]);
                    $status = self::STATUS_ERROR;
                    $error  = reset($serviceErrors);
                }
            } else {
                $status = self::STATUS_UNCHECKED;
            }
            $ret[$service->getId()] = array('status' => $status, 'error' => $error);
        }
        $builder->save();

        $servicesErrors = $this->ah->checkEachOfNewServicesForExistOrder($services, $newServices, true);
        foreach ($servicesErrors as $sId => $error) {
            if (empty($error)) {
                $ret[$sId] = array('status' => self::STATUS_UNCHECKED, 'error' => '');
            } else {
                $ret[$sId] = array('status' => self::STATUS_ERROR, 'error' => $error[0]);
            }
        }

	    $servicesExclusiveErrors = $this->ah->checkExclusiveServices( $services, array_merge( $merge, $newServices ) );
	    foreach ($servicesExclusiveErrors as $sId => $error) {
		    if (empty($error)) {
			    $ret[$sId] = array('status' => self::STATUS_UNCHECKED, 'error' => '');
		    } else {
			    $ret[$sId] = array('status' => self::STATUS_ERROR, 'error' => $error[0]);
		    }
	    }

        return $ret;
    }
}
