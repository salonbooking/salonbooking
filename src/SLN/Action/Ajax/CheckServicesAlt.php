<?php

class SLN_Action_Ajax_CheckServicesAlt extends SLN_Action_Ajax_CheckServices
{
    protected function innerInitServices($services, $merged, $newServices)
    {
        $builder       = $this->bb;
        $mergeIds = array();
        foreach($merged as $s){
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
            if ($servicesCount && (count($services) >= $servicesCount && ! in_array($service->getId(), $services))) {
                $status = self::STATUS_ERROR;
                $error  = sprintf(__('You can select up to %d items', 'salon-booking-system'), $servicesCount);
            } elseif (in_array($service->getId(), $services)) {
                $builder->addService($service);
                $status = self::STATUS_CHECKED;
            } else {
                $status = self::STATUS_UNCHECKED;
            }
            $ret[$service->getId()] = array('status' => $status, 'error' => $error);
        }
        $builder->save();

        return $ret;
    }
}
