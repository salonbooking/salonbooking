<?php

class SLN_Wrapper_Service extends SLN_Wrapper_Abstract
{
    const _CLASS = 'SLN_Wrapper_Service';

    private $availabilityItems;
    
    public function getPostType()
    {
        return SLN_Plugin::POST_TYPE_SERVICE;
    }

    function getPrice()
    {
        $ret = $this->getMeta('price');
        $ret = empty($ret) ? 0 : floatval($ret);

        return $ret;
    }


    function getUnitPerHour()
    {
        $ret = $this->getMeta('unit');
        $ret = empty($ret) ? 0 : intval($ret);

        return $ret;
    }

    function getDuration()
    {
        $ret = $this->getMeta('duration');
        if (empty($ret)) {
            $ret = '00:00';
        }
        $ret = SLN_Func::filter($ret, 'time');

        return new SLN_DateTime('1970-01-01 '.$ret);
    }


    function isSecondary()
    {
        $ret = $this->getMeta('secondary');
        $ret = empty($ret) ? false : ($ret ? true : false);

        return $ret;
    }

    function getPosOrder()
    {
        $ret = $this->getMeta('order');
        $ret     = empty($ret) ? 0 : $ret;

        return $ret;
    }

    function getExecOrder()
    {
        $ret = $this->getMeta('exec_order');
        $ret = empty($ret) || 1 > $ret || 10 < $ret ? 1 : $ret;

        return $ret;
    }

    public function getAttendantsIds()
    {
        $ret = array();
        foreach ($this->getAttendants() as $attendant) {
            $ret[] = $attendant->getId();
        }

        return $ret;
    }

    /**
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAttendants()
    {
        /** @var SLN_Repository_AttendantRepository $repo */
        $repo = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);

        return $repo->findByService($this);
    }

    public function isAttendantsEnabled() {
        $ret = $this->getMeta('attendants');
        $ret     = empty($ret) ? 1 : !$ret;

        return $ret;
    }

    function getNotAvailableOn($key)
    {
        $post_id = $this->getId();
        $ret = apply_filters(
            'sln_service_notav_'.$key,
            get_post_meta($post_id, '_sln_service_notav_'.$key, true)
        );
        $ret = empty($ret) ? false : ($ret ? true : false);

        return $ret;
    }


    function getNotAvailableFrom()
    {
        return $this->getNotAvailableTime('from');
    }

    function getNotAvailableTo()
    {
        return $this->getNotAvailableTime('to');
    }

    function isNotAvailableOnDate(SLN_DateTime $date)
    {
        return !$this->getAvailabilityItems()->isValidDatetime($date);
/*
        $key = array_search(SLN_Func::getDateDayName($date), SLN_Func::getDays());
        $notAvailableDay = $this->getNotAvailableOn($key);
        $time = new SLN_DateTime('1970-01-01 '.$date->format('H:i'));
        $notAvailableTime = $this->getNotAvailableFrom()
            && $this->getNotAvailableFrom() <= $time
            && $this->getNotAvailableTo()
            && $this->getNotAvailableTo() >= $time;

        return $notAvailableDay && $notAvailableTime;
*/
    }

    public function getNotAvailableString()
    {
        return implode('<br/>',$this->getAvailabilityItems()->toArray());
    }

    public function getName()
    {
        if ($this->object) {
            return $this->object->post_title;
        } else {
            return 'n.d.';
        }
    }

    public function getContent()
    {
        return $this->object->post_excerpt;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return SLN_Helper_AvailabilityItems
     */
    function getAvailabilityItems()
    {
        if (!isset($this->availabilityItems)) {
            $this->availabilityItems = new SLN_Helper_AvailabilityItems($this->getMeta('availabilities'));
        }
        return $this->availabilityItems;
    }
}
