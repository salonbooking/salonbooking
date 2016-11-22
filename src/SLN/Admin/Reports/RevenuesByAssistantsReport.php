<?php

class SLN_Admin_Reports_RevenuesByAssistantsReport extends SLN_Admin_Reports_AbstractReport {

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
		$ret['title'] = __('Reservations and revenues by assistants', 'salon-booking-system');
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