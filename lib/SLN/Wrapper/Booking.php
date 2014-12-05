<?php

class SLN_Wrapper_Booking extends SLN_Wrapper_Abstract
{
    function getAmount()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_booking_amount', get_post_meta($post_id, '_sln_booking_amount', true));
        $ret     = !empty($ret) ? floatval($ret) : '';

        return $ret;
    }

    function getFirstname()
    {
        $post_id = $this->getId();

        return apply_filters('sln_booking_firstname', get_post_meta($post_id, '_sln_booking_firstname', true));
    }

    function getLastname()
    {
        $post_id = $this->getId();

        return apply_filters('sln_booking_lastname', get_post_meta($post_id, '_sln_booking_lastname', true));
    }

    function getEmail()
    {
        $post_id = $this->getId();

        return apply_filters('sln_booking_email', get_post_meta($post_id, '_sln_booking_email', true));
    }

    function getPhone()
    {
        $post_id = $this->getId();

        return apply_filters('sln_booking_phone', get_post_meta($post_id, '_sln_booking_phone', true));
    }

    function getTime()
    {
        $post_id = $this->getId();
        return apply_filters('sln_booking_time', new \DateTime(get_post_meta($post_id, '_sln_booking_time', true)));
    }

    function getDate()
    {
        $post_id = $this->getId();
        return apply_filters('sln_booking_date', new \DateTime(get_post_meta($post_id, '_sln_booking_date', true)));
    }

    function getDuration()
    {
        $post_id = $this->getId();
        return apply_filters('sln_booking_date', new \DateTime(get_post_meta($post_id, '_sln_booking_date', true)));
    }
}