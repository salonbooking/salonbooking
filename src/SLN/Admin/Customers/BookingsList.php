<?php


if (!class_exists('WP_Posts_List_Table')) {
	_get_list_table('WP_Posts_List_Table');
}


class SLN_Admin_Customers_BookingsList extends WP_Posts_List_Table {

	protected function get_bulk_actions() {
		return array();
	}

	protected function extra_tablenav($which) {
		return;
	}

	protected function row_actions($actions, $always_visible = false) {
		unset($actions['inline hide-if-no-js']);

		return parent::row_actions($actions, $always_visible);
	}

	public function get_columns() {
		$ret = array(
				'ID'                => __('Booking ID', 'salon-booking-system'),
				'booking_date'      => __('Booking Date', 'salon-booking-system'),
				'booking_status'    => __('Status', 'salon-booking-system'),
				'booking_attendant' => __('Attendant', 'salon-booking-system'),
				'booking_price'     => __('Booking Price', 'salon-booking-system'),
				'booking_services'  => __('Booking Services', 'salon-booking-system'),
				'booking_review'    => __('Booking Review', 'salon-booking-system'),
		);

		return $ret;
	}
}