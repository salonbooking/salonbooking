<?php

class SLN_Admin_Settings
{

    const PAGE = 'salon-settings';

    protected $plugin;
    protected $settings;
    public $settings_page = '';
    private $tabs = array(
        'homepage' => 'Home',
        'general' => 'General',
        'booking' => 'Booking Rules',
        'checkout' => 'Checkout',
        'payments' => 'Payments',
        'style' => 'Style',
        'gcalendar' => 'Google Calendar',
        'documentation' => 'Support',
    );

    private static $fieldsTabBooking = array(
        'confirmation',
        'thankyou',
        'bookingmyaccount',
        'pay',
        'reservation_interval_enabled', // algolplus
        'minutes_between_reservation',  // algolplus
        'availabilities',
        'holidays',                     // algolplus
        'availability_mode',
        'cancellation_enabled',         // algolplus
        'hours_before_cancellation',    // algolplus
        'disabled',
        'disabled_message',
        'confirmation',
        'parallels_day',
        'parallels_hour',
        'hours_before_from',
        'hours_before_to',
        'interval',
        'form_steps_alt_order',
    );

    private static $fieldsTabGeneral = array(
        'gen_name',
        'gen_email',
        'gen_phone',
        'gen_address',
        'gen_timetable',
        'ajax_enabled',
        'attendant_enabled',
        'm_attendant_enabled',
        'attendant_email',
        'sms_enabled',
        'sms_account',
        'sms_password',
        'sms_prefix',
        'sms_provider',
        'sms_from',
        'sms_new',
        'sms_new_number',
        'sms_new_attendant',
        'sms_remind',
        'sms_remind_interval',
        'sms_trunk_prefix',
        'email_remind',
        'email_remind_interval',
        'email_subject',
        'soc_facebook',
        'soc_twitter',
        'soc_google',
        'date_format',
        'time_format',
        'week_start',
        'no_bootstrap',
    );

    private static $fieldsTabCheckout = array(
        'enabled_guest_checkout',
    );

    private static $fieldsTabPayment = array(
        'hide_prices',
        'pay_method',
        'pay_currency',
        'pay_currency_pos',
        'pay_paypal_email',
        'pay_paypal_test',
        'pay_cash',
        'pay_enabled',
        'pay_deposit',
    );

    private static $fieldsTabStyle = array(
        'style_shortcode',
        'style_colors_enabled',
        'style_colors',
    );

    private static $fieldsTabGCalendar = array(
        'google_calendar_enabled',
        'google_outh2_client_id',
        'google_outh2_client_secret',
        'google_outh2_redirect_uri',
        'google_client_calendar',
    );

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_menu()
    {
        $this->settings_page = add_submenu_page(
            'salon',
            __('Salon Settings', 'salon-booking-system'),
            __('Settings', 'salon-booking-system'),
            apply_filters('salonviews/settings/capability', 'manage_options'),
            self::PAGE,
            array($this, 'show')
        );
    }

    function row_input_checkbox($key, $label, $settings = array())
    {
        SLN_Form::fieldCheckbox(
            "salon_settings[{$key}]",
            $this->getOpt($key),
            $settings
        )
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_input_checkbox_switch($key, $label, $settings = array())
    { ?>
        <h6 class="sln-fake-label"><?php echo $label ?></h6>
        <?php SLN_Form::fieldCheckbox(
        "salon_settings[{$key}]",
        $this->getOpt($key),
        $settings
    )
        ?>
        <label for="salon_settings_<?php echo $key ?>" class="sln-switch-btn" data-on="On" data-off="Off"></label>
        <?php
        if (isset($settings['help'])) { ?>
            <label class="sln-switch-text" for="salon_settings_<?php echo $key ?>"
                   data-on="<?php echo $settings['bigLabelOn'] ?>"
                   data-off="<?php echo $settings['bigLabelOff'] ?>"></label>
        <?php }
        if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function getOpt($key)
    {
        return $this->settings->get($key);
    }

    function row_input_text($key, $label, $settings = array())
    {
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php echo SLN_Form::fieldText("salon_settings[$key]", $this->getOpt($key)) ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_checkbox_text($key, $label, $settings = array())
    {
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php echo SLN_Form::fieldCheckbox("salon_settings[$key]", $this->getOpt($key)) ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_input_textarea($key, $label, $settings = array())
    {
        if (!isset($settings['textarea'])) {
            $settings['textarea'] = array();
        }
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php SLN_Form::fieldTextarea("salon_settings[$key]", $this->getOpt($key), $settings['textarea']); ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php } ?>
        <?php
    }

    function row_input_page($key, $label, $settings = array())
    {
        ?>
        <label for="<?php echo $key ?>"><?php echo $label ?></label>
        <?php
        wp_dropdown_pages(
            array(
                'name' => 'salon_settings['.$key.']',
                'selected' => $this->getOpt($key) ? $this->getOpt($key) : null,
                'show_option_none' => 'Nessuna',
            )
        );
    }

    /**
     * select_text
     * @param type $list
     * @param type $value
     * @param type $settings
     */
    function select_text($key, $label, $list, $settings = array())
    {
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label></th>
        <select name="salon_settings[<?php echo $key ?>]">
            <?php
            foreach ($list as $k => $value) {
                $lbl = $value['label'];
                $sel = ($value['id'] == $this->getOpt($key)) ? "selected" : "";
                echo "<option value='$k' $sel>$lbl</option>";
            }
            ?>
        </select>
        <?php
    }

    public function showTab($tab)
    {
        include $this->plugin->getViewFile('admin/utilities/settings-sidebar');
        include $this->plugin->getViewFile('settings/tab_'.$tab);
    }

    public function processTabHomepage()
    {
        if ($_POST['reset-settings'] == 'reset') {
            $this->settings->clear();
            SLN_Action_Install::execute(true);
            $this->showAlert(
                'success',
                __('remember to customize your settings', 'salon-booking-system'),
                __('Reset completed with success', 'salon-booking-system')
            );
        }
    }

    public function showTabGeneral()
    {
        include SLN_PLUGIN_URL.'/views/settings/general.php';
    }

    public function processTabGeneral()
    {
        $submitted = $_POST['salon_settings'];
        $submitted['email_subject'] = !empty($submitted['email_subject']) ?
            $submitted['email_subject'] :
            'Your booking reminder for [DATE] at [TIME] at [SALON NAME]';
        foreach (self::$fieldsTabGeneral as $k) {
            $val = isset($submitted[$k]) ? $submitted[$k] : '';
            $this->settings->set($k, stripcslashes($val));
        }
        wp_clear_scheduled_hook('sln_sms_reminder');
        if (isset($submitted['sms_remind']) && $submitted['sms_remind']) {
            wp_schedule_event(time(), 'hourly', 'sln_sms_reminder');
            wp_schedule_event(time()+1800, 'hourly', 'sln_sms_reminder');
        }
        wp_clear_scheduled_hook('sln_email_reminder');
        if (isset($submitted['email_remind']) && $submitted['email_remind']) {
            wp_schedule_event(time(), 'hourly', 'sln_email_reminder');
            wp_schedule_event(time()+1800, 'hourly', 'sln_email_reminder');
        }
        $this->settings->save();
        $this->showAlert(
            'success',
            __('general settings are updated', 'salon-booking-system'),
            __('Update completed with success', 'salon-booking-system')
        );
        if ($submitted['sms_test_number'] && $submitted['sms_test_message']) {
            $this->sendTestSms(
                $submitted['sms_test_number'],
                $submitted['sms_test_message']
            );
        }
    }

    private function sendTestSms($number, $message)
    {
        $sms = $this->plugin->sms();
        $sms->send($number, $message);
        if ($sms->hasError()) {
            $this->showAlert('error', $sms->getError());
        } else {
            $this->showAlert(
                'success',
                __('Test sms sent with success', 'salon-booking-system'),
                ''
            );
        }
    }

    private function bindSettings($fields, $submitted)
    {
        foreach ($fields as $k) {
            $data = isset($submitted[$k]) ? $submitted[$k] : '';
            $this->settings->set($k, $data);
        }
    }

    public function processTabBooking()
    {
        $submitted = $_POST['salon_settings'];
        $tmp = array();
        if (isset($submitted['availabilities']) && $submitted['availabilities']) {
            foreach ($submitted['availabilities'] as $row) {
                $tmp[] = $row;
            }
        }
        $submitted['availabilities'] = $tmp;
        $submitted['holidays'] = SLN_Helper_HolidayItems::processSubmission($submitted['holidays']);
        $this->bindSettings(self::$fieldsTabBooking, $submitted);
        $this->settings->save();
        $this->showAlert(
            'success',
            __('booking settings are updated', 'salon-booking-system'),
            __('Update completed with success', 'salon-booking-system')
        );
    }

    public function processTabCheckout()
    {
		$submitted = isset($_POST['salon_settings']) ? $_POST['salon_settings'] : array();
		$this->bindSettings(self::$fieldsTabCheckout, $submitted);
        $this->settings->save();
        $this->showAlert(
            'success',
            __('checkout settings are updated', 'salon-booking-system'),
            __('Update completed with success', 'salon-booking-system')
        );
    }

    public function processTabPayments()
    {
		$fields = self::$fieldsTabPayment;
        foreach (SLN_Enum_PaymentMethodProvider::toArray() as $k => $v) {
            $fields = array_merge(
                $fields,
                SLN_Enum_PaymentMethodProvider::getService($k, $this->plugin)->getFields()
            );
        }

        $submitted = $_POST['salon_settings'];
        $this->bindSettings($fields, $submitted);
        if (isset($submitted['hide_prices'])) {
            $this->settings->set('pay_enabled', '');
        }

        $this->settings->save();
        $this->showAlert(
            'success',
            __('payments settings are updated', 'salon-booking-system'),
            __('Update completed with success', 'salon-booking-system')
        );
    }

    public function show()
    {
        $current = $this->getCurrentTab();
        if ($_POST) {
            $method = "processTab".ucwords($current);
            if (!method_exists($this, $method)) {
                throw new Exception('method not found '.$method);
            }
            if (empty($_POST[self::PAGE.$current]) || !wp_verify_nonce($_POST[self::PAGE.$current])) {
                $this->$method();
            } else {
                $this->showAlert(
                    'error',
                    __('try again', 'salon-booking-system'),
                    __('Page verification failed', 'salon-booking-system')
                );
            }
        }
        ?>
        <div id="sln-salon--admin" class="wrap sln-bootstrap sln-salon--settings">
            <?php screen_icon(); ?>
            <div class="row">
                <div class="col-xs-12"><h2><?php _e('Salon Settings', 'salon-booking-system'); ?></h2></div>
                <div class="sln-admin-nav hidden-xs hidden-sm col-sm-12 col-md-8">
                    <ul class="sln-admin-nav">
                        <li><a href="admin.php?page=salon" class="sln-btn--icon sln-icon--calendar">Calendar</a></li>
                        <li><a href="edit.php?post_type=sln_booking"
                               class="sln-btn--icon sln-icon--booking">Bookings</a></li>
                        <li><a href="edit.php?post_type=sln_service"
                               class="sln-btn--icon sln-icon--services">Services</a></li>
                        <li><a href="edit.php?post_type=sln_attendant" class="sln-btn--icon sln-icon--assistants">Assistants</a>
                        </li>
                        <li class="current"><a href="admin.php?page=salon-settings"
                                               class="current sln-btn--icon sln-icon--settings">Settings</a></li>
                    </ul>
                </div>
            </div>

            <?php settings_errors(); ?>
            <?php $this->showTabsBar(); ?>
            <form method="post" action="<?php admin_url('admin.php?page='.self::PAGE); ?>">
                <?php
                $this->showTab($current);
                wp_nonce_field(self::PAGE.$current);
                if ($current != 'homepage') {
                    submit_button(esc_attr__('Update Settings', 'salon-booking-system'), 'primary');
                }
                ?>
            </form>

        </div><!-- wrap -->
        <?php
    }

    private function showTabsBar()
    {
        echo '<h2 class="sln-nav-tab-wrapper nav-tab-wrapper">';
        $page = self::PAGE;
        $current = $this->getCurrentTab();
        foreach ($this->tabs as $tab => $name) {
            $class = ($tab == $current) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=$page&tab=$tab'>$name</a>";
        }
        echo '</h2>';
    }

    private function showAlert($type, $txt, $title = null)
    {
        ?>
        <div id="sln-setting-<?php echo $type ?>" class="updated settings-<?php echo $type ?>">
            <?php if (!empty($title)) { ?>
                <p><strong><?php echo $title ?></strong></p>
            <?php } ?>
            <p><?php echo $txt ?></p>
        </div>
        <?php
    }

    public function processTabStyle()
    {
        $submitted = $_POST['salon_settings'];
        $this->bindSettings(self::$fieldsTabStyle,$submitted);

        $this->settings->save();
        if($this->settings->get('style_colors_enabled')) {
            $this->saveCustomCss();
        }
        $this->showAlert(
            'success',
            __('style settings are updated', 'salon-booking-system'),
            __('Update completed with success', 'salon-booking-system')
        );
    }
    private function saveCustomCss(){
        $css = file_get_contents(SLN_PLUGIN_DIR.'/css/sln-colors--custom.css');
        $colors = $this->settings->get('style_colors');

        if($colors)
        foreach($colors as $k => $v){
            $css = str_replace("{color-$k}", $v, $css);
        }
        $dir = wp_upload_dir();
        $dir = $dir['basedir'];
        file_put_contents($dir.'/sln-colors.css', $css); 
    }

    public function processTabGcalendar()
    {
        $submitted = $_POST['salon_settings'];
        $oldSettings = $this->settings->all();
        $this->bindSettings(self::$fieldsTabGCalendar, $submitted);
        $this->settings->save();

        $params = array();
        foreach (self::$fieldsTabGCalendar as $k) {
            $v = $this->settings->get($k);
            $k = str_replace('google_', '', $k);
            $params[$k] = $v;
        }

        if ($this->needsGCalendarRevokeToken($oldSettings)) {
            header("Location: ".admin_url('admin.php?page=salon-settings&tab=gcalendar&revoketoken=1'));
        }

        if (isset($_GET['revoketoken']) && $_GET['revoketoken'] == 1) {
            header("Location: ".admin_url('admin.php?page=salon-settings&tab=gcalendar'));
        }

        $this->showAlert(
            'success',
            __('Google Calendar settings are updated', 'salon-booking-system'),
            __('Update completed with success', 'salon-booking-system')
        );
    }

    private function needsGCalendarRevokeToken($old)
    {
        $s = $this->settings;

        return $old['google_calendar_enabled'] != $s->get('google_calendar_enabled')
        || $old['google_outh2_client_id'] != $s->get('google_outh2_client_id')
        || $old['google_outh2_client_secret'] != $s->get('google_outh2_client_secret');
    }

    function getCurrentTab()
    {
        return isset($_GET['tab']) ? $_GET['tab'] : 'homepage';
    }

    function hidePriceSettings()
    {
        $ret = $this->getOpt('hide_prices') ? array(
            'attrs' => array(
                'disabled' => 'disabled',
                'title' => 'Please disable hide prices from general settings to enable online payment.',
            ),
        ) : array();

        return $ret;
    }

}
