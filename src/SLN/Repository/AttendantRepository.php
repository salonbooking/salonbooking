<?php

class SLN_Repository_AttendantRepository extends SLN_Repository_AbstractWrapperRepository
{
    const ATTENDANT_ORDER = '_sln_attendant_order';

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
                        'key'     => self::ATTENDANT_ORDER,
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key'     => self::ATTENDANT_ORDER,
                        'compare' => 'NOT EXISTS',
                    ),
                ),
                'orderby'    => self::ATTENDANT_ORDER,
                'order'      => 'ASC',
            );
            unset($criteria['@sort']);
        }

        $criteria = apply_filters('sln.repository.attendant.processCriteria', $criteria);

        return parent::processCriteria($criteria);
    }

    public function getStandardCriteria()
    {
        return $this->processCriteria(array('@sort' => true));
    }

    /**
     * @param SLN_Wrapper_Attendant[] $attendants
     *
     * @return SLN_Wrapper_Attendant[]
     */
    public function sortByPos($attendants)
    {
        usort($attendants, array($this, 'attendantPosCmp'));

        return $attendants;
    }

    public static function attendantPosCmp($a, $b)
    {
        if ( ! $b) {
            return $a;
        }
        if ( ! $a) {
            return $b;
        }
        if ( ! $a instanceof SLN_Wrapper_Attendant) {
            $a = SLN_Plugin::getInstance()->createAttendant($a);
        }
        if ( ! $b instanceof SLN_Wrapper_Attendant) {
            $b = SLN_Plugin::getInstance()->createAttendant($b);
        }

        /** @var SLN_Wrapper_Attendant $a */
        /** @var SLN_Wrapper_Attendant $b */
        $aOrder = $a->getPosOrder();
        $bOrder = $b->getPosOrder();
        if ($aOrder > $bOrder) {
            return 1;
        } else {
            return -1;
        }
    }
}