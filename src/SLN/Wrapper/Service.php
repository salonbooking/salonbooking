<?php

class SLN_Wrapper_Service extends SLN_Wrapper_Abstract
{
    function getPrice()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_price', get_post_meta($post_id, '_sln_service_price', true));
        $ret     = empty($ret) ? 0 : floatval($ret);

        return $ret;
    }


    function getUnitPerHour()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_unit', get_post_meta($post_id, '_sln_service_unit', true));
        $ret     = empty($ret) ? 0 : intval($ret);

        return $ret;
    }

    function getDuration()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_duration', get_post_meta($post_id, '_sln_service_duration', true));
        if(empty($ret)){
            $ret = '00:00';
        }
        $ret     = SLN_Func::filter($ret, 'time');
        return new SLN_DateTime('1970-01-01 ' . $ret);
    }


    function isSecondary()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_secondary', get_post_meta($post_id, '_sln_service_secondary', true));
        $ret     = empty($ret) ? false : ($ret ? true : false);

        return $ret;
    }

    function getExecOrder()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_exec_order', get_post_meta($post_id, '_sln_service_exec_order', true));
        $ret     = empty($ret) || 1 > $ret || 10 < $ret ? 1 : $ret;

        return $ret;
    }

    public function getAttendantsIds()
    {
        $ret = array();
        foreach($this->getAttendants() as $attendant) {
            $ret[] = $attendant->getId();
        }

        return $ret;
    }

	/**
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAttendants()
    {
        $ret = array();
        foreach(SLN_Plugin::getInstance()->getAttendants() as $attendant) {
            $attendantServicesIds = $attendant->getServicesIds();
            if (empty($attendantServicesIds) || in_array($this->getId(), $attendantServicesIds)) {
                $ret[] = $attendant;
            }
        }

        return $ret;
    }

    function getNotAvailableOn($key)
    {
        $post_id = $this->getId();
        $ret     = apply_filters(
            'sln_service_notav_' . $key,
            get_post_meta($post_id, '_sln_service_notav_' . $key, true)
        );
        $ret     = empty($ret) ? false : ($ret ? true : false);

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
        $key              = array_search(SLN_Func::getDateDayName($date), SLN_Func::getDays());
        $notAvailableDay  = $this->getNotAvailableOn($key);
        $time             = new SLN_DateTime('1970-01-01 ' . $date->format('H:i'));
        $notAvailableTime = $this->getNotAvailableFrom()
            && $this->getNotAvailableFrom() <= $time
            && $this->getNotAvailableTo()
            && $this->getNotAvailableTo() >= $time;

        return $notAvailableDay && $notAvailableTime;
    }

    function getNotAvailableTime($key)
    {
        $post_id = $this->getId();
        $ret     = apply_filters(
            'sln_service_notav_' . $key,
            get_post_meta($post_id, '_sln_service_notav_' . $key, true)
        );
        if($key == 'to' && $ret == '00:00')
            $ret = '23:59';
        $ret     = SLN_Func::filter($ret, 'time');

        return new SLN_DateTime('1970-01-01 ' . $ret);
    }

    public function getNotAvailableString()
    {
        $ret = array();
        foreach (SLN_Func::getDays() as $k => $day) {
            if ($this->getNotAvailableOn($k)) {
                $ret[] = $day;
            }
        }
        $ret  = $ret ? ' '.__('on ', 'salon-booking-system') .' '. implode(', ', $ret) : '';
        $from = $this->getNotAvailableFrom()->format('H:i');
        $to   = $this->getNotAvailableTo()->format('H:i');
        if ($from != '00:00') {
            $ret .= ' '.__(' from ', 'salon-booking-system') . ' ' . $from;
        }
        if ($to != '23:59') {
            $ret .= ' '.__(' to ', 'salon-booking-system') . ' ' . $to;
        }

        return $ret;
    }

    public function getName()
    {
        if($this->object)
        return $this->object->post_title;
        else
        return 'n.d.';
    }

    public function getContent()
    {
        return $this->object->post_excerpt;
    }

    public function __toString(){
        return $this->getName();
    }
}
