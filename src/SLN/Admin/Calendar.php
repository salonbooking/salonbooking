<?php

class SLN_Admin_Calendar extends SLN_Admin_AbstractPage
{
    const PAGE = 'salon';
    const PRIORITY = 0;

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

        $this->classicAdminMenu(
            __('Salon Calendar', 'salon-booking-system'),
            __('Calendar', 'salon-booking-system')
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
        parent::enqueueAssets();
        $locale = str_replace('_', '-', get_locale());
        $av = SLN_Action_InitScripts::ASSETS_VERSION;
        if($locale != 'en-US') {
            wp_enqueue_script(
                'salon-calendar-language',
                sprintf(SLN_PLUGIN_URL.'/js/calendar_language/%s.js', $locale),
                array('jquery'),
                $av,
                true
            );
        }
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
            SLN_PLUGIN_URL.'/js/admin/customCalendar.js',
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
