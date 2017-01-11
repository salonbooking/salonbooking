<?php

class SLN_Admin_Reports_RevenuesReport extends SLN_Admin_Reports_AbstractReport {
	protected $type = 'line';

	protected function getBookingStatuses() {
		return array(
			SLN_Enum_BookingStatus::PAID,
			SLN_Enum_BookingStatus::PAY_LATER,
			SLN_Enum_BookingStatus::CONFIRMED,
			SLN_Enum_BookingStatus::PENDING_PAYMENT,
			SLN_Enum_BookingStatus::CANCELED,
		);
	}

	protected function processBookings() {

		$ret = array();
		$ret['title'] = __('Earnings', 'salon-booking-system');
		$ret['subtitle'] = '';

		$ret['labels']['x'] = array(
				array(
						'label' => '',
						'type'  => 'string',
				),
		);
		$ret['labels']['y'] = array(
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

		$ret['data']   = array();
		$ret['footer'] = array(
				'earnings' => array(
						'all'                                   => 0.0,
						SLN_Enum_BookingStatus::PAID            => 0.0,
						SLN_Enum_BookingStatus::PAY_LATER       => 0.0,
						SLN_Enum_BookingStatus::PENDING_PAYMENT => 0.0,
						SLN_Enum_BookingStatus::CANCELED        => 0.0,
				),
				'bookings' => array(
						'all'                                   => 0,
						SLN_Enum_BookingStatus::PAID            => 0,
						SLN_Enum_BookingStatus::PAY_LATER       => 0,
						SLN_Enum_BookingStatus::PENDING_PAYMENT => 0,
						SLN_Enum_BookingStatus::CANCELED        => 0,
				),
		);

		foreach($this->bookings as $k => $bookings) {
			$earnings = 0.0;
			$count    = 0;
			/** @var SLN_Wrapper_Booking $booking */
			foreach($bookings as $booking) {
				if (in_array($booking->getStatus(), array(SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PENDING_PAYMENT, SLN_Enum_BookingStatus::CANCELED))) {
					$ret['footer']['bookings'][$booking->getStatus()]++;
					$ret['footer']['earnings'][$booking->getStatus()] += $booking->getAmount();
				}

				if (in_array($booking->getStatus(), array(SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::CONFIRMED))) {
					$earnings += $booking->getAmount();
					$count ++;
				}
			}

			$ret['footer']['earnings']['all'] += $earnings;
			$ret['footer']['bookings']['all'] += $count;

			$ret['data'][$k] = array($k, $earnings, $count);
		}

		$this->data = $ret;
	}

	protected function printFooter() {
		$statuses = array(SLN_Enum_BookingStatus::PAID,SLN_Enum_BookingStatus::PAY_LATER,SLN_Enum_BookingStatus::PENDING_PAYMENT,SLN_Enum_BookingStatus::CANCELED);
		?>
		<div class="col-xs-12 col-sm-6 col-md-4 report-statistics">
			<h4><?php _e('Reservations in the selected time range', 'salon-booking-system'); ?></h4>
			<div class="row">
				<div class="col-md-2 text-center"><?php _e('Total', 'salon-booking-system'); ?></div>
				<?php foreach($statuses as $status) : ?>
					<div class="col-md-<?php echo ($status === SLN_Enum_BookingStatus::PAY_LATER || $status === SLN_Enum_BookingStatus::PENDING_PAYMENT ? 3 : 2); ?> text-center">
						<?php echo SLN_Enum_BookingStatus::getLabel($status); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="row">
				<div class="col-md-2 text-center"><?php echo $this->data['footer']['bookings']['all']; ?></div>
				<?php foreach($statuses as $status) : ?>
					<div class="col-md-<?php echo ($status === SLN_Enum_BookingStatus::PAY_LATER || $status === SLN_Enum_BookingStatus::PENDING_PAYMENT ? 3 : 2); ?> text-center">
						<?php echo $this->data['footer']['bookings'][$status]; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="row">
				<div class="col-md-2 text-center"><?php echo $this->plugin->format()->money($this->data['footer']['earnings']['all'], false); ?></div>
				<?php foreach($statuses as $status) : ?>
					<div class="col-md-<?php echo ($status === SLN_Enum_BookingStatus::PAY_LATER || $status === SLN_Enum_BookingStatus::PENDING_PAYMENT ? 3 : 2); ?> text-center">
						<?php echo $this->plugin->format()->money($this->data['footer']['earnings'][$status], false); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

}