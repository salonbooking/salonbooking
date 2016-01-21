<?php

class SLN_Admin_Calendar
{
    const PAGE = 'salon-calendar';

    protected $plugin;
    protected $settings;
    public $settings_page = '';
    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin   = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('admin_menu', array($this, 'admin_menu'), 0 ); //algol plus add first
    }

    public function admin_menu()
    {
        add_menu_page(
                __('Salon', 'salon-booking-system'), __('Salon', 'salon-booking-system'), 'manage_options', 'salon', array($this, 'show'), SLN_PLUGIN_URL . '/img/admin_icon.png'
        );
        $this->settings_page = add_submenu_page(
            'salon',
            __('Salon Calendar', 'salon-booking-system'),
            __('Calendar', 'salon-booking-system'),
            apply_filters('salonviews/settings/capability', 'manage_salon'),
            'salon',// algolplus SAME slug to replace submenu Salon with Calendar
            array($this, 'show')
        );

    }

    public function show()
    {
        echo $this->plugin->loadView(
            'admin/calendar',
            array(
'x' => 'x'
            )
        );
    }
}
