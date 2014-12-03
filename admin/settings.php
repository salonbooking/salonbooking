<?php

class SLN_Saloon_Settings {

	private static $instance;

	public $settings_page = '';

	public $settings = array();

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
if(!get_option('saloon_settings'))
    add_option('saloon_settings',array());
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
var_dump($settings);
            return $settings;
        }

        function row_input_checkbox($key, $label){ ?>
           <tr valign="top">
            <th scope="row"><label for="saloon_settings[<?php echo $key?>]"><?php echo $label ?></label></th>
            <td>
                <input type="checkbox" name="saloon_settings[<?php echo $key?>]" value="1" <?php self::getOpt($key) ? 'checked="checked"' : '' ?>/>
            </td>
           </tr>
        <?php }

        function getOpt($key){
            $i = self::get_instance()->settings;
            return isset($i[$key]) ? $i[key] : '';
        }

        function row_input_text($key, $label){ ?>

           <tr valign="top">
            <th scope="row"><label for="saloon_settings[<?php echo $key?>]"><?php echo $label ?></label></th>
            <td>
                <input type="text" name="saloon_settings[<?php echo $key?>]" value="<?php self::getOpt($key) ?>"/>
            </td>
           </tr>
        <?php }

        function row_input_textarea($key, $label){ ?>
           <tr valign="top">
           <th scope="row"><label for="saloon_settings[<?php echo $key?>]"><?php echo $label ?></label></th>
            <td>
                <textarea name="saloon_settings[<?php echo $key?>]"><?php echo isset($this->settings[$key]) ? $this->settings[key] : ''; ?></textarea>
            </td>
           </tr>
        <?php }

        function row_input_page($key, $label){ ?>
          <tr valign="top">
          <th scope="row"><label for="<?php echo $key ?>"><?php echo $label ?></label></th>
          <td>
<?php
 wp_dropdown_pages(array(
'name' => 'saloon_settings['.$key.']',
'selected' => self::getOpt($key) ? self::getOpt($key) : null,
'show_option_none'      => 'Nessuna'
             )) 
?>       </td>
         </tr>


        <?php }

	public function section_general() { ?>
 <table class="form-table">
<?php
self::row_input_text('gen_name',  __('Name', 'sln'));
self::row_input_text('gen_email',  __('E-Mail', 'sln'));
self::row_input_text('gen_phone',  __('Phone', 'sln'));
self::row_input_textarea('gen_address',  __('Address', 'sln'));
self::row_input_textarea('gen_timetable',  __('Timetable Infos', 'sln'));
?>
<tr><th class="row" colspan="2"><stron>Social</strong></th></tr>
<?php
self::row_input_text('soc_facebook',  __('Facebook', 'sln'));
self::row_input_text('soc_twitter',  __('Twitter', 'sln'));
self::row_input_text('soc_google',  __('Google+', 'sln'));
?>
</table>
	<?php }

	public function section_booking_rules() { ?>
    <table class="form-table">

<?php
$key = 'available';
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
            <label><input type="checkbox" name="saloon_settings[<?php echo $key.'_'.$k?>]" value="1" <?php self::getOpt($key.'_'.$k) ? 'checked="checked"' : '' ?>/><?php echo substr($day, 0,3)?></label>
<?php } ?><br/>
<?php foreach(array('from' => __('From','sln'), 'to' => __('To','sln')) as $k => $v){ ?>
        <label><?php echo $v ?> <input type="text" name="saloon_settings[<?php echo $key.'_'.$k ?>]" value="<?php self::getOpt($key.'_'.$k)?>" /> </label>
<?php } ?>
        </td>

       </tr>
<?php
self::row_input_checkbox('confirmation',  __('Bookings Confirmation', 'sln'));
self::row_input_page('thankyou',  __('Thank you page', 'sln'));
?>
    </table>
	<?php }

	public function section_payments() { ?>
<table class="form-table">
<?php
self::row_input_checkbox('pay_enabled',  __('Enable online payments', 'sln'));
self::row_input_checkbox('pay_cash',  __('Client can pay on delivery', 'sln'));
?>
<tr><th class="row" colspan="2"><strong>Payment settings</strong></th></tr>
<?php
self::row_input_text('pay_currency',  __('Currency code(3 chars)', 'sln'));
self::row_input_text('pay_currency',  __('Currency Symbol', 'sln'));
self::row_input_text('pay_paypal_email',  __('Paypal E-mail', 'sln'));
self::row_input_text('pay_paypal_apikey',  __('Paypal Api-key', 'sln'));

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
