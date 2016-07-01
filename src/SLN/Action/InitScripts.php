<?php

class SLN_Action_InitScripts
{
    const ASSETS_VERSION = '20160404';
    private $isAdmin;
    private $plugin;

    public function __construct(SLN_Plugin $plugin, $isAdmin)
    {
        $this->plugin = $plugin;
        $this->isAdmin = $isAdmin;

        if ($isAdmin) {
            add_action('admin_enqueue_scripts', array($this, 'hook_enqueue_scripts'));
        }
        add_action('wp_enqueue_scripts', array($this, 'hook_enqueue_scripts'));
    }

    public function hook_enqueue_scripts()
    {
        $this->preloadScripts();

        if (!$this->isAdmin) {
            $this->preloadFrontendScripts();
        } else {
            $this->preloadAdminScripts();
        }
    }

    private function preloadScripts()
    {
        if (!$this->plugin->getSettings()->get('no_bootstrap')) {
            wp_enqueue_style(
                'salon-bootstrap',
                SLN_PLUGIN_URL.'/css/sln-bootstrap.css',
                array(),
                self::ASSETS_VERSION,
                'all'
            );
        }

        //        wp_enqueue_style('bootstrap', SLN_PLUGIN_URL . '/css/bootstrap.min.css', array(), SLN_VERSION, 'all');
        //       wp_enqueue_style('bootstrap', SLN_PLUGIN_URL . '/css/bootstrap.css', array(), SLN_VERSION, 'all');
        $lang = strtolower(substr(get_locale(), 0, 2));
        wp_enqueue_script(
            'smalot-datepicker',
            SLN_PLUGIN_URL.'/js/bootstrap-datetimepicker.js',
            array('jquery'),
            '20140711',
            true
        );
        if ($lang != 'en') {
            wp_enqueue_script(
                'smalot-datepicker-lang',
                SLN_PLUGIN_URL.'/js/datepicker_language/bootstrap-datetimepicker.'.$lang.'.js',
                array('jquery'),
                '2016-02-16',
                true
            );
        }
        wp_enqueue_script('salon', SLN_PLUGIN_URL.'/js/salon.js', array('jquery'), self::ASSETS_VERSION, true);
        // COLOR PICKER
        wp_enqueue_script(
            'salon-colorpicker-js',
            SLN_PLUGIN_URL.'/bower_components/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
        wp_enqueue_style(
            'salon-colorpicker-css',
            SLN_PLUGIN_URL.'/bower_components/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css',
            array(),
            self::ASSETS_VERSION,
            'all'
        );
        // COLOR PICKER // END
        wp_enqueue_script(
            'salon-bootstrap',
            SLN_PLUGIN_URL.'/js/bootstrap.min.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
        wp_enqueue_script(
            'salon-my-account',
            SLN_PLUGIN_URL.'/js/salon-my-account.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
        wp_enqueue_script(
            'salon-raty',
            SLN_PLUGIN_URL.'/js/jquery.raty.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
        wp_localize_script(
            'salon',
            'salon',
            array(
                'ajax_url' => admin_url('admin-ajax.php').'?lang='.(defined(
                        'ICL_LANGUAGE_CODE'
                    ) ? 'ICL_LANGUAGE_CODE' : $lang),
                'ajax_nonce' => wp_create_nonce('ajax_post_validation'),
                'loading' => SLN_PLUGIN_URL.'/img/preloader.gif',
                'txt_validating' => __('checking availability', 'salon-booking-system'),
                'images_folder' => SLN_PLUGIN_URL.'/img',
                'confirm_cancellation_text' => __('Do you really want to cancel?', 'salon-booking-system'),
                'time_format' => SLN_Enum_TimeFormat::getJSFormat($this->plugin->getSettings()->get('time_format')),
            )
        );
    }

    private function preloadAdminScripts()
    {
        wp_enqueue_script('jqueryUi', SLN_PLUGIN_URL.'/js/select2.min.js', array('jquery'), true);
        wp_enqueue_script(
            'salon-admin-select2',
            'http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js',
            array('jquery'),
            true
        );
        wp_enqueue_script('salon-admin-js', SLN_PLUGIN_URL.'/js/admin.js', array('jquery'), self::ASSETS_VERSION, true);
        wp_enqueue_style('salon-admin-css', SLN_PLUGIN_URL.'/css/admin.css', array(), SLN_VERSION, 'all');
        wp_enqueue_style('salon-admin-select2-css', SLN_PLUGIN_URL.'/css/select2.min.css', array(), SLN_VERSION, 'all');
    }

    private function preloadFrontendScripts()
    {
        wp_enqueue_style('salon', SLN_PLUGIN_URL.'/css/salon.css', array(), self::ASSETS_VERSION, 'all');
        wp_enqueue_style('sln-custom', SLN_PLUGIN_URL.'/css/sln-colors--custom.css', array(), self::ASSETS_VERSION, 'all');
    }
    
    public static function enqueueCustomSliderRange(){
        wp_enqueue_script(
            'salon-customSliderRange',
            SLN_PLUGIN_URL.'/js/customSliderRange.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
    }

    public static function enqueueCustomBookingUser(){
        wp_enqueue_script(
            'salon-customBookingUser',
            SLN_PLUGIN_URL.'/js/customBookingUser.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
    }
}