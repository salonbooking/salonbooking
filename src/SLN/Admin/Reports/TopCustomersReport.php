<?php

class SLN_Admin_Reports_TopCustomersReport extends SLN_Admin_Reports_AbstractReport {

	protected $type = 'bar';
	public $countOfCustomers = 20;

	protected function getBookingStatuses() {
		return array(
			SLN_Enum_BookingStatus::PAID,
			SLN_Enum_BookingStatus::PAY_LATER,
			SLN_Enum_BookingStatus::CONFIRMED,
		);
	}

	protected function processBookings($day = null, $month_num = null, $year = null, $hour = null) {

		$ret = array();
		$ret['title'] = __('Top customers', 'salon-booking-system');
		$ret['subtitle'] = '';

		$ret['labels']['x'] = array(
				array(
						'label'  => sprintf(__('Earnings (%s)', 'salon-booking-system'), $this->getCurrencyString()),
						'type'   => 'number',
						'format_axis' => array(
								'pattern' => '####.##'.$this->getCurrencySymbol(),
						),
						'format_data' => array(
								'pattern' => '####.##'.$this->getCurrencySymbol(),
						),
				),
				array(
						'label' => __('Bookings', 'salon-booking-system'),
						'type'  => 'number',
				),
		);
		$ret['labels']['y'] = array(
				array(
						'label' => '',
						'type'  => 'string',
				),
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

					$ret['data'][$user_id][1] += $booking->getAmount();
					$ret['data'][$user_id][2] ++;
				}
			}
		}

		uasort($ret['data'], array($this, 'sort'));

		$ret['data'] = array_slice($ret['data'], 0, $this->countOfCustomers);

		if (empty($ret['data'])) {
			$ret['data'][] = array(__('No customers bookings', 'salon-booking-system'),0,0);
		}

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