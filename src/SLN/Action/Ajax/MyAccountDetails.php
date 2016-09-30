<?php // algolplus

class SLN_Action_Ajax_MyAccountDetails extends SLN_Action_Ajax_Abstract
{
	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
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
