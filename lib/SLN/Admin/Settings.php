<?php

class SLN_Admin_Settings
{
    const PAGE = 'saloon-settings';

    protected $plugin;
    protected $settings;
    public $settings_page = '';
    private $tabs = array(
        'homepage'      => 'Home',
        'general'       => 'General',
        'booking'       => 'Booking Rules',
        'payments'      => 'Payments',
        'documentation' => 'Documentation'
    );

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin   = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_menu()
    {
        add_menu_page(
            __('Saloon', 'sln'),
            __('Saloon', 'sln'),
            'manage_options',
            'saloon',
            array($this, 'settings_page'),
            null,
            2
        );
        $this->settings_page = add_submenu_page(
            'saloon',
            __('Saloon Settings', 'sln'),
            __('Settings', 'sln'),
            apply_filters('saloonviews/settings/capability', 'manage_options'),
            self::PAGE,
            array($this, 'settings_page')
        );
    }


    function row_input_checkbox($key, $label, $settings = array())
    {
        ?>
        <div class="form-group">
            <label for="saloon_settings[<?php echo $key ?>]"><?php echo $label ?></label></th>
            <?php
            SLN_Form::fieldCheckbox(
                "saloon_settings[{$key}]",
                $this->getOpt($key)
            )
            ?>
            <?php if (isset($settings['help'])) ?><p class="help-block"><?php echo $settings['help'] ?></p>
        </div>
    <?php
    }

    function getOpt($key)
    {
        return $this->settings->get($key);
    }

    function row_input_text($key, $label, $settings = array())
    {
        ?>
        <div class="form-group">
            <label for="saloon_settings[<?php echo $key ?>]"><?php echo $label ?></label></th>
            <?php echo SLN_Form::fieldText("saloon_settings[$key]", $this->getOpt($key)) ?>
            <?php if (isset($settings['help'])) ?><p class="help-block"><?php echo $settings['help'] ?></p>
        </div>
    <?php
    }

    function row_input_textarea($key, $label, $settings = array())
    {
        if (!isset($settings['textarea'])) {
            $settings['textarea'] = array();
        }
        ?>
        <div class="form-group">
            <label for="saloon_settings[<?php echo $key ?>]"><?php echo $label ?></label></th>
            <?php SLN_Form::fieldTextarea("saloon_settings[$key]", $this->getOpt($key), $settings['textarea']); ?>
            <?php if (isset($settings['help'])) ?><p class="help-block"><?php echo $settings['help'] ?></p>
        </div>
    <?php
    }

    function row_input_page($key, $label, $settings = array())
    {
        ?>
        <div class="form-group">
            <label for="<?php echo $key ?>"><?php echo $label ?></label>
            <?php
            wp_dropdown_pages(
                array(
                    'name'             => 'saloon_settings[' . $key . ']',
                    'selected'         => $this->getOpt($key) ? $this->getOpt($key) : null,
                    'show_option_none' => 'Nessuna'
                )
            )
            ?>
        </div>
    <?php
    }

    public function showTab($tab)
    {
        include $this->plugin->getViewFile('settings/tab_' . $tab);
    }

    public function showTabHomepage()
    {
        include SLN_PLUGIN_BASENAME . '/views/settings/homepage.php';
    }

    public function processTabHomepage()
    {
        if ($_POST['reset-settings'] == 'reset') {
            $this->settings->clear();
            SLN_Action_Install::execute();
            $this->showAlert(
                'success',
                __('remember to customize your settings', 'sln'),
                __('Reset completed with success', 'sln')
            );
        }
    }

    public function showTabGeneral()
    {
        include SLN_PLUGIN_URL . '/views/settings/general.php';
    }

    public function processTabGeneral()
    {
        foreach (array(
                     'gen_name',
                     'gen_email',
                     'gen_phone',
                     'gen_address',
                     'gen_timetable',
                     'soc_facebook',
                     'soc_twitter',
                     'soc_google'
                 ) as $k) {
            $this->settings->set($k, $_POST['saloon_settings'][$k]);
        }
        $this->settings->save();
        $this->showAlert(
            'success',
            __('general settings are updated', 'sln'),
            __('Update completed with success', 'sln')
        );
    }

    public function showTabBooking()
    {
        include SLN_PLUGIN_URL . '/views/settings/booking.php';
    }

    public function processTabBooking()
    {
        $tmp = array();
        foreach ($_POST['saloon_settings']['availabilities'] as $row) {
            $tmp[] = $row;
        }
        $_POST['saloon_settings']['availabilities'] = $tmp;
        foreach (array(
                     'confirmation',
                     'thankyou',
                     'availabilities',
                     'disabled',
                     'disabled_message',
                     'confirmation',
                     'parallels_day',
                     'parallels_hour',
                     'hours_before_from',
                     'hours_before_to',
                     'interval'
                 ) as $k) {
            $this->settings->set($k, $_POST['saloon_settings'][$k]);
        }
        $this->settings->save();
        $this->showAlert(
            'success',
            __('booking settings are updated', 'sln'),
            __('Update completed with success', 'sln')
        );
    }

    public function showTabPayments()
    {
        include SLN_PLUGIN_URL . '/views/settings/payments.php';
    }

    public function processTabPayments()
    {
        foreach (array(
                     'pay_currency',
                     'pay_paypal_email',
                     'pay_paypal_test',
                     'pay_cash',
                     'pay_enabled'
                 ) as $k) {
            $this->settings->set($k, $_POST['saloon_settings'][$k]);
        }
        $this->settings->save();
        $this->showAlert(
            'success',
            __('payments settings are updated', 'sln'),
            __('Update completed with success', 'sln')
        );
    }

    public function settings_page()
    {
        $current = $this->getCurrentTab();
        if ($_POST) {
            $method = "processTab" . ucwords($current);
            if (!method_exists($this, $method)) {
                throw new \Exception('method not found ' . $method);
            }
            if (!wp_verify_nonce($_POST[self::PAGE . $current])) {
                $this->$method();
            } else {
                $this->showAlert('error', __('try again', 'sln'), __('Page verification failed', 'sln'));
            }
        }
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php _e('Saloon Settings', 'sln'); ?></h2>

            <?php settings_errors(); ?>
            <?php $this->showTabsBar(); ?>
            <form method="post" action="<?php admin_url('admin.php?page=' . self::PAGE); ?>">
                <?php
                $this->showTab($current);
                wp_nonce_field(self::PAGE . $current);
                if ($current != 'homepage') {
                    submit_button(esc_attr__('Update Settings', 'sln'), 'primary');
                } ?>
            </form>

        </div><!-- wrap -->
    <?php
    }

    private function showTabsBar()
    {
        echo '<h2 class="nav-tab-wrapper"><i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
        $page    = self::PAGE;
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

    function getCurrentTab()
    {
        return isset($_GET['tab']) ? $_GET['tab'] : 'homepage';
    }
}