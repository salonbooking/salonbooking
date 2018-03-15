<?php 

class SLN_Action_Ajax_AddHolydayRule extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}



		if(current_user_can('manage_options')) {
			$data = array();

			$data['from_date']	= $_POST['rule']['from_date'];
			$data['to_date']	= $_POST['rule']['to_date'];
			$data['from_time']	= $_POST['rule']['from_time'];
			$data['to_time']	= $_POST['rule']['to_time'];
			$data['daily']		= true;
			
			$plugin = SLN_Plugin::getInstance();
			$settings = $plugin->getSettings();
			$holidays_rules = $settings->get('holidays_daily')?:array();

			//$rule = new SLN_Helper_HolidayItem($rule);
			$holidays_rules[] = $data;

			//$holidays_rules = SLN_Helper_HolidayItems::processSubmission($holidays_rules);
			
			$settings->set('holidays_daily', $holidays_rules);
			$settings->save();
			
		} else {
			$this->addError(__("You don't have permissions", 'salon-booking-system'));
		}

		if ($errors = $this->getErrors()) {
			$ret = compact('errors');
		} else {
			$ret = array('success' => 1,'rules'=>$holidays_rules);
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
