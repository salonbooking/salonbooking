<?php

// START DOWNGRADE SERVICES & ATTENDANTS FOR BOOKINGS
$args = array(
	'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
	'nopaging'   => true,
);
$query = new WP_Query($args);
foreach ($query->get_posts() as $p) {
	/** @var WP_Post $p */
	$post_id = $p->ID;
	$booking_services_processed = get_post_meta($post_id, '_sln_booking_services_processed', true);
	$booking_services           = get_post_meta($post_id, '_sln_booking_services', true);

	$attendants = array();
	$services   = array();

	if (!empty($booking_services_processed)) {
		foreach($booking_services as $booking_service) {
			$attendants[$booking_service['service']] = $booking_service['attendant'];
			$services[]                              = $booking_service['service'];
		}
		delete_post_meta($post_id, '_sln_booking_services_processed');

		update_post_meta($post_id, '_sln_booking_services', $services);
		update_post_meta($post_id, '_sln_booking_attendants', $attendants);
	}
}
wp_reset_query();
wp_reset_postdata();
// END DOWNGRADE SERVICES & ATTENDANTS FOR BOOKINGS