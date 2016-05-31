<?php // algolplus

class SLN_Action_Ajax_SetCustomText extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		$plugin = SLN_Plugin::getInstance();

		if(current_user_can('manage_options')) {
			$plugin->getSettings()->setCustomText($_POST['key'], $_POST['value']);
			$plugin->getSettings()->save();
		} else {
			$this->addError(__("You don't have permissions", 'salon-booking-system'));
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
