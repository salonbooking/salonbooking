<?php

class SLN_Saloon_Settings {

	private static $instance;

	public $settings_page = '';

	public $settings = array();

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {

                add_menu_page(  __( 'Saloon','sln' ),  __( 'Saloon','sln' ), 'manage_options', 'saloon', array( $this, 'settings_page' ),null, 2);
		$this->settings_page = add_submenu_page( 
			'saloon',
			__( 'Saloon Settings', 'sln' ),
			__( 'Settings',            'sln' ),
			apply_filters( 'saloon_settings_capability', 'manage_options' ),
			'saloon-settings',
			array( $this, 'settings_page' )
		);

		if ( !empty( $this->settings_page ) ) {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	function register_settings() {

		$this->settings = get_option( 'saloon_settings' );

		register_setting( 'saloon_settings', 'saloon_settings', array( $this, 'validate_settings' ) );

		add_settings_section( 
			'sln_section_general', 
			__( 'General', 'sln' ), 
			array( $this, 'section_general' ),
			$this->settings_page
		);

		add_settings_section( 
			'sln_section_bookin_rules', 
			__( 'Booking rules', 'sln' ), 
			array( $this, 'section_booking_rules' ),
			$this->settings_page
		);

		add_settings_section( 
			'sln_section_payments', 
			__( 'Payments', 'sln' ), 
			array( $this, 'section_payments' ),
			$this->settings_page
		);
	}

	function validate_settings( $settings ) {

		$settings['saloon_item_archive_title'] = strip_tags( $settings['restaurant_item_archive_title'] );

		/* Kill evil scripts. */
		if ( !current_user_can( 'unfiltered_html' ) )
			$settings['saloon_item_description'] = stripslashes( wp_filter_post_kses( addslashes( $settings['restaurant_item_description'] ) ) );

		/* Return the validated/sanitized settings. */
		return $settings;
	}

        function row_input_checkbox($key, $label){ ?>
           <tr valign="top">
            <th scope="row"><label for="<?php echo $key?>"><?php echo $label ?></label></th>
            <td>
                <input type="checkbox" name="<?php echo $key?>" value="1" <?php get_option($key) ? 'checked="checked"' : '' ?>/>
            </td>
           </tr>
        <?php }


        function row_input_text($key, $label){ ?>

           <tr valign="top">
            <th scope="row"><label for="<?php echo $key?>"><?php echo $label ?></label></th>
            <td>
                <input type="text" name="<?php echo $key?>" value="<?php get_option($key) ?>"/>
            </td>
           </tr>
        <?php }


        function row_input_textarea($key, $label){ ?>
           <tr valign="top">
           <th scope="row"><label for="<?php echo $key?>"><?php echo $label ?></label></th>
            <td>
                <textarea name="<?php echo $key?>"><?php get_option($key) ?></textarea>
            </td>
           </tr>
        <?php }

        function row_input_page($key, $label){ ?>
          <tr valign="top">
          <th scope="row"><label for="<?php echo $key ?>"><?php echo $label ?></label></th>
          <td>
<?php
 wp_dropdown_pages(array(
'name' => $key,
'selected' => get_option($key) ? get_option($key) : null,
'show_option_none'      => 'Nessuna'
             )) 
?>       </td>
         </tr>


        <?php }

	public function section_general() { ?>
 <table class="form-table">
<?php
SLN_Saloon_Settings::row_input_text('sln_gen_name',  __('Name', 'sln'));
self::row_input_text('sln_gen_email',  __('E-Mail', 'sln'));
self::row_input_text('sln_gen_phone',  __('Phone', 'sln'));
self::row_input_textarea('sln_gen_address',  __('Address', 'sln'));
self::row_input_textarea('sln_gen_timetable',  __('Timetable Infos', 'sln'));
?>
<tr><th class="row" colspan="2"><stron>Social</strong></th></tr>
<?php
self::row_input_text('sln_soc_facebook',  __('Facebook', 'sln'));
self::row_input_text('sln_soc_twitter',  __('Twitter', 'sln'));
self::row_input_text('sln_soc_google',  __('Google+', 'sln'));
?>
</table>
	<?php }

	public function section_booking_rules() { ?>
    <table class="form-table">

<?php
$key = 'sln_available';
$label =  __('Online booking not available on', 'sln');
?>
       <tr valign="top">
        <th scope="row" nowrap="nowrap"><?php echo $label ?></th>
        <td>
<?php
$timestamp = strtotime('next Sunday');
$days = array();
for ($i = 0; $i < 7; $i++) {
 $days[] = strftime('%A', $timestamp);
 $timestamp = strtotime('+1 day', $timestamp);
}
?>
<?php foreach($days as $k => $day){ ?>
            <label><input type="checkbox" name="<?php echo $key.'_'.$k?>" value="1" <?php get_option($key.'_'.$k) ? 'checked="checked"' : '' ?>/><?php echo substr($day, 0,3)?></label>
<?php } ?><br/>
<?php foreach(array('from' => __('From','sln'), 'to' => __('To','sln')) as $k => $v){ ?>
        <label><?php echo $v ?> <input type="text" name="<?php echo $key.'_'.$k ?>" value="<?php get_option($key.'_'.$k)?>" /> </label>
<?php } ?>
        </td>

       </tr>
<?php
self::row_input_checkbox('sln_confirmation',  __('Bookings Confirmation', 'sln'));
self::row_input_page('sln_thankyou',  __('Thank you page', 'sln'));
?>
    </table>
	<?php }

	public function section_payments() { ?>
<table class="form-table">
<?php
self::row_input_checkbox('sln_pay_enabled',  __('Enable online payments', 'sln'));
self::row_input_checkbox('sln_pay_cash',  __('Client can pay on delivery', 'sln'));
?>
<tr><th class="row" colspan="2"><strong>Payment settings</strong></th></tr>
<?php
self::row_input_text('sln_pay_currency',  __('Currency code(3 chars)', 'sln'));
self::row_input_text('sln_pay_currency',  __('Currency Symbol', 'sln'));
self::row_input_text('sln_pay_paypal_email',  __('Paypal E-mail', 'sln'));
self::row_input_text('sln_pay_paypal_apikey',  __('Paypal Api-key', 'sln'));

?>
</table>
	<?php }

	public function settings_page() { ?>

		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e( 'Saloon Settings', 'sln' ); ?></h2>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'saloon_settings' ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php submit_button( esc_attr__( 'Update Settings', 'sln' ), 'primary' ); ?>
			</form>

		</div><!-- wrap -->
	<?php }

	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

SLN_Saloon_Settings::get_instance();
