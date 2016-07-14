<?php

class SLN_Admin_Reports_RevenuesByAssistantsReport extends SLN_Admin_Reports_AbstractReport {

	protected $type = 'bar';

	protected function processBookings($day = null, $month_num = null, $year = null, $hour = null) {

		$ret = array();
		$ret['title'] = __('Reservations and revenues by assistants', 'salon-booking-system');
		$ret['subtitle'] = '';

		$ret['labels']['x'] = array(
				__('Earnings', 'salon-booking-system') => 'number',
				__('Bookings', 'salon-booking-system') => 'number',
		);
		$ret['labels']['y'] = array(
				''                                     => 'string',
		);

		$sRepo =  $this->plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
		$allAttendants = $sRepo->getAll();
		foreach($allAttendants as $attendant) {
			$ret['data'][$attendant->getId()] = array($attendant->getName(), 0.0, 0);
		}


		foreach($this->bookings as $k => $bookings) {
			/** @var SLN_Wrapper_Booking $booking */
			foreach($bookings as $booking) {
				$attWasAdded = array();
				foreach($booking->getBookingServices()->getItems() as $bookingService) {
					if ($bookingService->getAttendant()) {
						if (!in_array($bookingService->getAttendant()->getId(), $attWasAdded)) {
							$ret['data'][$bookingService->getAttendant()->getId()][2] ++;
							$attWasAdded[] = $bookingService->getAttendant()->getId();
						}
						$ret['data'][$bookingService->getAttendant()->getId()][1] += $bookingService->getPrice();
					}
				}
			}
		}

		$this->data = $ret;
	}
}