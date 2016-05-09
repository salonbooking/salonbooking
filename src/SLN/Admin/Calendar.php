<?php

class SLN_Admin_Calendar
{
    const PAGE = 'salon';

    protected $plugin;
    protected $settings;
    public $settings_page = '';

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('load-toplevel_page_'.self::PAGE, array($this, 'enqueueAssets'), 0);
        add_action('admin_menu', array($this, 'admin_menu'), 0);
    }


    public function admin_menu()
    {
        add_menu_page(
            __('Salon', 'salon-booking-system'),
            __('Salon', 'salon-booking-system'),
            'manage_salon',
            'salon',
            array($this, 'show'),
            SLN_PLUGIN_URL.'/img/admin_icon.png'
        );
        $this->settings_page = add_submenu_page(
            'salon',
            __('Salon Calendar', 'salon-booking-system'),
            __('Calendar', 'salon-booking-system'),
            apply_filters('salonviews/settings/capability', 'manage_salon'),
            self::PAGE,
            array($this, 'show')
        );
    }

    public function show()
    {
        echo $this->plugin->loadView(
            'admin/calendar',
            array(
                'x' => 'x',
            )
        );
    }

    public function enqueueAssets(){
        $locale = str_replace('_', '-', get_locale());
        $av = SLN_Action_InitScripts::ASSETS_VERSION;
        wp_enqueue_script(
            'salon-calendar-language',
            sprintf(SLN_PLUGIN_URL.'/js/calendar_language/%s.js', $locale),
            array('jquery'),
            $av,
            true
        );
        wp_enqueue_script(
            'salon-bootstrap',
            SLN_PLUGIN_URL.'/js/bootstrap.min.js',
            array('jquery'),
            $av,
            true
        );
        wp_enqueue_script(
            'salon-underscore',
            SLN_PLUGIN_URL.'/js/underscore-min.js',
            array('jquery'),
            $av,
            true
        );
        wp_enqueue_script(
            'salon-moment',
            SLN_PLUGIN_URL.'/js/moment.min.js',
            array('jquery'),
            $av,
            true
        );
        wp_enqueue_script(
            'salon-calendar-app',
            SLN_PLUGIN_URL.'/js/calendar-app.js',
            array('jquery'),
            $av,
            true
        );
        wp_enqueue_script(
            'salon-calendar',
            SLN_PLUGIN_URL.'/js/calendar.js',
            array('jquery'),
            $av,
            true
        );
        wp_enqueue_style(
            'salon-calendar',
            SLN_PLUGIN_URL.'/css/calendar.css',
            array(),
            $av,
            'all'
        );
    }
}
