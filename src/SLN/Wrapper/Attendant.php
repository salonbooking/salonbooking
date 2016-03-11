<?php

class SLN_Wrapper_Attendant extends SLN_Wrapper_Abstract
{
    function getNotAvailableOn($key)
    {
        $post_id = $this->getId();
        $ret     = apply_filters(
            'sln_attendant_notav_' . $key,
            get_post_meta($post_id, '_sln_attendant_notav_' . $key, true)
        );
        $ret     = empty($ret) ? false : ($ret ? true : false);

        return $ret;
    }

    function getEmail(){
        return apply_filters(
            'sln_attendant_email',
            get_post_meta($this->getId(), '_sln_attendant_email', true)
        );
    }

    function getPhone(){
        return apply_filters(
            'sln_attendant_phone',
            get_post_meta($this->getId(), '_sln_attendant_phone', true)
        );
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
            'sln_attendant_notav_' . $key,
            get_post_meta($post_id, '_sln_attendant_notav_' . $key, true)
        );
        if($key == 'to' && $ret == '00:00')
            $ret = '23:59';
        $ret     = SLN_Func::filter($ret, 'time');

        return new DateTime('1970-01-01 ' . $ret);
    }

    public function getNotAvailableString()
    {
        $ret = array();
        foreach (SLN_Func::getDays() as $k => $day) {
            if ($this->getNotAvailableOn($k)) {
                $ret[] = $day;
            }
        }
        $ret  = $ret ? __('on ', 'salon-booking-system') . implode(', ', $ret) : '';
        $from = $this->getNotAvailableFrom()->format('H:i');
        $to   = $this->getNotAvailableTo()->format('H:i');
        if ($from != '00:00') {
            $ret .= __(' from ', 'salon-booking-system') . $from;
        }
        if ($to != '00:00') {
            $ret .= __(' to ', 'salon-booking-system') . $to;
        }

        return $ret;
    }

    public function getServicesIds()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_attendant_services', get_post_meta($post_id, '_sln_attendant_services', true));
        if(is_array($ret))
            $ret = array_unique($ret);
        return empty($ret) ? array() : $ret;
    }

    public function getServices()
    {
        $ret = array();
        foreach($this->getServicesIds() as $id){
            $tmp = new SLN_Wrapper_Service($id);
            if(!$tmp->isEmpty()){
                $ret[] = $tmp;
            }
        }
        return $ret;
    }

    public function hasService(SLN_Wrapper_Service $service)
    {
        return in_array($service->getId(), $this->getServicesIds());
    }

    public function hasAllServices()
    {
        //an assistant without services is an assistant available for all services
        return $this->getServicesIds() ? false : true;
    }

    public function getName()
    {
        return $this->object->post_title;
    }

    public function getContent()
    {
        return $this->object->post_excerpt;
    }

    public function __toString(){
        return $this->getName();
    }
}
