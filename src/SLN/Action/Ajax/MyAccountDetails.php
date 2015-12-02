<?php // algolplus

class SLN_Action_Ajax_MyAccountDetails extends SLN_Action_Ajax_Abstract
{
	public function execute()
	{
		if($timezone = get_option('timezone_string'))
			date_default_timezone_set($timezone);

		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		$ret['content'] = do_shortcode( '[' . SLN_Shortcode_SalonMyAccount_Details::NAME . ']');

		return $ret;
	}
}
