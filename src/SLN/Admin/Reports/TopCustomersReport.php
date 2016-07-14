<?php

class SLN_Admin_Reports_TopCustomersReport extends SLN_Admin_Reports_AbstractReport {

	protected $type = 'bar';
	public $countOfCustomers = 20;

	protected function processBookings($day = null, $month_num = null, $year = null, $hour = null) {

		$ret = array();
		$ret['title'] = __('Top customers', 'salon-booking-system');
		$ret['subtitle'] = '';

		$ret['labels']['x'] = array(
				__('Earnings', 'salon-booking-system') => 'number',
				__('Bookings', 'salon-booking-system') => 'number',
		);
		$ret['labels']['y'] = array(
				''                                     => 'string',
		);

		$ret['data'] = array();

		foreach($this->bookings as $k => $bookings) {
			/** @var SLN_Wrapper_Booking $booking */
			foreach($bookings as $booking) {

				$user_id = $booking->getUserId();

				if (SLN_Wrapper_Customer::isCustomer($user_id)) {
					if (!array_key_exists($user_id, $ret['data'])) {
						$customer              = new SLN_Wrapper_Customer(new WP_User($user_id));
						$ret['data'][$user_id] = array($customer->getName(), 0.0, 0);
					}

					$ret['data'][$user_id][1] = $booking->getAmount();
					$ret['data'][$user_id][2] ++;
				}
			}
		}

		uasort($ret['data'], array($this, 'sort'));

		$ret['data'] = array_slice($ret['data'], 0, $this->countOfCustomers);

		$this->data = $ret;
	}

	protected function sort($a, $b) {
		if ($a[2] >= $b[2]) {
			return -1;
		}
		else {
			return 1;
		}
	}
}