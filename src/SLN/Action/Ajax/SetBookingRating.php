<?php // algolplus

class SLN_Action_Ajax_SetBookingRating extends SLN_Action_Ajax_Abstract
{
	public function execute()
	{
		if($timezone = get_option('timezone_string'))
			date_default_timezone_set($timezone);

		if (!is_user_logged_in())
		{
			return array( 'redirect' => wp_login_url());
		}

		$ret = array();

		$booking = SLN_Plugin::getInstance()->createBooking($_POST['id']);

		$available = $booking->getUserId() == get_current_user_id();

		if ($available)
		{
			$booking->setRating($_POST['score']);
		}
		else
		{
			$this->addError(__("You don't have access", 'sln'));
		}

		if ($errors = $this->getErrors())
		{
			$ret = compact('errors');
		} else
		{
			$ret = array('success' => 1);
		}

		return $ret;
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
