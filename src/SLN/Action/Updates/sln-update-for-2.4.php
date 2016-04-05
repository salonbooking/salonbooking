<?php
// START UPDATE SERVICES & ATTENDANTS FOR BOOKINGS BEFORE 110 feature
$args = array(
	'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
	'nopaging'   => true,
);
$query = new WP_Query($args);
$ret = array();
$noTimeStatuses = SLN_Enum_BookingStatus::$noTimeStatuses;
foreach ($query->get_posts() as $p) {
	/** @var WP_Post $p */
	$post_id = $p->ID;
	$booking_services_processed = get_post_meta($post_id, '_sln_booking_services_processed', true);
	$booking_services           = get_post_meta($post_id, '_sln_booking_services', true);
	$booking_attendant          = get_post_meta($post_id, '_sln_booking_attendant', true);
	$booking_attendants         = get_post_meta($post_id, '_sln_booking_attendants', true);

	$data = array();

	if (!empty($booking_services_processed)) {
		if (!empty($booking_attendant)) {
			foreach($booking_services as $booking_service) {
				$data[$booking_service['attendant']] = $booking_attendant; // it's not a mistake
			}
		}
		else {
			foreach($booking_services as $booking_service) {
				$data[$booking_service['service']] = $booking_service; // it's not a mistake
			}
		}
	}
	else {
		if (!empty($booking_attendants)) {
			foreach($booking_services as $service) {
				$data[(int)$service] = isset($booking_attendants[$service]) ? $booking_attendants[$service] : '';
			}
		}
		else {
			foreach($booking_services as $service) {
				$data[(int)$service] = $booking_attendant;
			}
		}
	}
	delete_post_meta($post_id, '_sln_booking_attendant');
	delete_post_meta($post_id, '_sln_booking_attendants');

	$date = new SLN_DateTime(get_post_meta($post_id, '_sln_booking_date', true));
	$time = new SLN_DateTime(get_post_meta($post_id, '_sln_booking_time', true));
	$bookingServices = SLN_Wrapper_Booking_Services::build($data, new SLN_DateTime($date->format('Y-m-d').' '.$time->format('H:i')));
	$ret = $bookingServices->toArrayRecursive();

	update_post_meta($post_id, '_sln_booking_services', $ret);
	update_post_meta($post_id, '_sln_booking_services_processed', 1);
}
//wp_reset_query();
//wp_reset_postdata();
// END UPDATE SERVICES & ATTENDANTS FOR BOOKINGS BEFORE 110 feature
