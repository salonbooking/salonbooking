<?php

class SLN_Shortcode_Salon_DetailsStep extends SLN_Shortcode_Salon_AbstractUserStep
{
    protected function dispatchForm()
    {
        global $current_user;
	    if (isset($_POST['fb_id'])) {
			$values = $this->parseFBValues($_POST);
		    $this->dispatchAuthFB($values);
		    if ($this->hasErrors()) {
			    return false;
		    }
	    } elseif (isset($_POST['login_name'])) {
            $ret = $this->dispatchAuth($_POST['login_name'], $_POST['login_password']);
            if (!$ret) {
                return false;
            }

            $values = array(
                'firstname' => '',
                'lastname'  => '',
                'email'     => '',
                'phone'     => '',
                'address'   => '',
            );
            if (!SLN_Enum_CheckoutFields::isHidden('firstname')) {
                $values['firstname'] = $current_user->user_firstname;
            }
            if (!SLN_Enum_CheckoutFields::isHidden('lastname')) {
                $values['lastname'] = $current_user->user_lastname;
            }
            if (!SLN_Enum_CheckoutFields::isHidden('email')) {
                $values['email'] = $current_user->user_email;
            }
            if (!SLN_Enum_CheckoutFields::isHidden('phone')) {
                $values['phone'] = get_user_meta($current_user->ID, '_sln_phone', true);
            }
            if (!SLN_Enum_CheckoutFields::isHidden('address')) {
                $values['address'] = get_user_meta($current_user->ID, '_sln_address', true);
            }
            $additional_fields = array_keys( SLN_Enum_CheckoutFields::toArray('additional'));
            if($additional_fields){
                foreach ($additional_fields as $field ) {
                    if (!SLN_Enum_CheckoutFields::isHidden($field))
                    $values[$field] = get_user_meta($current_user->ID, '_sln_'.$field, true);
                }
            }
            $this->bindValues($values);
            $this->validate($values);
            if ($this->getErrors()) {
                $this->bindValues($values);
                return false;
            }else{
                $_SESSION['sln_sms_dontcheck'] = true;
            }
        } else {
            $values = $_POST['sln'];
            $this->bindValues($values);
            if (!is_user_logged_in()) {
                $this->validate($values);
                if ($this->getErrors()) {
                    return false;
                }

                if ($this->getPlugin()->getSettings()->get('enabled_force_guest_checkout') || $this->getPlugin()->getSettings()->get('enabled_guest_checkout') && isset($values['no_user_account']) && $values['no_user_account']) {
                    $_SESSION['sln_detail_step'] = $values;
                } else {
                    if (email_exists($values['email'])) {
                        $this->addError(__('E-mail exists', 'salon-booking-system'));
                        if ($this->getErrors()) {
                            return false;
                        }
                    }

                    if ($values['password'] != $values['password_confirm']) {
                        $this->addError(__('Passwords are different', 'salon-booking-system'));
                        if ($this->getErrors()) {
                            return false;
                        }
                    }

                    if(!$this->getShortcode()->needSms()) {
                        $this->successRegistration($values);
                    }else{
                        $_SESSION['sln_detail_step'] = $values;
                    }
                }
            }else{
                wp_update_user(
                    array('ID' => $current_user->ID, 'first_name' => $values['firstname'], 'last_name' => $values['lastname'])
                );
                $user_meta_fields = array_merge(array('phone', 'address'), array_keys( SLN_Enum_CheckoutFields::toArray('additional')));
                foreach($user_meta_fields as $k){
                    if(isset($values[$k])){
                       update_user_meta($current_user->ID, '_sln_'.$k, $values[$k]);
                    }
                }
            }
        }
        $this->bindValues($values);

        return true;
    }

    private function parseFBValues($params) {
    	return array(
		    'fb_id'     => isset($params['fb_id']) ? $params['fb_id'] : '',
		    'firstname' => isset($params['fb_firstname']) ? $params['fb_firstname'] : '',
		    'lastname'  => isset($params['fb_lastname']) ? $params['fb_lastname'] : '',
		    'email'     => isset($params['fb_email']) ? $params['fb_email'] : '',
		    'phone'     => '',
		    'address'   => '',
	    );
    }
    
    private function dispatchAuthFB($values) {
	    $fbID        = $values['fb_id'];
	    $fbEmail     = $values['email'];
	    $fbFirstName = $values['firstname'];
	    $fbLastName  = $values['lastname'];

	    $userID = (int) SLN_Wrapper_Customer::getCustomerIdByFacebookID($fbID);
	    
	    if (empty($userID)) {
		    //create user
		    $errors = wp_create_user("fb_{$fbID}", wp_generate_password(), $fbEmail);
		    if (!is_wp_error($errors)) {
			    $userID = $errors;
			    wp_update_user(
				    array(
					    'ID'           => $userID,
					    'display_name' => $fbFirstName . ' ' . $fbLastName,
					    'nickname'     => $fbFirstName . ' ' . $fbLastName,
					    'first_name'   => $fbFirstName,
					    'last_name'    => $fbLastName,
					    'role'         => SLN_Plugin::USER_ROLE_CUSTOMER,
				    )
			    );
			    add_user_meta($userID, '_sln_fb_id', $fbID);
			    add_user_meta($userID, '_sln_phone', '');
			    add_user_meta($userID, '_sln_address', '');
                $additional_fields = array_keys( SLN_Enum_CheckoutFields::toArray('additional'));
                foreach($additional_fields as $k){
                    add_user_meta($userID, '_sln_'.$k, '');
                }
			    wp_new_user_notification($errors, null, 'both');
		    } else {
			    $this->addError($errors->get_error_message());
			    return false;
		    }
	    }

	    if (!$this->getErrors() && !empty($userID)) {
		    wp_set_auth_cookie($userID);
		    wp_set_current_user($userID);
	    }

	    return true;
    }

    private function validate($values){
        if (SLN_Enum_CheckoutFields::isRequired('firstname') && empty($values['firstname'])) {
            $this->addError(__('First name can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('lastname') && empty($values['lastname'])) {
            $this->addError(__('Last name can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('phone') && empty($values['phone'])) {
            $this->addError(__('Mobile phone can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('address') && empty($values['address'])) {
            $this->addError(__('Address can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('email')) {
            if (empty($values['email'])) {
                $this->addError(__('e-mail can\'t be empty', 'salon-booking-system'));
            }
            if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
                $this->addError(__('e-mail is not valid', 'salon-booking-system'));
            }
        }
        $fields = SLN_Enum_CheckoutFields::toArray('additional');
        foreach ($fields as $field => $label) {
            if (SLN_Enum_CheckoutFields::isRequired($field) && empty($values[$field])){
                $this->addError(__($label.' can\'t be empty', 'salon-booking-system'));
            }
        }
    }
}
