<?php

class SLN_Admin_Tools {

	const PAGE = 'salon-tools';

	protected $plugin;
	protected $settings;
	public $settings_page = '';

	public function __construct( SLN_Plugin $plugin ) {
		if ( isset( $_POST ) && $_POST )
			$this->save_settings( $_POST );

		$this->plugin	 = $plugin;
		$this->settings	 = $plugin->getSettings();
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		$this->settings_page = add_submenu_page(
		'salon', __( 'Salon Tools', 'salon-booking-system' ), __( 'Tools', 'salon-booking-system' ), apply_filters( 'salonviews/settings/capability', 'manage_salon' ), self::PAGE, array( $this, 'show' )
		);
	}

	public function show() {
		if (version_compare(phpversion(), "5.3.0", "<")) {
			$info = json_encode( get_option( SLN_Settings::KEY ) );
		} else {
			$info = json_encode( get_option( SLN_Settings::KEY ), JSON_PRETTY_PRINT );
		}
		
		echo $this->plugin->loadView(
		'admin/tools', array(
			'info' => $info
		)
		);
	}

	public function save_settings( $data ) {
		if ( !isset( $data[ 'sln-tools-import' ] ) )
			return;

		update_option( SLN_Settings::KEY, json_decode( $data[ 'tools-import' ], 1 ) );
		add_action( 'admin_notices', array( $this, 'tool_admin_notice' ) );
	}

	public function tool_admin_notice() {
		?>
		<div class="updated">
			<p><?php _e( 'Settings updated successfully!', 'salon-booking-system' ); ?></p>
		</div>
		<?php
	}

}
