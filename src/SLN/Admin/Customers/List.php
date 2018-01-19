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
	}

	public function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'ID'             => __('Customer ID', 'salon-booking-system'),
			'first_name'     => __('First Name', 'salon-booking-system'),
			'last_name'      => __('Last Name', 'salon-booking-system'),
			'user_email'     => __('E-mail', 'salon-booking-system'),
			'_sln_phone'     => __('Telephone', 'salon-booking-system'),
			'total_bookings' => __('Total Reservations', 'salon-booking-system'),
			'total_amount'   => __('Customer Value', 'salon-booking-system'),
		);

        return apply_filters('sln.admin_customers_list.columns', $columns);
	}

	protected function get_sortable_columns() {
		$c = array(
			'user_email' => 'user_email',
		);

		return $c;
	}

	public function manage_users_custom_column($empty, $column_name, $user_id) {

		$user_object = get_userdata((int) $user_id);
		$customer_object = new SLN_Wrapper_Customer($user_object);

		switch ($column_name) {
			case 'total_bookings':
				$html = $customer_object->getCountOfReservations();
				break;
			case 'total_amount':
				$html = SLN_Plugin::getInstance()->format()->money($customer_object->getAmountOfReservations(), false);
				break;
			case 'first_name':
			case 'last_name':
			case 'ID':
				$link = esc_url( add_query_arg( 'wp_http_referer', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), SLN_Admin_Customers::get_edit_customer_link($user_id) ) );
				$html = '<strong><a href="' . $link . '">' . $user_object->get($column_name) . '</a></strong><br />';
				break;
			default:
				$html = $user_object->get($column_name);
		}

		return $html;
	}

	public function prepare_items() {
		global $role, $usersearch, $wpdb;

		$args = array();

		$role   = '%'.SLN_Plugin::USER_ROLE_CUSTOMER.'%';
		$args[] = $role;

		$join   = '';
		$where  = '';

		$usersearch = !empty($_REQUEST['s']) ? '%'.wp_unslash(trim($_REQUEST['s'])).'%' : '';
		if (!empty($usersearch)) {
			$join = "INNER JOIN {$wpdb->prefix}usermeta AS usermeta2 ON ( users.ID = usermeta2.user_id )
						INNER JOIN {$wpdb->prefix}usermeta AS usermeta3 ON ( users.ID = usermeta3.user_id )";

			$where = "AND
				    (
				        usermeta2.meta_key = 'first_name' AND usermeta2.meta_value LIKE %s
				        OR
				        usermeta3.meta_key = 'last_name' AND usermeta3.meta_value LIKE %s
				        OR
				        users.user_email LIKE %s
				    )";

			$args[] = $usersearch;
			$args[] = $usersearch;
			$args[] = $usersearch;
		}

		$orderby = !empty($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'ID';
		$order   = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'ASC';

		$per_page       = ($this->is_site_users) ? 'site_users_network_per_page' : 'users_per_page';
		$users_per_page = $this->get_items_per_page($per_page);
		$paged  = $this->get_pagenum();
		$limit  = $users_per_page;
		$offset = ($paged-1) * $users_per_page;

		$args[] = $offset;
		$args[] = $limit;

		$sqlSelect = "SELECT DISTINCT ID

FROM {$wpdb->prefix}users AS users
INNER JOIN {$wpdb->prefix}usermeta AS usermeta1 ON ( users.ID = usermeta1.user_id )
$join

WHERE
    ( usermeta1.meta_key = '{$wpdb->prefix}capabilities' AND usermeta1.meta_value LIKE %s )
    $where
ORDER BY ".$wpdb->_real_escape($orderby)." ".$wpdb->_real_escape($order)." LIMIT %d, %d";

		$querySelect = $wpdb->prepare(
				$sqlSelect,
				$args
		);
		$users = $wpdb->get_results($querySelect);
		$items = array();

		foreach($users as $user) {
			$items[$user->ID] = get_user_by('ID', $user->ID);
		}

		$this->items = $items;

		$sqlCount = str_replace(array('DISTINCT ID', 'LIMIT %d, %d'), array('COUNT(DISTINCT ID)', ''), $sqlSelect);
		$queryCount = $wpdb->prepare(
				$sqlCount,
				$args
		);

		$this->set_pagination_args( array(
			'total_items' => $wpdb->get_var($queryCount),
			'per_page'    => $users_per_page,
		) );
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
		if ($which === 'top') {
			?>
			<div class="alignleft actions">
				<?php $this->search_box(__('Search customers', 'salon-booking-system'), 'customer'); ?>
			</div>
			<?php
		}
	}
}