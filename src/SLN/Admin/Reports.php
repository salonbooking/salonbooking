<?php

class SLN_Admin_Reports {

	const PAGE = 'salon-reports';

	protected $plugin;
	protected $settings;
	public $settings_page = '';

	public function __construct( SLN_Plugin $plugin ) {
		$this->plugin   = $plugin;
		$this->settings = $plugin->getSettings();

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		$this->settings_page = add_submenu_page(
			'salon',
			__( 'Salon Reports', 'salon-booking-system' ),
			__( 'Reports', 'salon-booking-system' ),
			apply_filters( 'salonviews/settings/capability', 'manage_salon' ),
			self::PAGE,
			array( $this, 'show' )
		);
	}

	public function show() {

		echo $this->plugin->loadView(
			'admin/reports', array(
				'plugin' => $this->plugin,
			)
		);
	}
}
