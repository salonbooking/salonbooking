<?php

if (!class_exists('WP_Users_List_Table')) {
	_get_list_table('WP_Users_List_Table');
}


class SLN_Admin_Customers_List extends WP_Users_List_Table {


	/**
	 * SLN_Admin_Customers_List constructor.
	 */
	public function __construct($args = array()) {
		parent::__construct($args);

		add_filter('manage_users_custom_column', array($this, 'manage_users_custom_column'), 10, 3);
		add_filter('users_list_table_query_args', array($this, 'users_list_table_query_args'));
	}

	public function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'id'             => __('Customer ID', 'salon-booking-system'),
			'first_name'     => __('First Name', 'salon-booking-system'),
			'last_name'      => __('Last Name', 'salon-booking-system'),
			'email'          => __('E-mail', 'salon-booking-system'),
			'billing_phone'  => __('Telephone', 'salon-booking-system'),
			'total_bookings' => __('Total Reservations', 'salon-booking-system'),
			'total_amount'   => __('Customer Value', 'salon-booking-system'),
		);

		return $columns;
	}

	public function manage_users_custom_column($empty, $column_name, $user_id) {

		$user_object = get_userdata((int) $user_id);
		$customer_object = new SLN_Wrapper_Customer($user_object);

		switch ($column_name) {
			case 'total_bookings':
				$html = $customer_object->getCountOfReservations();
				break;
			case 'total_amount':
				$html = SLN_Plugin::getInstance()->getSettings()->getCurrencySymbol().' '.$customer_object->getCustomerValue();
				break;
			case 'id':
				$link = esc_url( add_query_arg( 'wp_http_referer', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), SLN_Admin_Customers::get_edit_customer_link($user_id) ) );
				$html = '<strong><a href="' . $link . '">' . $user_object->get($column_name) . '</a></strong><br />';
				break;
			default:
				$html = $user_object->get($column_name);
		}

		return $html;
	}

	public function users_list_table_query_args($args) {
		$args['role'] = SLN_Plugin::USER_ROLE_CUSTOMER;

		return $args;
	}

	protected function row_actions($actions, $always_visible = false) {

		if (isset($actions['edit'])) {

			if (preg_match('/user_id=(\d+)\&/s', $actions['edit'], $matches)) {
				$user_id = isset($matches[1]) ? $matches[1] : '';
				$edit_link = esc_url(SLN_Admin_Customers::get_edit_customer_link($user_id)) ;
				$actions['edit'] = '<a href="' . $edit_link . '">' . __('Edit', 'salon-booking-system') . '</a>';
			}
		}

		return parent::row_actions($actions, $always_visible);
	}

	protected function extra_tablenav($which) {
		echo '';
	}
}