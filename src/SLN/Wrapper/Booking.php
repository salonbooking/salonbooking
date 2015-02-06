<?php

class SLN_Wrapper_Booking extends SLN_Wrapper_Abstract
{
    function getAmount()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_booking_amount', get_post_meta($post_id, '_sln_booking_amount', true));
        $ret     = number_format(!empty($ret) ? ($ret) : 0, 2);

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
    function getDisplayName(){
        return $this->getFirstname().' '.$this->getLastname();
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

        return apply_filters('sln_booking_time', new DateTime(get_post_meta($post_id, '_sln_booking_time', true)));
    }

    function getDate()
    {
        $post_id = $this->getId();

        return apply_filters('sln_booking_date', new DateTime(get_post_meta($post_id, '_sln_booking_date', true)));
    }

    function getDuration()
    {
        $post_id = $this->getId();

        return apply_filters('sln_booking_date', new DateTime(get_post_meta($post_id, '_sln_booking_date', true)));
    }

    function hasService(SLN_Wrapper_Service $service)
    {
        return in_array($service->getId(), $this->getServicesIds());
    }

    function getServicesIds()
    {
        $post_id = $this->getId();
        $ret     = apply_filters('sln_booking_services', get_post_meta($post_id, '_sln_booking_services', true));

        return empty($ret) ? array() : $ret;
    }

    function getStatus()
    {
        return $this->object->post_status;
    }

    function hasStatus($status)
    {
        return $this->getStatus() == $status;
    }

    /**
     * @param $status
     * @return $this
     */
    function setStatus($status)
    {
        $post_id = $this->getId();
        wp_transition_post_status($status, $this->object->post_status, $this->object->ID);

        return $this;
    }

    function getTitle()
    {
        return $this->object->post_title;
    }

    function getNote()
    {
        $post_id = $this->getId();

        return apply_filters(
            'sln_booking_note',
            get_post_meta($post_id, '_sln_booking_note', true)
        );
    }

    function getTransactionId()
    {
        $post_id = $this->getId();

        return apply_filters(
            'sln_booking_transaction_id',
            get_post_meta($post_id, '_sln_booking_transaction_id', true)
        );
    }

}
