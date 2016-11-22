<?php

class SLN_Admin_Reports_RevenuesReport extends SLN_Admin_Reports_AbstractReport {
	protected $type = 'line';

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
		?>
		<p class="sln_graph_totals">
			<?php
			echo sprintf(
					__('Total reservations and earnings for the selected period: <strong>%s | %s</strong>', 'salon-booking-system'),
					$this->data['footer']['bookings']['all'],
					$this->plugin->format()->money($this->data['footer']['earnings']['all'], false)
			);
			?>
		</p>


		<?php
		$statuses = array(SLN_Enum_BookingStatus::PAID,SLN_Enum_BookingStatus::PAY_LATER,SLN_Enum_BookingStatus::PENDING_PAYMENT,SLN_Enum_BookingStatus::CANCELED);
		foreach($statuses as $status) : ?>
			<p class="sln_graph_notes">
				<?php
				echo sprintf(
						__("Total '%s' reservations for the selected period: <strong>%s | %s</strong>", 'salon-booking-system'),
						SLN_Enum_BookingStatus::getLabel($status),
						$this->data['footer']['bookings'][$status],
						$this->plugin->format()->money($this->data['footer']['earnings'][$status], false)
				);
				?>
			</p>
		<?php endforeach; ?>
<?php
	}

}