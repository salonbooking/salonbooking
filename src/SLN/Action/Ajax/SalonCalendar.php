<?php // algolplus

class SLN_Action_Ajax_SalonCalendar extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}
        SLN_TimeFunc::startRealTimezone();

		$plugin = SLN_Plugin::getInstance();
		$atts = array();
		if($_REQUEST['attendantsIds'])$atts['assistants']=$_REQUEST['attendantsIds'];
		$obj    = new SLN_Shortcode_SalonCalendar($plugin,$atts );

        $ret            = array();
		$ret['content'] = $obj->getContent();

		if ($errors = $this->getErrors()) {
			$ret = compact('errors');
		} else {
            $ret['success'] = 1;
		}

        SLN_TimeFunc::endRealTimezone();

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
