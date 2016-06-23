<?php

class SLN_Shortcode_Salon_DetailsStep extends SLN_Shortcode_Salon_AbstractUserStep
{
    protected function dispatchForm()
    {
        global $current_user;
        if (isset($_POST['login_name'])) {
            $ret = $this->dispatchAuth($_POST['login_name'], $_POST['login_password']);
            if (!$ret) {
                return false;
            }

            $values = array(
                'firstname' => $current_user->user_firstname,
                'lastname'  => $current_user->user_lastname,
                'email'     => $current_user->user_email,
                'phone'     => get_user_meta($current_user->ID, '_sln_phone', true),
                'address'     => get_user_meta($current_user->ID, '_sln_address', true)
            );
            $this->bindValues($values);
            $this->validate($values);
            if ($this->getErrors()) {
                $this->bindValues($values);
                return false;
            }
        } else {
            $values = $_POST['sln'];
            $this->bindValues($values);
            if (!is_user_logged_in()) {
                $this->validate($values);
                if ($this->getErrors()) {
                    return false;
                }

                if (email_exists($values['email'])) {
                    $this->addError(__('E-mail exists', 'salon-booking-system'));
                    if ($this->getErrors()) {
                        return false;
                    }
                }

                if ($this->getPlugin()->getSettings()->get('enabled_guest_checkout') && isset($values['no_user_account']) && $values['no_user_account']) {
                    $_SESSION['sln_detail_step'] = $values;
                } else {
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
                foreach(array('phone', 'address') as $k){
                    if(isset($values[$k])){
                       update_user_meta($current_user->ID, '_sln_'.$k, $values[$k]);
                    }
                }
            }
        }
        $this->bindValues($values);

        return true;
    }

    private function validate($values){
        if (empty($values['firstname'])) {
            $this->addError(__('First name can\'t be empty', 'salon-booking-system'));
        }
        if (empty($values['lastname'])) {
            $this->addError(__('Last name can\'t be empty', 'salon-booking-system'));
        }
        if (empty($values['email'])) {
            $this->addError(__('e-mail can\'t be empty', 'salon-booking-system'));
        }
        if (empty($values['phone'])) {
            $this->addError(__('Mobile phone can\'t be empty', 'salon-booking-system'));
        }
#       if (empty($values['address'])) {
#           $this->addError(__('Address can\'t be empty', 'salon-booking-system'));
#       } 
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $this->addError(__('e-mail is not valid', 'salon-booking-system'));
        }
    }
}
