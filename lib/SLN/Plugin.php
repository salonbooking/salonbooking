<?php

class SLN_Plugin
{
    const POST_TYPE_SERVICE = 'sln_service';
    const POST_TYPE_BOOKING = 'sln_booking';
    const TEXT_DOMAIN = 'sln';

    private static $instance;
    private $settings;
    private $services;
    private $formatter;

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
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        register_activation_hook(__FILE__, array('SLN_Action_Install', 'execute'));
        new SLN_PostType_Service($this, self::POST_TYPE_SERVICE);
        new SLN_PostType_Booking($this, self::POST_TYPE_BOOKING);
    }

    private function initAdmin()
    {
        new SLN_Metabox_Service($this, self::POST_TYPE_SERVICE);
        new SLN_Metabox_Booking($this, self::POST_TYPE_BOOKING);
        new SLN_Admin_Settings($this);
        add_action('admin_notices', array($this, 'admin_notices'));
    }

    public function action_init()
    {
        load_plugin_textdomain(self::TEXT_DOMAIN, false, '/saloon/languages');
        wp_enqueue_style('saloon', SLN_PLUGIN_URL . '/css/saloon.css', array(), SLN_VERSION, 'all');
        wp_enqueue_style('bootstrap', SLN_PLUGIN_URL . '/css/bootstrap.min.css', array(), SLN_VERSION, 'all');
        wp_enqueue_script('saloon', SLN_PLUGIN_URL . '/js/saloon.js', array('jquery'), '20140711', true);
        SLN_Shortcode_Saloon::init($this);
    }

    public function admin_enqueue_scripts()
    {
        wp_enqueue_script('saloon-admin-js', SLN_PLUGIN_URL . '/js/admin.js', array('jquery'), '20140711', true);
        wp_enqueue_style('saloon-admin-css', SLN_PLUGIN_URL . '/css/admin.css', array(), SLN_VERSION, 'all');
    }

    /** @return SLN_Settings */
    public function getSettings()
    {
        if (!isset($this->settings)) {
            $this->settings = new SLN_Settings();
        }

        return $this->settings;
    }

    public function createService($service)
    {
        if (is_int($service)) {
            $service = get_post($service);
        }

        return new SLN_Wrapper_Service($service);
    }

    public function createBooking($booking)
    {
        if (is_int($booking)) {
            $booking = get_post($booking);
        }

        return new SLN_Wrapper_Booking($booking);
    }

    public function getBookingBuilder()
    {
        return new SLN_Wrapper_Booking_Builder($this);
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getServices()
    {
        if (!isset($this->services)) {
            $query = new WP_Query(
                array(
                    'post_type' => self::POST_TYPE_SERVICE,
                    'nopaging'  => true
                )
            );
            $ret   = array();
            foreach ($query->get_posts() as $p) {
                $ret[] = $this->createService($p);
            }
            wp_reset_query();
            wp_reset_postdata();
            $this->services = $ret;
        }

        return $this->services;
    }

    public function admin_notices()
    {
        if ($_GET['sln-dismiss'] == 'dismiss_admin_notices') {
            $this->getSettings()
                ->setNoticesDisabled(true)
                ->save();
        }
        if (!$this->getSettings()->getNoticesDisabled()) {
            $dismissUrl = add_query_arg(array('sln-dismiss' => 'dismiss_admin_notices'));
            echo $this->loadView('admin_notices', compact('dismissUrl'));
        }
    }

    public function getTextDomain()
    {
        return self::TEXT_DOMAIN;
    }

    public function getViewFile($view)
    {
        return SLN_PLUGIN_DIR . '/views/' . $view . '.php';
    }

    public function loadView($view, $data)
    {
        ob_start();
        extract($data);
        $plugin = $this;
        include $this->getViewFile($view);

        return ob_get_clean();
    }

    /**
     * @return SLN_Formatter
     */
    public function format()
    {
        if (!isset($this->formatter)) {
            $this->formatter = new SLN_Formatter($this);
        }

        return $this->formatter;
    }
}
