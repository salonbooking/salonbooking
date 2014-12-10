<?php

class SLN_Admin_Settings
{
    const PAGE = 'saloon-settings';

    protected $plugin;
    protected $settings;
    public $settings_page = '';
    private $tabs = array(
        'homepage' => 'Home',
        'general'  => 'General',
        'booking'  => 'Booking Rules',
        'payments' => 'Payments'
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
            apply_filters('saloon_settings_capability', 'manage_options'),
            self::PAGE,
            array($this, 'settings_page')
        );
    }


    function row_input_checkbox($key, $label, $settings = array())
    {
        ?>
        <tr valign="top">
            <th scope="row"><label for="saloon_settings[<?php echo $key ?>]"><?php echo $label ?></label></th>
            <td>
                <?php
                SLN_Form::fieldCheckbox(
                    "saloon_settings[{$key}]",
                    $this->getOpt($key)
                )
                ?>
            </td>
        </tr>
    <?php
    }

    function getOpt($key)
    {
        return $this->settings->get($key);
    }

    function row_input_text($key, $label, $settings = array())
    {
        ?>
        <tr valign="top">
            <th scope="row"><label for="saloon_settings[<?php echo $key ?>]"><?php echo $label ?></label></th>
            <td>
                <?php echo SLN_Form::fieldText("saloon_settings[$key]", $this->getOpt($key)) ?>
                <?php if (isset($settings['help'])) ?><br/><em><?php echo $settings['help'] ?></em>
            </td>
        </tr>
    <?php
    }

    function row_input_textarea($key, $label, $settings = array())
    {
        ?>
        <tr valign="top">
            <th scope="row"><label for="saloon_settings[<?php echo $key ?>]"><?php echo $label ?></label></th>
            <td>
                <textarea
                    name="saloon_settings[<?php echo $key ?>]"><?php echo $this->getOpt($key) ?></textarea>
            </td>
        </tr>
    <?php
    }

    function row_input_page($key, $label, $settings = array())
    {
        ?>
        <tr valign="top">
            <th scope="row"><label for="<?php echo $key ?>"><?php echo $label ?></label></th>
            <td>
                <?php
                wp_dropdown_pages(
                    array(
                        'name'             => 'saloon_settings[' . $key . ']',
                        'selected'         => $this->getOpt($key) ? $this->getOpt($key) : null,
                        'show_option_none' => 'Nessuna'
                    )
                )
                ?>       </td>
        </tr>


    <?php
    }

    public function showTabHomepage()
    {
        include dirname(__FILE__) . '/_settings_homepage.php';
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
        include dirname(__FILE__) . '/_settings_general.php';
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
        include dirname(__FILE__) . '/_settings_booking.php';
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
                     'availabilities'
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
        include dirname(__FILE__) . '/_settings_payments.php';
    }

    public function processTabPayments()
    {
        foreach (array(
                     'pay_currency',
                     'pay_paypal_email',
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
                $method = "showTab" . ucwords($current);
                if (!method_exists($this, $method)) {
                    throw new \Exception('method not found ' . $method);
                }
                $this->$method();
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
        echo '<h2 class="nav-tab-wrapper">';
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