<?php
// Exit if accessed directly
if (! defined('ABSPATH')) exit;

class SLN_Admin_Reports_Report {

	protected $plugin;
	protected $attr;

	function __construct(SLN_Plugin $plugin, array $attr = array())
	{
		$this->plugin = $plugin;
		$this->attr = $attr;
		if (!isset($this->attr['range'])) {
			$this->attr['range'] = 'this_month';
		}
		if (!isset($this->attr['year'])) {
			$this->attr['year'] = current_time('Y');
		}
		if (!isset($this->attr['year_end'])) {
			$this->attr['year_end'] = current_time('Y');
		}
		if (!isset($this->attr['m_start'])) {
			$this->attr['m_start'] = 1;
		}
		if (!isset($this->attr['m_end'])) {
			$this->attr['m_end'] = 12;
		}
		if (!isset($this->attr['day'])) {
			$this->attr['day'] = 1;
		}
		if (!isset($this->attr['day_end'])) {
			$this->attr['day_end'] = cal_days_in_month(CAL_GREGORIAN, $this->attr['m_end'], $this->attr['year']);
		}
		if (!isset($this->attr['view']) || !in_array($this->attr['view'], array_keys($this->getReportViews()))) {
			$views = $this->getReportViews();
			$this->attr['view'] = reset($views);
		}
	}

	public function getReportDates() {
		$current_time = current_time('timestamp');

		$dates = $this->attr;

		// Modify dates based on predefined ranges
		switch ($dates['range']) :

			case 'this_month' :
				$dates['m_start']  = current_time('n');
				$dates['m_end']    = current_time('n');
				$dates['day']      = 1;
				$dates['day_end']  = cal_days_in_month(CAL_GREGORIAN, $dates['m_end'], $dates['year']);
				$dates['year']     = current_time('Y');
				$dates['year_end'] = current_time('Y');
				break;

			case 'last_month' :
				if(current_time('n') == 1) {
					$dates['m_start']  = 12;
					$dates['m_end']    = 12;
					$dates['year']     = current_time('Y') - 1;
					$dates['year_end'] = current_time('Y') - 1;
				} else {
					$dates['m_start']  = current_time('n') - 1;
					$dates['m_end']    = current_time('n') - 1;
					$dates['year_end'] = $dates['year'];
				}
				$dates['day_end'] = cal_days_in_month(CAL_GREGORIAN, $dates['m_end'], $dates['year']);
				break;

			case 'today' :
				$dates['day']     = current_time('d');
				$dates['m_start'] = current_time('n');
				$dates['m_end']   = current_time('n');
				$dates['year']    = current_time('Y');
				break;

			case 'yesterday' :

				$year  = current_time('Y');
				$month = current_time('n');
				$day   = current_time('d');

				if ($month == 1 && $day == 1) {

					$year  -= 1;
					$month = 12;
					$day   = cal_days_in_month(CAL_GREGORIAN, $month, $year);

				} elseif ($month > 1 && $day == 1) {

					$month -= 1;
					$day   = cal_days_in_month(CAL_GREGORIAN, $month, $year);

				} else {

					$day -= 1;

				}

				$dates['day']       = $day;
				$dates['m_start']   = $month;
				$dates['m_end']     = $month;
				$dates['year']      = $year;
				$dates['year_end']  = $year;
				break;

			case 'this_week' :
			case 'last_week' :
				$base_time = $dates['range'] === 'this_week' ? current_time('mysql') : date('Y-m-d h:i:s', current_time('timestamp') - WEEK_IN_SECONDS);
				$start_end = get_weekstartend($base_time, get_option('start_of_week'));

				$dates['day']      = date('d', $start_end['start']);
				$dates['m_start']  = date('n', $start_end['start']);
				$dates['year']     = date('Y', $start_end['start']);

				$dates['day_end']  = date('d', $start_end['end']);
				$dates['m_end']    = date('n', $start_end['end']);
				$dates['year_end'] = date('Y', $start_end['end']);
				break;

			case 'this_quarter' :
				$month_now = current_time('n');

				if ($month_now <= 3) {

					$dates['m_start'] = 1;
					$dates['m_end']   = 3;
					$dates['year_end'] = $dates['year']    = current_time('Y');

				} else if ($month_now <= 6) {

					$dates['m_start'] = 4;
					$dates['m_end']   = 6;
					$dates['year_end'] = $dates['year']    = current_time('Y');

				} else if ($month_now <= 9) {

					$dates['m_start'] = 7;
					$dates['m_end']   = 9;
					$dates['year_end'] = $dates['year']    = current_time('Y');

				} else {

					$dates['m_start']  = 10;
					$dates['m_end']    = 12;
					$dates['year_end'] = $dates['year']     = current_time('Y');
				}
				break;

			case 'last_quarter' :
				$month_now = current_time('n');

				if ($month_now <= 3) {

					$dates['m_start']  = 10;
					$dates['m_end']    = 12;
					$dates['year']     = current_time('Y') - 1; // Previous year
					$dates['year_end'] = current_time('Y') - 1; // Previous year

				} else if ($month_now <= 6) {

					$dates['m_start'] = 1;
					$dates['m_end']   = 3;
					$dates['year_end'] = $dates['year']    = current_time('Y');

				} else if ($month_now <= 9) {

					$dates['m_start'] = 4;
					$dates['m_end']   = 6;
					$dates['year_end'] = $dates['year']    = current_time('Y');

				} else {

					$dates['m_start'] = 7;
					$dates['m_end']   = 9;
					$dates['year_end'] = $dates['year']    = current_time('Y');

				}
				break;

			case 'this_year' :
				$dates['m_start'] = 1;
				$dates['m_end']   = 12;
				$dates['year_end'] = $dates['year']    = current_time('Y');
				break;

			case 'last_year' :
				$dates['m_start']  = 1;
				$dates['m_end']    = 12;
				$dates['year_end'] = $dates['year']     = current_time('Y') - 1;
				break;

		endswitch;
		return $dates;
	}

	public function getReportGraphControls() {
		$date_options = array(
			'today'        => __('Today', 'salon-booking-system'),
			'yesterday'    => __('Yesterday', 'salon-booking-system'),
			'this_week'    => __('This Week', 'salon-booking-system'),
			'last_week'    => __('Last Week', 'salon-booking-system'),
			'this_month'   => __('This Month', 'salon-booking-system'),
			'last_month'   => __('Last Month', 'salon-booking-system'),
			'this_quarter' => __('This Quarter', 'salon-booking-system'),
			'last_quarter' => __('Last Quarter', 'salon-booking-system'),
			'this_year'    => __('This Year', 'salon-booking-system'),
			'last_year'    => __('Last Year', 'salon-booking-system'),
			'other'        => __('Custom', 'salon-booking-system')
		);

		$dates   = $this->getReportDates();
		$display = $dates['range'] == 'other' ? '' : 'style="display:none;"';
		$view    = $this->getReportingView();

		if(empty($dates['day_end'])) {
			$dates['day_end'] = cal_days_in_month(CAL_GREGORIAN, current_time('n'), current_time('Y'));
		}

		ob_start();
		?>
		<form id="sln-graphs-filter" method="get">
			<div class="tablenav top">
				<div class="alignleft actions">

					<input type="hidden" name="page" value="salon-reports"/>
					<input type="hidden" name="view" value="<?php echo esc_attr($view); ?>"/>

					<select id="sln-graphs-date-options" name="range">
						<?php foreach ($date_options as $key => $option) : ?>
							<option value="<?php echo esc_attr($key); ?>"<?php selected($key, $dates['range']); ?>><?php echo esc_html($option); ?></option>
						<?php endforeach; ?>
					</select>

					<div id="sln-date-range-options" <?php echo $display; ?>>
						<span><?php _e('From', 'salon-booking-system'); ?>&nbsp;</span>
						<select id="sln-graphs-month-start" name="m_start">
							<?php for ($i = 1; $i <= 12; $i++) : ?>
								<option value="<?php echo absint($i); ?>" <?php selected($i, $dates['m_start']); ?>><?php echo $this->monthNumToName($i); ?></option>
							<?php endfor; ?>
						</select>
						<select id="sln-graphs-day-start" name="day">
							<?php for ($i = 1; $i <= 31; $i++) : ?>
								<option value="<?php echo absint($i); ?>" <?php selected($i, $dates['day']); ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
						<select id="sln-graphs-year-start" name="year">
							<?php for ($i = 2007; $i <= current_time('Y'); $i++) : ?>
								<option value="<?php echo absint($i); ?>" <?php selected($i, $dates['year']); ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
						<span><?php _e('To', 'salon-booking-system'); ?>&nbsp;</span>
						<select id="sln-graphs-month-end" name="m_end">
							<?php for ($i = 1; $i <= 12; $i++) : ?>
								<option value="<?php echo absint($i); ?>" <?php selected($i, $dates['m_end']); ?>><?php echo $this->monthNumToName($i); ?></option>
							<?php endfor; ?>
						</select>
						<select id="sln-graphs-day-end" name="day_end">
							<?php for ($i = 1; $i <= 31; $i++) : ?>
								<option value="<?php echo absint($i); ?>" <?php selected($i, $dates['day_end']); ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
						<select id="sln-graphs-year-end" name="year_end">
							<?php for ($i = 2007; $i <= current_time('Y'); $i++) : ?>
								<option value="<?php echo absint($i); ?>" <?php selected($i, $dates['year_end']); ?>><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
					</div>

					<div class="sln-graph-filter-submit graph-option-section">
						<input type="hidden" name="sln_action" value="filter_reports" />
						<input type="submit" class="button-secondary" value="<?php _e('Filter', 'salon-booking-system'); ?>"/>
					</div>
				</div>
			</div>
		</form>
		<script>
			// Show hide extended date options
			jQuery(window).ready(function() {
				jQuery( '#sln-graphs-date-options' ).change( function() {
					var $this = jQuery(this);
					date_range_options = jQuery( '#sln-date-range-options' );

					if ( 'other' === $this.val() ) {
						date_range_options.show();
					} else {
						date_range_options.hide();
					}
				});
			})
		</script>
		<?php

		return ob_get_clean();
	}

	protected function monthNumToName($n) {
		$timestamp = mktime(0, 0, 0, $n, 1, 2005);

		return date_i18n("M", $timestamp);
	}


	protected function getReportViews() {
		$views = array(
			'earnings'   => __('Earnings', 'salon-booking-system'),
		);

		return $views;
	}

	protected function getReportingView() {
		return $this->attr['view'];
	}

	public function getBookingsSalesByDate(&$counts, &$earnings, $day = null, $month_num = null, $year = null, $hour = null) {

		$year = $year ? $year : '';
		$month_num = ($month_num ? ($month_num >= 10 ?  $month_num : '0'.$month_num) : '');
		$day = ($day ? (10 <= $day ?  (int) $day : '0'.(int)$day) : '');

//		echo "$year-$month_num-$day<br>";
		
		$args = array(
			'post_type'      => SLN_Plugin::POST_TYPE_BOOKING,
			'nopaging'       => true,
			'meta_query' => array(
				array(
					'key' => '_sln_booking_date',
					'value' => "$year-$month_num-$day",
					'compare' => 'LIKE',
					'type' => 'STRING',
				),

			)
		);
		if ($hour) {
			$hour = ($hour >= 10 ? "$hour:" : "0$hour:");
			$args['meta_query'][] = array(
				'key' => '_sln_booking_time',
				'value' => $hour,
				'compare' => 'LIKE',
				'type' => 'STRING',
			);
		}

		$counts = $earnings = array(
				'all'                                   => 0,
				SLN_Enum_BookingStatus::PAID            => 0,
				SLN_Enum_BookingStatus::PAY_LATER       => 0,
				SLN_Enum_BookingStatus::PENDING_PAYMENT => 0,
				SLN_Enum_BookingStatus::CANCELED        => 0,
		);
		
		$bookings = new WP_Query($args);
		foreach($bookings->get_posts() as $p) {
			$booking = SLN_Plugin::getInstance()->createBooking($p->ID);
			$earnings['all'] += $booking->getAmount();
			if (in_array($booking->getStatus(), array(SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PENDING_PAYMENT, SLN_Enum_BookingStatus::CANCELED))) {
				$counts[$booking->getStatus()]++;
				$earnings[$booking->getStatus()] += $booking->getAmount();
			}
		}

		$counts['all'] = (int) $bookings->post_count;
	}
}