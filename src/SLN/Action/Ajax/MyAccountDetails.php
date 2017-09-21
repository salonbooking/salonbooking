<?php // algolplus

class SLN_Action_Ajax_MyAccountDetails extends SLN_Action_Ajax_Abstract
{
	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		if (isset($_POST['feedback_id']) && $_POST['feedback_id'] != 'false') {
			$feedback_id = intval( $_POST[ 'feedback_id' ] );

			$id = $this->plugin->getSettings()->getBookingmyaccountPageId();
			if ($id) {
				$url = get_permalink($id);
			} else {
				$url = home_url();
			}
            
			
            $booking = new SLN_Wrapper_Booking( $feedback_id );
            if( $booking->getUserId() != get_current_user_id() || $booking->getRating() ) {
                return array( 'redirect' => $url );
            }
		}

		$args = array();
		if (isset($_POST['args'])) {
			$args = $_POST['args'];
		}

		$shortcode  = "[" . SLN_Shortcode_SalonMyAccount_Details::NAME;
		foreach($args as $k => $v) {
			$shortcode .= " $k='" . addcslashes($v, "'") . "'";
		}
		$shortcode .= "]";

		$ret['content'] = do_shortcode($shortcode);

		return $ret;
	}
}
