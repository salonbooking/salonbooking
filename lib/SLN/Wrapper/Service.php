<?php

class SLN_Wrapper_Service extends SLN_Wrapper_Abstract
{
    function getPrice()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_price', get_post_meta($post_id, '_sln_service_price', true));
        $ret     = !empty($ret) ? floatval($ret) : '';

        return $ret;
    }


    function getUnit()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_unit', get_post_meta($post_id, '_sln_service_unit', true));
        $ret     = !empty($ret) ? floatval($ret) : '';

        return $ret;
    }

    function getDuration()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_duration', get_post_meta($post_id, '_sln_service_duration', true));
        $ret     = !empty($ret) ? floatval($ret) : '';

        return $ret;
    }


    function isSecondary()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_service_secondary', get_post_meta($post_id, '_sln_service_secondary', true));
        $ret     = empty($ret) ? false : ($ret ? true : false);

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

    function getNotAvailableTime($key)
    {
        $post_id = $this->getId();
        $ret     = apply_filters(
            'sln_service_notav_' . $key,
            get_post_meta($post_id, '_sln_service_notav_' . $key, true)
        );
        $ret     = SLN_Func::filter($ret, 'time');

        return new \DateTime('1970-01-01 '.$ret);
    }
}