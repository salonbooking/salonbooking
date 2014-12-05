<?php

class SLN_Plugin
{
    private static $instance;
    private $settings;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->init();
        if (is_admin()) {
            $this->initAdmin();
        }
    }

    private function init()
    {
        add_action('init', array($this, 'action_init'));
        register_activation_hook(__FILE__, array('SLN_Action_Install', 'execute'));
        new SLN_PostType_Service();
        new SLN_PostType_Booking();
    }

    private function initAdmin()
    {
        new SLN_Metabox_Service($this);
        new SLN_Metabox_Booking($this);
        new SLN_Admin_Settings($this);
    }

    public function action_init()
    {
        load_plugin_textdomain('sln', false, '/saloon/languages');
        wp_enqueue_style('saloon', SLN_PLUGIN_URL . '/css/saloon.css', array(), SLN_VERSION, 'all');
        wp_enqueue_script('saloon', SLN_PLUGIN_URL . '/js/saloon.js', array('jquery'), '20140711', true);
        SLN_Shortcode_Saloon::init($this);
    }

    public function getSettings()
    {
        if (!isset($this->settings)) {
            $this->settings = new SLN_Settings();
        }

        return $this->settings;
    }

    public function createService($service)
    {
        return new SLN_Wrapper_Service($service);
    }

    public function createBooking($booking)
    {
        return new SLN_Wrapper_Booking($booking);
    }
}