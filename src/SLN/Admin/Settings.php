<?php

class SLN_Admin_Settings {

    const PAGE = 'salon-settings';

    protected $plugin;
    protected $settings;
    public $settings_page = '';
    private $tabs = array(
        'homepage' => 'Home',
        'general' => 'General',
        'booking' => 'Booking Rules',
        'payments' => 'Payments',
        'gcalendar' => 'Google Calendar',
        'documentation' => 'Support'
    );

    public function __construct(SLN_Plugin $plugin) {
        $this->plugin = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_menu() {
        add_menu_page(
                __('Salon', 'salon-booking-system'), __('Salon', 'salon-booking-system'), 'manage_options', 'salon', array($this, 'settings_page'), SLN_PLUGIN_URL . '/img/admin_icon.png'
        );
        $this->settings_page = add_submenu_page(
                'salon', __('Salon Settings', 'salon-booking-system'), __('Settings', 'salon-booking-system'), apply_filters('salonviews/settings/capability', 'manage_options'), self::PAGE, array($this, 'show')
        );
    }

    function row_input_checkbox($key, $label, $settings = array()) {
        SLN_Form::fieldCheckbox(
                "salon_settings[{$key}]", $this->getOpt($key), $settings
        )
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_input_checkbox_switch($key, $label, $settings = array()) { ?>
        <h6 class="sln-fake-label"><?php echo $label ?></h6>
        <?php SLN_Form::fieldCheckbox(
                "salon_settings[{$key}]", $this->getOpt($key), $settings
        )
        ?>
        <label for="salon_settings_<?php echo $key ?>" class="sln-switch-btn" data-on="On" data-off="Off"></label>
        <?php
            if (isset($settings['help'])) { ?>
            <label class="sln-switch-text"  for="salon_settings_<?php echo $key ?>" data-on="<?php echo $settings['bigLabelOn'] ?>" 
                data-off="<?php echo $settings['bigLabelOff'] ?>"></label>
            <?php }
            if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function getOpt($key) {
        return $this->settings->get($key);
    }

    function row_input_text($key, $label, $settings = array()) {
        ?>
            <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php echo SLN_Form::fieldText("salon_settings[$key]", $this->getOpt($key)) ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
        }
        function row_checkbox_text($key, $label, $settings = array()) {
        ?>
            <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php echo SLN_Form::fieldCheckbox("salon_settings[$key]", $this->getOpt($key)) ?>
            <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

        function row_input_textarea($key, $label, $settings = array()) {
            if (!isset($settings['textarea'])) {
                $settings['textarea'] = array();
            }
            ?>
            <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php SLN_Form::fieldTextarea("salon_settings[$key]", $this->getOpt($key), $settings['textarea']); ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php } ?>
        <?php
    }

    function row_input_page($key, $label, $settings = array()) {
        ?>
            <label for="<?php echo $key ?>"><?php echo $label ?></label>
        <?php
        wp_dropdown_pages(
                array(
                    'name' => 'salon_settings[' . $key . ']',
                    'selected' => $this->getOpt($key) ? $this->getOpt($key) : null,
                    'show_option_none' => 'Nessuna'
                )
        );
        }

         /**
     * select_text
     * @param type $list
     * @param type $value
     * @param type $settings
     */
    function select_text($key, $label, $list, $settings = array()) {
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
    
        public function showTab($tab) {
            include $this->plugin->getViewFile('admin/utilities/settings-sidebar');
            include $this->plugin->getViewFile('settings/tab_' . $tab);
        }

        public function showTabHomepage() {
            include SLN_PLUGIN_BASENAME . '/views/settings/homepage.php';
        }

        public function processTabHomepage() {
            if ($_POST['reset-settings'] == 'reset') {
                $this->settings->clear();
                SLN_Action_Install::execute(true);
                $this->showAlert(
                        'success', __('remember to customize your settings', 'salon-booking-system'), __('Reset completed with success', 'salon-booking-system')
                );
            }
        }

        public function showTabGeneral() {
            include SLN_PLUGIN_URL . '/views/settings/general.php';
        }

        public function processTabGeneral() {
            foreach (array(
        'gen_name',
        'gen_email',
        'gen_phone',
        'gen_address',
        'gen_timetable',
        'ajax_enabled',
        'attendant_enabled',
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
        'soc_facebook',
        'soc_twitter',
        'soc_google',
        'date_format',
        'time_format',
        'no_bootstrap'
            ) as $k) {
                $val = isset($_POST['salon_settings'][$k]) ? $_POST['salon_settings'][$k] : '';
                $this->settings->set($k, stripcslashes($val));
            }
            wp_clear_scheduled_hook('sln_sms_reminder');
            if (isset($_POST['salon_settings']['sms_remind']) && $_POST['salon_settings']['sms_remind']) {
                wp_schedule_event(time(), 'hourly', 'sln_sms_reminder');
            }
            $this->settings->save();
            $this->showAlert(
                    'success', __('general settings are updated', 'salon-booking-system'), __('Update completed with success', 'salon-booking-system')
            );
            if ($_POST['salon_settings']['sms_test_number'] && $_POST['salon_settings']['sms_test_message']) {
                try{
                $this->plugin->sendSms(
                    $_POST['salon_settings']['sms_test_number'],
                    $_POST['salon_settings']['sms_test_message']
                );
                $this->showAlert(
                        'success', __('Test sms sent with success', 'salon-booking-system'), ''
                );
                }catch(\SLN_Action_Sms_Exception $e){
                    $this->showAlert('error', $e->getMessage());
                }
            }
        }

        public function showTabBooking() {
            include SLN_PLUGIN_URL . '/views/settings/booking.php';
        }

        public function processTabBooking() {
            $tmp = array();
            foreach ($_POST['salon_settings']['availabilities'] as $row) {
                $tmp[] = $row;
            }
            $_POST['salon_settings']['availabilities'] = $tmp;
            foreach (array(
        'confirmation',
        'thankyou',
        'pay',
        'availabilities',
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
        'interval'
            ) as $k) {
                $this->settings->set($k, isset($_POST['salon_settings'][$k]) ? $_POST['salon_settings'][$k] : '');
            }
            $this->settings->save();
            $this->showAlert(
                    'success', __('booking settings are updated', 'salon-booking-system'), __('Update completed with success', 'salon-booking-system')
            );
        }

        public function showTabPayments() {
            include SLN_PLUGIN_URL . '/views/settings/payments.php';
        }

        public function processTabPayments() {
            $fields = array(
        'hide_prices',
        'pay_method',
        'pay_currency',
        'pay_currency_pos',
        'pay_paypal_email',
        'pay_paypal_test',
        'pay_cash',
        'pay_enabled',
        'pay_deposit'
            );
            foreach(SLN_Enum_PaymentMethodProvider::toArray() as $k => $v){
                $fields = array_merge($fields, SLN_Enum_PaymentMethodProvider::getService($k, $this->plugin)->getFields());
            }

            foreach ($fields as $k) {
                $data = isset($_POST['salon_settings'][$k]) ? $_POST['salon_settings'][$k] : '';
                $this->settings->set($k, $data);
            }

            if (isset($_POST['salon_settings']['hide_prices'])) {
                $this->settings->set('pay_enabled', '');
            }


            $this->settings->save();
            $this->showAlert(
                    'success', __('payments settings are updated', 'salon-booking-system'), __('Update completed with success', 'salon-booking-system')
            );
        }

        public function show() {
            $current = $this->getCurrentTab();
            if ($_POST) {
                $method = "processTab" . ucwords($current);
                if (!method_exists($this, $method)) {
                    throw new Exception('method not found ' . $method);
                }
                if (empty($_POST[self::PAGE . $current]) || !wp_verify_nonce($_POST[self::PAGE . $current])) {
                    $this->$method();
                } else {
                    $this->showAlert('error', __('try again', 'salon-booking-system'), __('Page verification failed', 'salon-booking-system'));
                }
            }
            ?>
        <div id="sln-salon--admin" class="wrap sln-bootstrap sln-salon--settings">
        <?php screen_icon(); ?>
        <div class="row">
            <h2 class="col-xs-12 col-sm-4"><?php _e('Salon Settings', 'salon-booking-system'); ?></h2>
            <div class="sln-admin-nav hidden-xs col-sm-8">
                <ul class="sln-admin-nav">
                <li><a href="admin.php?page=salon-calendar" class="sln-btn--icon sln-icon--calendar">Calendar</a></li>
                <li><a href="edit.php?post_type=sln_booking" class="sln-btn--icon sln-icon--booking">Bookings</a></li>
                <li><a href="edit.php?post_type=sln_service" class="sln-btn--icon sln-icon--services">Services</a></li>
                <li><a href="edit.php?post_type=sln_attendant" class="sln-btn--icon sln-icon--assistants">Assistants</a></li>
                <li class="current"><a href="admin.php?page=salon-settings" class="current sln-btn--icon sln-icon--settings">Settings</a></li>
            </ul>
            </div>
        </div>

        <?php settings_errors(); ?>
        <?php $this->showTabsBar(); ?>
            <form method="post" action="<?php admin_url('admin.php?page=' . self::PAGE); ?>">
        <?php
        $this->showTab($current);
        wp_nonce_field(self::PAGE . $current);
        if ($current != 'homepage') {
            submit_button(esc_attr__('Update Settings', 'salon-booking-system'), 'primary');
        }
        ?>
            </form>

        </div><!-- wrap -->
        <?php
    }

    private function showTabsBar() {
        echo '<h2 class="nav-tab-wrapper">';
        $page = self::PAGE;
        $current = $this->getCurrentTab();
        foreach ($this->tabs as $tab => $name) {
            $class = ($tab == $current) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=$page&tab=$tab'>$name</a>";
        }
        echo '</h2>';
    }

    private function showAlert($type, $txt, $title = null) {
        ?>
        <div id="sln-setting-<?php echo $type ?>" class="updated settings-<?php echo $type ?>">
        <?php if (!empty($title)) { ?>
                <p><strong><?php echo $title ?></strong></p>
        <?php } ?>
            <p><?php echo $txt ?></p>
        </div> 
        <?php
    }

    public function showTabGcalendar() {
        
    }

    public function processTabGcalendar() {
        $gcalendar_array = array(
            'google_calendar_enabled',
            'google_outh2_client_id',
            'google_outh2_client_secret',
            'google_outh2_redirect_uri',
            'google_client_calendar'
        );

        foreach ($gcalendar_array as $k) {
            $old_value[$k] = $this->settings->get($k);
            $data = isset($_POST['salon_settings'][$k]) ? trim($_POST['salon_settings'][$k]) : '';
            $this->settings->set($k, $data);
        }
        $this->settings->save();
        $params = array();
        foreach ($gcalendar_array as $k) {
            $v = $this->settings->get($k);
            $k = str_replace('google_', '', $k);
            $params[$k] = $v;
        }

        if ($old_value['google_calendar_enabled'] != $this->settings->get('google_calendar_enabled') ||
                $old_value['google_outh2_client_id'] != $this->settings->get('google_outh2_client_id') ||
                $old_value['google_outh2_client_secret'] != $this->settings->get('google_outh2_client_secret')
        )
            header("Location: " . admin_url('admin.php?page=salon-settings&tab=gcalendar&revoketoken=1'));

        if (isset($_GET['revoketoken']) && $_GET['revoketoken'] == 1)
            header("Location: " . admin_url('admin.php?page=salon-settings&tab=gcalendar'));

        $this->showAlert(
                'success', __('Google Calendar settings are updated', 'salon-booking-system'), __('Update completed with success', 'salon-booking-system')
        );
    }

    function getCurrentTab() {
        return isset($_GET['tab']) ? $_GET['tab'] : 'homepage';
    }

    function hidePriceSettings() {
        $ret = $this->getOpt('hide_prices')  ? array('attrs' => array('disabled' => 'disabled', 'title' => 'Please disable hide prices from general settings to enable online payment.')) : array();
        return $ret;
    }

}
