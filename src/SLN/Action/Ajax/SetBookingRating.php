<?php // algolplus

class SLN_Action_Ajax_SetBookingRating extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		if (isset($_POST['score']) && isset($_POST['comment'])) {
			$booking = SLN_Plugin::getInstance()->createBooking($_POST['id']);

			$available = $booking->getUserId() == get_current_user_id();

			if ($available) {

				$rating = $booking->getRating();
				if( !$rating ) {
					$booking->setRating($_POST['score']);

					wp_insert_comment(array(
						'comment_author' => wp_get_current_user()->display_name,
						'comment_author_email' => wp_get_current_user()->user_email,
						'comment_content' => sanitize_text_field($_POST['comment']),
						'comment_post_ID' => intval($_POST['id']),
						'comment_type' => 'sln_review',
					));

					$args = compact('booking');
					SLN_Plugin::getInstance()->sendMail('mail/booking_rated', $args);
				} else {
					$this->addError(__("You don't have access", 'salon-booking-system'));
				}
			}
			else {
				$this->addError(__("You don't have access", 'salon-booking-system'));
			}
		}
		else {
			$this->addError(__("Set rating and comment", 'salon-booking-system'));
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
