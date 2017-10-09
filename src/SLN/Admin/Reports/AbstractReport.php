<?php

abstract class SLN_Admin_Reports_AbstractReport {
	protected $type;
	protected $plugin;
	protected $attr;
	protected $bookings;
	protected $data;

	protected abstract function processBookings();

	function __construct(SLN_Plugin $plugin, array $attr = array())
	{
		$this->plugin = $plugin;
		$this->attr = $attr;
		if (!isset($this->attr['range'])) {
			$this->attr['range'] = 'last_month';
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

	}

	public function build() {
		$dates = $this->getReportDates();

		// Determine graph options
		switch ($dates['range']) :
			case 'last_quarter' :
			case 'this_quarter' :
			case 'last_year' :
			case 'this_year' :
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

		$data = array();


		if ($dates['range'] == 'today' || $dates['range'] == 'yesterday') {
			// Hour by hour
			$hour  = 1;
			$month = $dates['m_start'];
			while ($hour <= 23) {

				$this->getDataByDate($dates['day'], $month, $dates['year'], $hour);

				$date         = mktime($hour, 0, 0, $month, $dates['day'], $dates['year']) * 1000;

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
				$this->getDataByDate($report_date['day'], $report_date['month'], $report_date['year']);

				$date         = mktime(0, 0, 0, $report_date['month'], $report_date['day'], $report_date['year']) * 1000;
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

							$this->getDataByDate($d, $i, $y);

							$date         = mktime(0, 0, 0, $i, $d, $y) * 1000;

							$d ++;
						}

					} else {

						$this->getDataByDate(null, $i, $y);

						if ($i == $month_end && $last_year) {
							$num_of_days = cal_days_in_month(CAL_GREGORIAN, $i, $y);
						} else {
							$num_of_days = 1;
						}
						$date         = mktime(0, 0, 0, $i, $num_of_days, $y) * 1000;
					}

					$i ++;

				}

				$y ++;
			}

		}

		$this->processBookings();

?>
	<form id="sln-graphs-filter" method="get">
        <?php do_action('sln.template.report.filters'); ?>
	<select name="view">
		<?php foreach(self::getReportViews() as $k => $v): ?>
			<option value="<?php echo $k ?>" <?php echo selected($k, $this->attr['view'], false) ?>><?php echo $v ?></option>
		<?php endforeach; ?>
	</select>
		<script>
			jQuery(document).ready(function() {
				jQuery('[name=view]').change(function() {
					jQuery('#sln-graphs-filter').submit();
				});
			});
		</script>

		<div id="sln-dashboard-widgets-wrap">
			<div class="metabox-holder" style="padding-top: 0;">
				<div class="postbox">
					<div class="inside">
						<?php
						$graphControls = $this->getReportGraphControls();
						echo $graphControls;

						$graph = new SLN_Admin_Reports_GoogleGraph($this->data);

						$method = 'display_' . $this->type;
						$graph->$method();
						?>
					</div>
				</div>
			</div>
		</div>
<?php
		$this->printFooter();
?>
	</form>
<?php
	}

	protected function printFooter() {

	}

	/**
	 * @return array
	 */
	protected function getBookingStatuses() {
		return array_keys(SLN_Enum_BookingStatus::toArray());
	}

	protected function getDataByDate($day = null, $month_num = null, $year = null, $hour = null) {

		$year = $year ? $year : '';
		$month_num = ($month_num ? ($month_num >= 10 ?  $month_num : '0'.$month_num) : '');
		$day = ($day ? (10 <= $day ?  (int) $day : '0'.(int)$day) : '');

		$args = array(
				'post_type'      => SLN_Plugin::POST_TYPE_BOOKING,
				'post_status'    => $this->getBookingStatuses(),
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



		$bookings = array();
        $args = apply_filters('sln.action.report.criteria', $args);

		$posts = new WP_Query($args);
		foreach($posts->get_posts() as $p) {
			$booking = SLN_Plugin::getInstance()->createBooking($p->ID);
			$bookings[$p->ID] = $booking;
		}

		$format = 'M';
		if ($day) {
			$format = 'd ' . $format;
		} else {
			$format .= ' Y';
		}
		if ($hour) {
			$format .= ' H:i';
		}

		$day = $day ? $day : 1;
		$hour = $hour ? $hour . "00" : "";

		$datetime = date($format, strtotime("$year-$month_num-$day $hour"));
		$this->bookings[$datetime] = $bookings;
	}

	protected function getReportDates() {
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

	protected function getReportGraphControls() {
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
		$display = $dates['range'] == 'other' ? 'style="display:inline;"' : 'style="display:none;"';

		if(empty($dates['day_end'])) {
			$dates['day_end'] = cal_days_in_month(CAL_GREGORIAN, current_time('n'), current_time('Y'));
		}

		ob_start();
		?>
			<div class="tablenav top">
				<div class="alignleft actions">

					<input type="hidden" name="page" value="salon-reports"/>

					<select id="sln-graphs-date-options" name="range">
						<?php foreach ($date_options as $key => $option) : ?>
							<option value="<?php echo esc_attr($key); ?>"<?php selected($key, $dates['range']); ?>><?php _e($option); ?></option>
						<?php endforeach; ?>
					</select>

					<div id="sln-date-range-options" <?php echo $display; ?>>
						<span style="float: left;"><?php _e('From', 'salon-booking-system'); ?>&nbsp;</span>
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
						<span style="float: left;"><?php _e('To', 'salon-booking-system'); ?>&nbsp;</span>
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

					<div class="sln-graph-filter-submit graph-option-section"style="display: inline">
						<input type="hidden" name="sln_action" value="filter_reports" />
						<input type="submit" class="button-secondary" value="<?php _e('Filter', 'salon-booking-system'); ?>" />
					</div>
				</div>
			</div>
		<br>
		<script>
			// Show hide extended date options
			jQuery(window).ready(function() {
				jQuery( '#sln-graphs-date-options' ).change( function() {
					var $this = jQuery(this);
					date_range_options = jQuery( '#sln-date-range-options' );

					if ( 'other' === $this.val() ) {
						date_range_options.css('display', 'inline');
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

	protected function getCurrencyString() {
		$currency = $this->plugin->getSettings()->getCurrency();
		return $currency . ' ' . SLN_Currency::getSymbolAsIs($currency) . '';
	}

	protected function getCurrencySymbol() {
		return SLN_Currency::getSymbolAsIs($this->plugin->getSettings()->getCurrency());
	}

	protected function getReportingView() {
		return $this->attr['view'];
	}

	/**
	 * @param $attr
	 *
	 * @return SLN_Admin_Reports_AbstractReport
	 */
	public static function createReportObj($attr) {
		if (!isset($attr['view']) || !in_array($attr['view'], array_keys(self::getReportViews()))) {
			$views = self::getReportViews();
			reset($views);
			$attr['view'] = key($views);
		}

		$class = 'SLN_Admin_Reports_' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attr['view']))) . 'Report';
		return new $class(SLN_Plugin::getInstance(), $attr);
	}

	protected static function getReportViews() {
		$views = array(
			'revenues'               => __('Reservations and revenues', 'salon-booking-system'),
			'revenues_by_services'   => __('Reservations and revenues by services', 'salon-booking-system'),
			'revenues_by_assistants' => __('Reservations and revenues by assistants', 'salon-booking-system'),
			'top_customers'          => __('Top customers', 'salon-booking-system'),
		);

		return $views;
	}

}