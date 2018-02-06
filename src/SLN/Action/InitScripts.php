<?php

class SLN_Action_InitScripts
{
    const ASSETS_VERSION = '20180117';
    private $isAdmin;
    private $plugin;

    public function __construct(SLN_Plugin $plugin, $isAdmin)
    {
        $this->plugin  = $plugin;
        $this->isAdmin = $isAdmin;

        if ($isAdmin) {
            add_action('admin_enqueue_scripts', array($this, 'hook_enqueue_scripts'));
            add_action('wp_print_scripts', array($this, 'hook_admin_print_scripts'));
            if (SLN_Func::isSalonPage()) {
                add_filter('script_loader_src', array($this, 'hook_script_loader_src'), 10, 2);
                add_filter('style_loader_src', array($this, 'hook_script_loader_src'), 10, 2);
            }
        }
        add_action('wp_enqueue_scripts', array($this, 'hook_enqueue_scripts'), 99999);
    }

    public function hook_enqueue_scripts()
    {
        if ( ! $this->isAdmin) {
            self::preloadScripts();
            self::enqueueTwitterBootstrap(false);
            $this->preloadFrontendScripts();
        }
    }

    private static function preloadScripts()
    {
        wp_enqueue_script('salon', SLN_PLUGIN_URL.'/js/salon.js', array('jquery'), self::ASSETS_VERSION, true);
        $s = SLN_Plugin::getInstance()->getSettings();
        $lang = $s->getLocale();

        $params = array(
            'ajax_url'                  => admin_url('admin-ajax.php').'?lang='.$lang,
            'ajax_nonce'                => wp_create_nonce('ajax_post_validation'),
            'loading'                   => SLN_PLUGIN_URL.'/img/preloader.gif',
            'txt_validating'            => __('checking availability', 'salon-booking-system'),
            'images_folder'             => SLN_PLUGIN_URL.'/img',
            'confirm_cancellation_text' => __('Do you really want to cancel?', 'salon-booking-system'),
            'time_format'               => SLN_Enum_TimeFormat::getJSFormat(
                $s->get('time_format')
            ),
            'has_stockholm_transition'  => self::hasStockholmTransition() ? 'yes' : 'no',
            'checkout_field_placeholder' => __('fill this field', 'salon-booking-system'),
            'txt_close' => __('Close')
        );

        $fbLoginEnabled = SLN_Plugin::getInstance()->getSettings()->get('enabled_fb_login');
        $fbAppID        = SLN_Plugin::getInstance()->getSettings()->get('fb_app_id');
        if ($fbLoginEnabled && !empty($fbAppID)) {
            $params['fb_app_id'] = $fbAppID;
        }

        wp_localize_script(
            'salon',
            'salon',
            $params
        );
    }

    private static function enqueueSalonMyAccount(){
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
    }

    private static function hasStockholmTransition()
    {
        global $qode_options;

        return $qode_options && $qode_options['page_transitions'] > 0;
    }

    private static function preloadFrontendScripts()
    {
        self::enqueueDateTimePicker();
        if(in_array('select',SLN_Enum_CheckoutFields::$additional_fields_types,true) ) self::enqueueSelect2();
        wp_enqueue_style('salon', SLN_PLUGIN_URL.'/css/salon.css', array(), self::ASSETS_VERSION, 'all');
        if (SLN_Plugin::getInstance()->getSettings()->get('style_colors_enabled')) {
            $dir = wp_upload_dir();
            $dir = $dir['baseurl'];
            wp_enqueue_style('sln-custom', $dir.'/sln-colors.css', array(), self::ASSETS_VERSION, 'all');
        }
        self::enqueueSalonMyAccount();
    }

    public static function enqueueCustomSliderRange()
    {
        wp_enqueue_script(
            'salon-customSliderRange',
            SLN_PLUGIN_URL.'/js/admin/customSliderRange.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
        //100% we need this too
        wp_enqueue_script(
            'salon-customRulesCollections',
            SLN_PLUGIN_URL.'/js/admin/customRulesCollections.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );

    }

    public static function enqueueCustomMetaService()
    {
        wp_enqueue_script(
            'salon-customMetaService',
            SLN_PLUGIN_URL.'/js/admin/customMetaService.js',
            array('jquery'),
            SLN_Action_InitScripts::ASSETS_VERSION,
            true
        );
    }

    public static function enqueueCustomBookingUser()
    {
        wp_enqueue_script(
            'salon-customBookingUser',
            SLN_PLUGIN_URL.'/js/admin/customBookingUser.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
    }

    public static function enqueueTwitterBootstrap($force = true)
    {
        $s = SLN_Plugin::getInstance()->getSettings();
        if ($force || ! $s->get('no_bootstrap')) {
            wp_enqueue_style(
                'salon-bootstrap',
                SLN_PLUGIN_URL.'/css/sln-bootstrap.css',
                array(),
                self::ASSETS_VERSION,
                'all'
            );
        }
        if ($force || ! $s->get('no_bootstrap_js')) {
            wp_enqueue_script(
                'salon-bootstrap',
                SLN_PLUGIN_URL.'/js/bootstrap.min.js',
                array('jquery'),
                self::ASSETS_VERSION,
                true
            );
        }
    }

    public static function enqueueDateTimePicker()
    {
        $date_lang = defined('ICL_LANGUAGE_CODE')
            ? ICL_LANGUAGE_CODE
            : (function_exists('pll_current_language')
                ? pll_current_language()
                : get_locale()
        );
        
        wp_enqueue_script(
            'smalot-datepicker',
            SLN_PLUGIN_URL.'/js/bootstrap-datetimepicker.js',
            array('jquery'),
            '20140711',
            true
        );
        if ($date_lang != 'en') {
            wp_enqueue_script(
                'smalot-datepicker-lang',
                SLN_PLUGIN_URL.'/js/datepicker_language/bootstrap-datetimepicker.'.$date_lang.'.js',
                array('jquery','smalot-datepicker'),
                '2016-02-16',
                true
            );
        }
    }

    public static function enqueueColorPicker(){
        // COLOR PICKER
        wp_enqueue_script(
            'salon-colorpicker-js',
            SLN_PLUGIN_URL.'/js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
        wp_enqueue_style(
            'salon-colorpicker-css',
            SLN_PLUGIN_URL.'/js/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
            array(),
            self::ASSETS_VERSION,
            'all'
        );

    }

    public static function enqueueSelect2(){

        wp_enqueue_script('jqueryUi', SLN_PLUGIN_URL.'/js/select2.min.js?scope=sln', array('jquery'), true);
        wp_enqueue_script(
            'salon-admin-select2',
            SLN_PLUGIN_URL.'/js/jquery-ui.min.js',
            array('jquery'),
            true
        );
        wp_enqueue_style('salon-admin-select2-css', SLN_PLUGIN_URL.'/css/select2.min.css?scope=sln', array(), SLN_VERSION, 'all');
        wp_enqueue_script(
            'salon-customSelect2',
            SLN_PLUGIN_URL.'/js/admin/customSelect2.js?scope=sln',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
    }

    public static function enqueueAdmin(){
        self::preloadScripts();
        self::enqueueDateTimePicker();
        wp_enqueue_script(
            'salon-customDateTime',
            SLN_PLUGIN_URL.'/js/admin/customDateTime.js',
            array('jquery'),
            self::ASSETS_VERSION,
            true
        );
        wp_enqueue_style('salon-admin-css', SLN_PLUGIN_URL.'/css/admin.css', array(), SLN_VERSION, 'all');
        wp_enqueue_script('salon-admin-js', SLN_PLUGIN_URL.'/js/admin.js', array('jquery'), self::ASSETS_VERSION, true);
        wp_localize_script( 'salon-admin-js', 'salon_admin', array(
            'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'salon-booking-system' ), SLN_Plugin::getInstance()->getSettings()->get('pay_decimal_separator') ),
            'mon_decimal_point'                 => SLN_Plugin::getInstance()->getSettings()->get('pay_decimal_separator'),

        ) );
    }
    public function hook_admin_print_scripts(){

        if(
            (is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php'))
            && SLN_Func::isSalonPage()
        ) {
        //if(isdefined('WPSEO_VERSION') && SLN_Func::isSalonPage()) {
            self::dequeueYoast();
        }
    }

    public function hook_script_loader_src($src, $handle) {
        if (!preg_match('/\/woocommerce\//', $src) && !preg_match('/\/select2\./', $src) || preg_match('/scope=sln/', $src)) {
            return $src;
        }
    }

    public static function dequeueYoast()
    {
        $scripts = array(
'yoast-social-preview', 'wp-seo-premium-redirect-notifications', 'wp-seo-premium-custom-fields-plugin', 'yoast-seo-premium-metabox', 'yoast-seo-admin-script', 'yoast-seo-admin-media','yoast-seo-bulk-editor','yoast-seo-dismissible','yoast-seo-admin-global-script','yoast-seo-metabox','yoast-seo-featured-image','yoast-seo-admin-gsc','yoast-seo-post-scraper','yoast-seo-term-scraper','yoast-seo-replacevar-plugin','yoast-seo-shortcode-plugin','yoast-seo-recalculate','yoast-seo-primary-category','yoast-seo-select2','yoast-seo-select2-translations','yoast-seo-configuration-wizard');
        foreach($scripts as $s) {
            wp_dequeue_script($s);
        }
    }
}
