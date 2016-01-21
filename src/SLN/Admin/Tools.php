<?php

class SLN_Admin_Tools
{
    const PAGE = 'salon-tools';

    protected $plugin;
    protected $settings;
    public $settings_page = '';
    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin   = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('admin_menu', array($this, 'admin_menu') );
    }

    public function admin_menu()
    {
        $this->settings_page = add_submenu_page(
            'salon',
            __('Salon Tools', 'salon-booking-system'),
            __('Tools', 'salon-booking-system'),
            apply_filters('salonviews/settings/capability', 'manage_salon'),
            self::PAGE,
            array($this, 'show')
        );
    }

    public function show()
    {
        echo $this->plugin->loadView(
            'admin/tools',
            array(
                'info' => print_r(get_option(SLN_Settings::KEY), true)
            )
        );
    }
}
