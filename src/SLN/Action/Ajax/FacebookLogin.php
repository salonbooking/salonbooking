<?php

class SLN_Action_Ajax_FacebookLogin extends SLN_Action_Ajax_Abstract
{
	protected $fbID;
	protected $fbEmail;
	protected $fbFirstName;
	protected $fbLastName;
	protected $errors = array();

	public function execute()
	{
		if (!isset($this->fbEmail)) {
			if(isset($_POST['fbEmail'])){
				$this->fbEmail = $_POST['fbEmail'];
			}
			if(isset($_POST['fbFirstName'])){
				$this->fbFirstName = $_POST['fbFirstName'];
			}
			if(isset($_POST['fbLastName'])){
				$this->fbLastName = $_POST['fbLastName'];
			}
			if(isset($_POST['fbID'])){
				$this->fbID = $_POST['fbID'];
			}
		}

		if (!$this->getErrors()) {
			$this->login();
		}

		if ($errors = $this->getErrors()) {
			$ret = compact('errors');
		} else {
			$ret = array('success' => 1);
		}

		return $ret;
	}

	public function login() {
		$userID = SLN_Wrapper_Customer::getCustomerIdByFacebookID($this->fbID);

		if (empty($userID)) {
			//create user
			$errors = wp_create_user("fb_{$this->fbID}", wp_generate_password(), $this->fbEmail);
			if (!is_wp_error($errors)) {
				$userID = $errors;
				wp_update_user(
					array(
						'ID'           => $userID,
						'display_name' => $this->fbFirstName . ' ' . $this->fbLastName,
						'nickname'     => $this->fbFirstName . ' ' . $this->fbLastName,
						'first_name'   => $this->fbFirstName,
						'last_name'    => $this->fbLastName,
						'role'         => SLN_Plugin::USER_ROLE_CUSTOMER,
					)
				);
				add_user_meta($userID, '_sln_fb_id', $this->fbID);
				add_user_meta($userID, '_sln_phone', '');
				add_user_meta($userID, '_sln_address', '');

				wp_new_user_notification($errors, null, 'both');
			} else {
				$this->addError($errors->get_error_message());
			}
		}

		if (!$this->getErrors() && !empty($userID)) {
			//login
			$user = get_user_by('id', (int) $userID);
			wp_set_auth_cookie($user->ID, false);
			do_action('wp_login', $user->user_login, $user);
		}

		return true;
	}

	protected function addError($err)
	{
		$this->errors[] = $err;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}
