<?php

class SLN_Admin_Customers {

	const PAGE = 'salon-customers';

	protected $plugin;
	protected $settings;
	public $settings_page = '';
	public function __construct(SLN_Plugin $plugin)
	{
		$this->plugin   = $plugin;
		$this->settings = $plugin->getSettings();
		add_action('admin_menu', array($this, 'admin_menu'), 10 );
		add_filter('manage_salon_page_salon-customers_columns', array($this, 'users_columns'));

	}

	public function admin_menu()
	{
		$this->settings_page = add_submenu_page(
			'salon',
			__('Salon Customers', 'salon-booking-system'),
			__('Customers', 'salon-booking-system'),
			apply_filters('salonviews/settings/capability', 'manage_salon'),
			'salon-customers',
			array($this, 'show')
		);

	}

	public function show()
	{
		if (!empty($_REQUEST['id'])) {
			$this->show_customer_page();
		}
		else {
			$this->show_customers();
		}
	}

	public function show_customer_page() {

		$user_id = $_REQUEST['id'];


		echo $this->plugin->loadView(
				'admin/_customer',
				array(
						'x' => 'x'
				)
		);
	}

	public function show_customers() {
		if ( empty($_REQUEST) ) {
			$referer = '<input type="hidden" name="wp_http_referer" value="'. esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ) . '" />';
		} elseif ( isset($_REQUEST['wp_http_referer']) ) {
			$redirect = remove_query_arg(array('wp_http_referer', 'updated', 'delete_count'), wp_unslash( $_REQUEST['wp_http_referer'] ) );
			$referer = '<input type="hidden" name="wp_http_referer" value="' . esc_attr($redirect) . '" />';
		} else {
			$redirect = 'admin.php';
			$referer = '';
		}

		$table = new SLN_Admin_Customers_List();

		switch ( $table->current_action() ) {
			default:
				if (!empty($table->current_action())) {
					if ( empty($_REQUEST['users']) ) {
						wp_redirect($redirect);
						exit();
					}
					$update = '';
					wp_redirect(add_query_arg('update', $update, $redirect));
					exit();
				}
				else {
					if ( !empty($_GET['_wp_http_referer']) ) {
						wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce'), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
						exit;
					}
				}
		}

		$table->prepare_items();

		echo $this->plugin->loadView(
				'admin/customers',
				array(
						'x' => 'x',
						'table' => $table,
				)
		);
	}

	public function users_columns($users_columns) {
		$table = new SLN_Admin_Customers_List();

		return $table->get_columns();
	}



	public static function get_edit_customer_link($user_id) {

		return get_admin_url() . "admin.php?page=salon-customers&id=$user_id";
	}
}