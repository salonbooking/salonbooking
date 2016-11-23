<?php

class SLN_Admin_Reports_RevenuesByServicesReport extends SLN_Admin_Reports_AbstractReport {

	protected $type = 'bar';

	protected function getBookingStatuses() {
		return array(
			SLN_Enum_BookingStatus::PAID,
			SLN_Enum_BookingStatus::PAY_LATER,
			SLN_Enum_BookingStatus::CONFIRMED,
		);
	}

	protected function processBookings($day = null, $month_num = null, $year = null, $hour = null) {

		$ret = array();
		$ret['title'] = __('Reservations and revenues by services', 'salon-booking-system');
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

		$sRepo =  $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
		$allServices = $sRepo->getAll();
		foreach($allServices as $service) {
			$ret['data'][$service->getId()] = array($service->getName(), 0.0, 0);
		}

		foreach($this->bookings as $k => $bookings) {
			/** @var SLN_Wrapper_Booking $booking */
			foreach($bookings as $booking) {
				foreach($booking->getBookingServices()->getItems() as $bookingService) {
					if (array_key_exists($bookingService->getService()->getId(), $ret['data'])) {
						$ret['data'][$bookingService->getService()->getId()][1] += $bookingService->getPrice();
						$ret['data'][$bookingService->getService()->getId()][2] ++;
					}
				}
			}
		}

		$this->data = $ret;
	}
}