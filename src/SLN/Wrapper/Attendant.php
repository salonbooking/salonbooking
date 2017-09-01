<?php

class SLN_Wrapper_Attendant extends SLN_Wrapper_Abstract implements SLN_Wrapper_AttendantInterface
{
    const _CLASS = 'SLN_Wrapper_Attendant';
    private $availabilityItems;
    private $holidayItems;

    public function getPostType()
    {
        return SLN_Plugin::POST_TYPE_ATTENDANT;
    }

    function getNotAvailableOn($key)
    {
        $post_id = $this->getId();
        $ret = apply_filters(
            'sln_attendant_notav_'.$key,
            get_post_meta($post_id, '_sln_attendant_notav_'.$key, true)
        );
        $ret = empty($ret) ? false : ($ret ? true : false);

        return $ret;
    }

    function getEmail()
    {
        return $this->getMeta('email');
    }

    function getPhone()
    {
        return $this->getMeta('phone');
    }

    function getPosOrder()
    {
        $ret = $this->getMeta('order');
        $ret     = empty($ret) ? 0 : $ret;

        return $ret;
    }

    function isNotAvailableOnDate(SLN_DateTime $date)
    {
        return !($this->getAvailabilityItems()->isValidDatetime($date) && $this->getHolidayItems()->isValidDatetime($date));
    }

    /**
     * @return SLN_Helper_AvailabilityItems
     */
    public function getAvailabilityItems()
    {
        if (!isset($this->availabilityItems)) {
            $this->availabilityItems = new SLN_Helper_AvailabilityItems($this->getMeta('availabilities'));
        }
        return $this->availabilityItems;
    }

    /**
     * @return SLN_Helper_HolidayItems
     */
    public function getHolidayItems()
    {
        if (!isset($this->holidayItems)) {
            $this->holidayItems = new SLN_Helper_HolidayItems($this->getMeta('holidays'));
        }
        return $this->holidayItems;
    }

    public function getNotAvailableString()
    {
        return ''; //'(Available '.implode('-', $this->getAvailabilityItems()->toArray()).')';
    }

    public function getServicesIds()
    {
        $ret = $this->getMeta('services');
        if (is_array($ret)) {
            $ret = array_unique($ret);
        }

        return empty($ret) ? array() : $ret;
    }

    public function getServices()
    {
        $ret = array();
        foreach ($this->getServicesIds() as $id) {
            $tmp = new SLN_Wrapper_Service($id);
            if (!$tmp->isEmpty()) {
                $ret[] = $tmp;
            }
        }

        return $ret;
    }

    public function hasService(SLN_Wrapper_ServiceInterface $service)
    {
        return in_array($service->getId(), $this->getServicesIds());
    }

    public function hasServices($services)
    {
        if ($this->hasAllServices()) {
            return true;
        }
        /** @var SLN_Wrapper_ServiceInterface $service */
        foreach ($services as $service) {
            if (!$this->hasService($service)) {
                return false;
            }
        }
        return true;
    }

    public function hasAllServices()
    {
        //an assistant without services is an assistant available for all services
        return $this->getServicesIds() ? false : true;
    }

    public function getGoogleCalendar()
    {
        return $this->getMeta('google_calendar');
    }

    public function getName()
    {
        return $this->getTitle();
    }

    public function getContent()
    {
        if(isset($this->object->post_excerpt))
            return $this->object->post_excerpt;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
