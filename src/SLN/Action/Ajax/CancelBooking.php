<?php // algolplus

class SLN_Action_Ajax_CancelBooking extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		$ret = array();
		$plugin = SLN_Plugin::getInstance();
		$booking = $plugin->createBooking($_POST['id']);

		$available = $booking->getUserId() == get_current_user_id();
		$cancellationEnabled = $plugin->getSettings()->get('cancellation_enabled');
		$outOfTime = (strtotime($booking->getStartsAt())-current_time('timestamp')) < $plugin->getSettings()->get('hours_before_cancellation') * 3600;

		if ($cancellationEnabled && !$outOfTime && $available) {
			$booking->setStatus(SLN_Enum_BookingStatus::CANCELED);
			$booking = $plugin->createBooking($_POST['id']);

			$args = compact('booking');

			$args['forAdmin'] = true;
			$args['to'] = $plugin->getSettings()->getSalonEmail();
			$plugin->sendMail('mail/status_canceled', $args);
		} elseif (!$available) {
			$this->addError(__("You don't have access", 'salon-booking-system'));
		} elseif (!$cancellationEnabled) {
			$this->addError(__('Cancellation disabled', 'salon-booking-system'));
		} elseif ($outOfTime) {
			$this->addError(__('Out of time', 'salon-booking-system'));
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
