<?php

class SLN_Metabox_Booking extends SLN_Metabox_Abstract
{
    public function add_meta_boxes()
    {
        $pt = $this->getPostType();
        add_meta_box(
            $pt . '-details',
            __('Booking details', 'sln'),
            array($this, 'details_meta_box'),
            $pt,
            'normal',
            'high'
        );
    }


    public function details_meta_box($object, $box)
    {
        echo $this->getPlugin()->loadView(
            'metabox/booking',
            array(
                'metabox'  => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'booking'  => $this->getPlugin()->createBooking($object),
                'postType' => $this->getPostType(),
                'helper'   => new SLN_Metabox_Helper()
            )
        );
        do_action($this->getPostType() . '_details_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        return array(
            'amount'    => 'money',
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'phone'     => '',
            'address'   => '',
            'duration'  => 'time',
            'date'      => 'date',
            'time'      => 'time',
            'attendant'  => '',
            'services'  => 'nofilter',
            '_sln_calendar_event_id' => ''
        );
    }
    public function save_post($post_id, $post){
        if(!$_POST)
            return;
        if(isset($_POST['_sln_booking_services']))
        foreach($_POST['_sln_booking_services'] as $k => $v){
            $_POST['_sln_booking_services'][$k] = str_replace('sln_booking_services_','', $v);
        }
        parent::save_post($post_id, $post);
        $booking = new SLN_Wrapper_Booking($post_id);
        $booking->evalDuration();
        $s = $booking->getStatus();
        $new =  $_POST['_sln_booking_status'];
        if(strpos($new,'sln-b-') !== 0) $new = 'sln-b-pending';
        if(strpos($s,'sln-b-') !== 0){
            var_dump($booking->evalTotal());
            $postnew = array(
                'ID' => $post_id,
                'post_status' => $new
            );
            wp_update_post($postnew);
        }
    } 

}

