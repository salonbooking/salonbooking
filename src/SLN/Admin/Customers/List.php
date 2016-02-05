<?php

if ( ! class_exists( 'WP_Users_List_Table' ) ) {
	_get_list_table( 'WP_Users_List_Table' );
}


class SLN_Admin_Customers_List extends WP_Users_List_Table {


	/**
	 * SLN_Admin_Customers_List constructor.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args );

		add_filter( 'manage_users_custom_column', array( $this, 'manage_users_custom_column' ), 10, 3 );
		add_filter( 'users_list_table_query_args', array( $this, 'users_list_table_query_args' ) );
	}

	public function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'id'             => __( 'Customer ID' ),
			'first_name'     => __( 'First Name' ),
			'last_name'      => __( 'Last Name' ),
			'email'          => __( 'E-mail' ),
			'billing_phone'  => __( 'Telephone' ),
			'total_bookings' => __( 'Total Reservations' ),
			'total_amount'   => __( 'Customer Value' ),
		);

		return $columns;
	}

	public function manage_users_custom_column( $empty, $column_name, $user_id ) {

		$user_object = get_userdata( (int) $user_id );

		switch ( $column_name ) {
			case 'total_bookings':
				$html = 'qwe';
				break;
			case 'total_amount':
				$html = 'qwe';
				break;
			default:
				$html = $user_object->get( $column_name );
		}


		return $html;
	}

	public function users_list_table_query_args( $args ) {

		$args['role'] = SLN_Plugin::USER_ROLE_CUSTOMER;

		return $args;
	}

	protected function row_actions( $actions, $always_visible = false ) {

		if (isset($actions['edit'])) {

			if (preg_match('/user_id=(\d+)\&/s', $actions['edit'], $matches)) {
				$user_id = isset($matches[1]) ? $matches[1] : '';
				$edit_link = esc_url( add_query_arg( 'wp_http_referer', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), SLN_Admin_Customers::get_edit_customer_link($user_id) ) );
				$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			}
		}
		return parent::row_actions( $actions, $always_visible );
	}

	protected function extra_tablenav( $which ) {
		echo '';
	}
}