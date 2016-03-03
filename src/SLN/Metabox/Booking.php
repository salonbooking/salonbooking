<?php

class SLN_Metabox_Booking extends SLN_Metabox_Abstract
{
    public function add_meta_boxes()
    {
        $pt = $this->getPostType();
        add_meta_box(
            $pt . '-details',
            __('Booking details', 'salon-booking-system'),
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
            'amount'    => 'float',
            'deposit'   => 'float',
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'phone'     => '',
            'address'   => '',
            'date'      => 'date',
            'time'      => 'time',
            'attendant'  => '',
            'services'  => 'nofilter',
            'note' => '',
            'admin_note' => '',
            '_sln_calendar_event_id' => ''
        );
    }

    private $disabledSavePost = false;
    public function save_post($post_id, $post){
        if($this->disabledSavePost)
            return;

        if(!isset($_POST['_sln_booking_status']))
            return;
        if(isset($_POST['_sln_booking_services']))
        foreach($_POST['_sln_booking_services'] as $k => $v){
            $_POST['_sln_booking_services'][$k] = str_replace('sln_booking_services_','', $v);
        }
        parent::save_post($post_id, $post);
        $this->validate($_POST);
        if(isset($_SESSION['_sln_booking_user_errors']))
            return;

        $booking = new SLN_Wrapper_Booking($post_id);
        $booking->evalDuration();
        $s = $booking->getStatus();
        $new =  $_POST['_sln_booking_status'];
        if(strpos($new,'sln-b-') !== 0) $new = 'sln-b-pendingpayment';
        $postnew = array();
        if(strpos($s,'sln-b-') !== 0){
            $postnew = array_merge($postnew, array(
                'ID' => $post_id,
                'post_status' => $new
            ));
        }
        if(isset($_POST['_sln_booking_createuser']) && $_POST['_sln_booking_createuser'] && get_post_field('post_type', $post_id) === SLN_Plugin::POST_TYPE_BOOKING){
            $userid = $this->registration($_POST);
            if($userid instanceof WP_Error)
                return;
            $postnew = array_merge($postnew, array(
                'ID' => $post_id,
                'post_author' => $userid
            ));
        }
        if(!empty($postnew)){
            $this->disabledSavePost = true;
            wp_update_post($postnew);
            $this->disabledSavePost = false;
        }

        $booking = new SLN_Wrapper_Booking($post_id);
        $user = new WP_User($booking->getUserId());
        if (array_search('administrator', $user->roles) === false && array_search('subscriber', $user->roles) !== false) {
            wp_update_user(array(
                'ID' => $booking->getUserId(),
                'role' => SLN_Plugin::USER_ROLE_CUSTOMER,
            ));
        }
    } 

    protected function registration($data){
        $errors = wp_create_user($data['_sln_booking_email'], wp_generate_password(), $data['_sln_booking_email']);
        if (!is_wp_error($errors)) {
            wp_update_user(
                array('ID' => $errors, 'first_name' => $data['_sln_booking_firstname'], 'last_name' => $data['_sln_booking_lastname'], 'role' => SLN_Plugin::USER_ROLE_CUSTOMER)
            );
            add_user_meta($errors, '_sln_phone', $data['_sln_booking_phone']);
            add_user_meta($errors, '_sln_address', $data['_sln_booking_address']);

            wp_new_user_notification($errors); //, $values['password']);
        } else {
            $this->addError($errors->get_error_message());
        }

        return $errors;
    }

    private function validate($values){
        if (empty($values['_sln_booking_firstname'])) {
            $this->addError(__('First name can\'t be empty', 'salon-booking-system'));
        }
        if (empty($values['_sln_booking_lastname'])) {
            $this->addError(__('Last name can\'t be empty', 'salon-booking-system'));
        }
        if(isset($_POST['_sln_booking_createuser']) && $_POST['_sln_booking_createuser']) {
            if (empty($values['_sln_booking_email'])) {
                $this->addError(__('e-mail can\'t be empty', 'salon-booking-system'));
            }
            if (empty($values['_sln_booking_phone'])) {
                $this->addError(__('Mobile phone can\'t be empty', 'salon-booking-system'));
            }
        }

        if (!empty($values['_sln_booking_email']) && !filter_var($values['_sln_booking_email'], FILTER_VALIDATE_EMAIL)) {
            $this->addError(__('e-mail is not valid', 'salon-booking-system'));
        }
    }

    protected function addError($message){
        $_SESSION['_sln_booking_user_errors'][] = $message;
    }
}

