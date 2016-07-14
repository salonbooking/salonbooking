<?php

class SLN_Admin_Reports_RevenuesByServicesReport extends SLN_Admin_Reports_AbstractReport {

	protected $type = 'bar';

	protected function processBookings($day = null, $month_num = null, $year = null, $hour = null) {

		$ret = array();
		$ret['title'] = __('Reservations and revenues by services', 'salon-booking-system');
		$ret['subtitle'] = '';

		$ret['labels']['x'] = array(
				__('Earnings', 'salon-booking-system') => 'number',
				__('Bookings', 'salon-booking-system') => 'number',
		);
		$ret['labels']['y'] = array(
				''                                     => 'string',
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
					$ret['data'][$bookingService->getService()->getId()][1] += $bookingService->getPrice();
					$ret['data'][$bookingService->getService()->getId()][2] ++;
				}
			}
		}

		$this->data = $ret;
	}
}