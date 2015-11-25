<?php // algolplus

class SLN_Action_Ajax_CancelBooking extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if($timezone = get_option('timezone_string'))
			date_default_timezone_set($timezone);

		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		$ret = array();
		$plugin = SLN_Plugin::getInstance();
//		$posttypeBooking = new SLN_PostType_Booking($plugin, SLN_Plugin::POST_TYPE_BOOKING);
		$booking = $plugin->createBooking($_POST['id']);

		$available = $booking->getUserId() == get_current_user_id();
		$cancellationEnabled = $plugin->getSettings()->get('cancellation_enabled');
		$outOfTime = (strtotime($booking->getDate())-time()) < $plugin->getSettings()->get('hours_before_cancellation') * 3600;

		if ($cancellationEnabled && !$outOfTime && $available) {
//			$posttypeBooking->transitionPostStatus(SLN_Enum_BookingStatus::CANCELED, $booking->getStatus(), get_post($_POST['id']));
			$plugin->sendMail('mail/status_canceled', compact('booking'));
			$booking->setStatus(SLN_Enum_BookingStatus::CANCELED);
		} elseif (!$available) {
			$this->addError(__("You don't have access", 'sln'));
		} elseif (!$cancellationEnabled) {
			$this->addError(__('Cancellation disabled', 'sln'));
		} elseif ($outOfTime) {
			$this->addError(__('Out of time', 'sln'));
		}

		if ($errors = $this->getErrors()) {
			$ret = compact('errors');
		} else {
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