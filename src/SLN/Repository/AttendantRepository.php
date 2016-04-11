<?php

class SLN_Repository_AttendantRepository extends SLN_Repository_AbstractWrapperRepository
{
    private $attendants;

    public function getWrapperClass()
    {
        return SLN_Wrapper_Attendant::_CLASS;
    }

    /**
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAll()
    {
        if (!isset($this->attendants)) {
            $this->attendants = $this->get();
        }

        return $this->attendants;
    }

    /**
     * @param SLN_Wrapper_Service $service
     * @return SLN_Wrapper_Attendant[]
     */
    public function findByService(SLN_Wrapper_Service $service)
    {
        $ret = array();

        foreach ($this->getAll() as $attendant) {
            $attendantServicesIds = $attendant->getServicesIds();
            if (empty($attendantServicesIds) || in_array($service->getId(), $attendantServicesIds)) {
                $ret[] = $attendant;
            }
        }

        return $ret;
    }
}