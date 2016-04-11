<?php

class SLN_Repository_ServiceRepository extends SLN_Repository_AbstractWrapperRepository
{
    const SERVICE_ORDER = '_sln_service_order';

    private $services;

    public function getWrapperClass()
    {
        return SLN_Wrapper_Service::_CLASS;
    }

    protected function processCriteria($criteria)
    {
        if (isset($criteria['@sort'])) {
            $criteria['@wp_query'] = array(
                '@wp_query' => array(
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key' => self::SERVICE_ORDER,
                            'compare' => 'EXISTS',
                        ),
                        array(
                            'key' => self::SERVICE_ORDER,
                            'compare' => 'NOT EXISTS',
                        ),
                    ),
                    'orderby' => self::SERVICE_ORDER,
                    'order' => 'ASC',
                ),
            );
        }

        return parent::processCriteria($criteria);
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getAll()
    {
        if (!isset($this->services)) {
            $this->services = $this->get(array('@sort' => true));
        }

        return $this->services;
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getAllSecondary()
    {
        $ret = array();
        foreach ($this->getAll() as $s) {
            if ($s->isSecondary()) {
                $ret[] = $s;
            }
        }

        return $ret;
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getAllPrimary()
    {
        $ret = array();
        foreach ($this->getAll() as $s) {
            if (!$s->isSecondary()) {
                $ret[] = $s;
            }
        }

        return $ret;
    }

    public function getStandardCriteria()
    {
        return $this->processCriteria(array('@sort' => true));
    }

    /**
     * @param SLN_Wrapper_Service[] $services
     * @return SLN_Wrapper_Service[]
     */
    public function sortByExec($services)
    {
        usort($services, array($this, 'serviceCmp'));

        return $services;
    }

    public function serviceExecCmp(SLN_Wrapper_Service $a, SLN_Wrapper_Service $b)
    {
        /** @var SLN_Wrapper_Service $a */
        /** @var SLN_Wrapper_Service $b */
        $aExecOrder = $a->getExecOrder();
        $bExecOrder = $b->getExecOrder();
        if ($aExecOrder > $bExecOrder) {
            return 1;
        } else {
            return -1;
        }
    }

    public function serviceCmp(SLN_Wrapper_Service $a, SLN_Wrapper_Service $b)
    {
        if (!$b) {
            return $a;
        }
        if (!$a) {
            return $b;
        }
        $aExecOrder = $a->getExecOrder();
        $bExecOrder = $b->getExecOrder();
        if ($aExecOrder != $bExecOrder) {
            return $aExecOrder > $bExecOrder ? 1 : -1;
        } else {
            $aPosOrder = $a->getPosOrder();
            $bPosOrder = $b->getPosOrder();
            if ($aPosOrder != $bPosOrder) {
                return $aPosOrder > $bPosOrder ? 1 : -1;
            } elseif ($a->getId() > $b->getId()) {
                return 1;
            } else {
                return -1;
            }
        }
    }
}