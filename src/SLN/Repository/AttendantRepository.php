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
    public function getAll($criteria = array())
    {
        if ( ! isset($this->attendants)) {
            $this->attendants = $this->get($criteria);
        }

        return $this->attendants;
    }

    /**
     * @param SLN_Wrapper_Service $service
     *
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


    protected function processCriteria($criteria)
    {
        if (isset($criteria['@sort'])) {
            $criteria['@wp_query'] = array(
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key'     => self::SERVICE_ORDER,
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key'     => self::SERVICE_ORDER,
                        'compare' => 'NOT EXISTS',
                    ),
                ),
                'orderby'    => self::SERVICE_ORDER,
                'order'      => 'ASC',
            );
            unset($criteria['@sort']);
        }

        $criteria = apply_filters('sln.repository.attendant.processCriteria', $criteria);

        return parent::processCriteria($criteria);
    }
}