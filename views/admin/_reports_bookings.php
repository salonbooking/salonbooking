<div class="sln-box sln-box--main">
	<h2 class="sln-box-title"><?php _e('Reports','salon-booking-system') ?></h2>
	<div class="row">
		<?php
		$report = new SLN_Admin_Reports_Report($plugin, $_GET);

		$dates = $report->getReportDates();

		// Determine graph options
		switch ($dates['range']) :
			case 'today' :
			case 'yesterday' :
				$day_by_day = true;
				break;
			case 'last_year' :
			case 'this_year' :
			case 'last_quarter' :
			case 'this_quarter' :
				$day_by_day = false;
				break;
			case 'other' :
				if ($dates['m_end'] - $dates['m_start'] >= 2 || ($dates['year_end'] > $dates['year'] && ($dates['m_start'] - $dates['m_end']) != 11)) {
					$day_by_day = false;
				} else {
					$day_by_day = true;
				}
				break;
			default:
				$day_by_day = true;
				break;
		endswitch;

		$earnings_totals = array(
				'all'                                   => 0.00,
				SLN_Enum_BookingStatus::PAID            => 0.00,
				SLN_Enum_BookingStatus::PAY_LATER       => 0.00,
				SLN_Enum_BookingStatus::PENDING_PAYMENT => 0.00,
				SLN_Enum_BookingStatus::CANCELED        => 0.00,
		); // Total earnings for time period shown

		$bookings_totals = array(
				'all'                                   => 0,
				SLN_Enum_BookingStatus::PAID            => 0,
				SLN_Enum_BookingStatus::PAY_LATER       => 0,
				SLN_Enum_BookingStatus::PENDING_PAYMENT => 0,
				SLN_Enum_BookingStatus::CANCELED        => 0,
		);    // Total bookings for time period shown

		$earnings_data = array();
		$bookings_data = array();


		if ($dates['range'] == 'today' || $dates['range'] == 'yesterday') {
			// Hour by hour
			$hour  = 1;
			$month = $dates['m_start'];
			while ($hour <= 23) {

				$bookings = $report->getCountOfBookingsByDate($dates['day'], $month, $dates['year'], $hour);
				$earnings = $report->getBookingEarningsByDate($dates['day'], $month, $dates['year'], $hour);

				foreach($bookings as $k => $v) {
					$bookings_totals[$k] += $v;
				}
				foreach($earnings as $k => $v) {
					$earnings_totals[$k] += $v;
				}

				$date            = mktime($hour, 0, 0, $month, $dates['day'], $dates['year']) * 1000;
				$bookings_data[] = array($date, $bookings['all']);
				$earnings_data[] = array($date, $earnings['all']);

				$hour ++;
			}

		} elseif ($dates['range'] == 'this_week' || $dates['range'] == 'last_week') {

			$num_of_days = cal_days_in_month(CAL_GREGORIAN, $dates['m_start'], $dates['year']);

			$report_dates = array();
			$i            = 0;
			while ($i <= 6) {

				if (($dates['day'] + $i) <= $num_of_days) {
					$report_dates[ $i ] = array(
							'day'   => (string) ($dates['day'] + $i),
							'month' => $dates['m_start'],
							'year'  => $dates['year'],
					);
				} else {
					$report_dates[ $i ] = array(
							'day'   => (string) $i,
							'month' => $dates['m_end'],
							'year'  => $dates['year_end'],
					);
				}

				$i ++;
			}

			foreach ($report_dates as $report_date) {
				$bookings = $report->getCountOfBookingsByDate($report_date['day'], $report_date['month'], $report_date['year']);
				$earnings = $report->getBookingEarningsByDate($report_date['day'], $report_date['month'], $report_date['year']);

				foreach($bookings as $k => $v) {
					$bookings_totals[$k] += $v;
				}
				foreach($earnings as $k => $v) {
					$earnings_totals[$k] += $v;
				}

				$date            = mktime(0,
								0,
								0,
								$report_date['month'],
								$report_date['day'],
								$report_date['year']) * 1000;
				$bookings_data[] = array($date, $bookings['all']);
				$earnings_data[] = array($date, $earnings['all']);
			}

		} else {

			$y = $dates['year'];

			while ($y <= $dates['year_end']) {

				$last_year = false;

				if ($dates['year'] == $dates['year_end']) {
					$month_start = $dates['m_start'];
					$month_end   = $dates['m_end'];
					$last_year   = true;
				} elseif ($y == $dates['year']) {
					$month_start = $dates['m_start'];
					$month_end   = 12;
				} elseif ($y == $dates['year_end']) {
					$month_start = 1;
					$month_end   = $dates['m_end'];
				} else {
					$month_start = 1;
					$month_end   = 12;
				}

				$i = $month_start;
				while ($i <= $month_end) {

					if ($day_by_day) {

						$d = $dates['day'];

						if ($i == $month_end) {

							$num_of_days = $dates['day_end'];

							if ($month_start < $month_end) {

								$d = 1;

							}

						} else {

							$num_of_days = cal_days_in_month(CAL_GREGORIAN, $i, $y);

						}


						while ($d <= $num_of_days) {

							$bookings = $report->getCountOfBookingsByDate($d, $i, $y);
							$earnings = $report->getBookingEarningsByDate($d, $i, $y);

							foreach($bookings as $k => $v) {
								$bookings_totals[$k] += $v;
							}
							foreach($earnings as $k => $v) {
								$earnings_totals[$k] += $v;
							}

							$date            = mktime(0, 0, 0, $i, $d, $y) * 1000;
							$bookings_data[] = array($date, $bookings['all']);
							$earnings_data[] = array($date, $earnings['all']);
							$d ++;

						}

					} else {

						$bookings = $report->getCountOfBookingsByDate(null, $i, $y);
						$earnings = $report->getBookingEarningsByDate(null, $i, $y);

						foreach($bookings as $k => $v) {
							$bookings_totals[$k] += $v;
						}
						foreach($earnings as $k => $v) {
							$earnings_totals[$k] += $v;
						}

						if ($i == $month_end && $last_year) {

							$num_of_days = cal_days_in_month(CAL_GREGORIAN, $i, $y);

						} else {

							$num_of_days = 1;

						}

						$date            = mktime(0, 0, 0, $i, $num_of_days, $y) * 1000;
						$bookings_data[] = array($date, $bookings['all']);
						$earnings_data[] = array($date, $earnings['all']);

					}

					$i ++;

				}

				$y ++;
			}

		}

		$data = array(
				__('Earnings', 'salon-booking-system') => $earnings_data,
				__('Bookings', 'salon-booking-system') => $bookings_data,
		);

		?>
		<div id="sln-dashboard-widgets-wrap">
			<div class="metabox-holder" style="padding-top: 0;">
				<div class="postbox">
					<h3><span><?php _e('Earnings Over Time','salon-booking-system'); ?></span></h3>

					<div class="inside">
						<?php
						$graphControls = $report->getReportGraphControls();
						echo $graphControls;
						$graph = new SLN_Admin_Reports_Graph($data);
						$graph->set('x_mode', 'time');
						$graph->set('multiple_y_axes', true);
						$graph->display();
						?>

						<p class="sln_graph_totals">
							<?php
							echo sprintf(
									__('Total reservations and earnings for the selected period: <strong>%s | %s</strong>', 'salon-booking-system'),
									$bookings_totals['all'],
									$plugin->format()->money($earnings_totals['all'], false)
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
										$bookings_totals[$status],
										$plugin->format()->money($earnings_totals[$status], false)
								);
								?>
							</p>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php

