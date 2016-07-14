<div class="sln-box sln-box--main">
	<h2 class="sln-box-title"><?php _e('Reports','salon-booking-system') ?></h2>
	<div class="row">
		<?php
		if ( ! function_exists( 'cal_days_in_month' ) ) {
			// Fallback in case the calendar extension is not loaded in PHP
			// Only supports Gregorian calendar
			function cal_days_in_month( $calendar, $month, $year ) {
				return date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
			}
		}

		$report = SLN_Admin_Reports_AbstractReport::createReportObj($_GET);
		$report->build();
?>
	</div>
</div>
<?php

