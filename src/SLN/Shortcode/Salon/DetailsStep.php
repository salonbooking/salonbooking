<?php

class SLN_Shortcode_Salon_DetailsStep extends SLN_Shortcode_Salon_Step
{
    protected function dispatchForm()
    {
        if ($_POST['login_name']) {
            $ret = $this->dispatchAuth($_POST['login_name'], $_POST['login_password']);
            global $current_user;
            get_currentuserinfo();
            $values = array(
                'firstname' => $current_user->user_firstname,
                'lastname'  => $current_user->user_lastname,
                'email'     => $current_user->user_email,
                'phone'     => get_user_meta($current_user->ID, '_sln_phone', true)
            );
            if (!$ret) {
                return false;
            }
        } else {
            $values = $_POST['sln'];
            if(!is_user_logged_in()){
                if(email_exists($values['email']))
                    $this->addError(__('E-mail exists', 'sln'));
                if($values['password'] != $values['password_confirm']){
                    $this->addError(__('Passwords are different', 'sln'));
                }
                if($this->getErrors())
                    return false;
                $errors = wp_create_user($values['email'], $values['password'], $values['email']);
                if ( is_wp_error($errors) ) {
                    $this->addError($errors->get_error_message());
                    die('b');
                }
                if(!$this->dispatchAuth($values['email'], $values['password']))
                    die('a');
            }
        }
        $this->bindValues($values);

        return true;
    }

    private function dispatchAuth($username, $password)
    {
        global $user;
        $creds                  = array();
        $creds['user_login']    = $username;
        $creds['user_password'] = $password;
        $creds['remember']      = true;
        $user                   = wp_signon($creds, false);
        if (is_wp_error($user)) {
            $this->addError($user->get_error_message());

            return false;
        }
        return true;
    }

    public function isValid()
    {
        $bb = $this->getPlugin()->getBookingBuilder();
        if (!$bb->get('email') && is_user_logged_in()) {
            global $current_user;
            get_currentuserinfo();
            $values = array(
                'firstname' => $current_user->user_firstname,
                'lastname'  => $current_user->user_lastname,
                'email'     => $current_user->user_email,
                'phone'     => get_user_meta($current_user->ID, '_sln_phone', true)
            );
            $this->bindValues($values);
        }

        return parent::isValid();
    }

    private function bindValues($values)
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        $fields = array(
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'phone'     => ''
        );
        foreach ($fields as $field => $filter) {
            $bb->set($field, SLN_Func::filter($values[$field], $filter));
        }

        $bb->save();
    }
}
