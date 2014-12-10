<?php

class SLN_Plugin
{
    const POST_TYPE_SERVICE = 'sln_service';
    const POST_TYPE_BOOKING = 'sln_booking';
    const TEXT_DOMAIN = 'sln';

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
        if (is_admin()) {
            wp_enqueue_script('saloon', SLN_PLUGIN_URL . '/js/admin.js', array('jquery'), '20140711', true);
            wp_enqueue_style('saloon', SLN_PLUGIN_URL . '/css/admin.css', array(), SLN_VERSION, 'all');
        } else {
            wp_enqueue_style('saloon', SLN_PLUGIN_URL . '/css/saloon.css', array(), SLN_VERSION, 'all');
            wp_enqueue_script('saloon', SLN_PLUGIN_URL . '/js/saloon.js', array('jquery'), '20140711', true);
        }
        SLN_Shortcode_Saloon::init($this);

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
        return new SLN_Wrapper_Service($service);
    }

    public function createBooking($booking)
    {
        return new SLN_Wrapper_Booking($booking);
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getServices()
    {
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


        return $ret;
    }

    public function admin_notices()
    {
        if ($_GET['sln-dismiss'] == 'dismiss_admin_notices') {
            $this->getSettings()
                ->setNoticesDisabled(true)
                ->save();
        }
        if (!$this->getSettings()->getNoticesDisabled()) {
            ?>
            <div id="sln-setting-error" class="updated settings-error">
                <h3>Saloon booking plugin has been activated</h3>

                <p>A new <strong>"Saloon"</strong> section has been created into the left sidebar<br/>
                    There you can find <strong>"Services", "Bookings", "Settings"</strong> sections<br/>
                    We have also created "services" and "bookings" for demo purpose. Feel free to delete them<br/>
                    Go to "Saloon > Settings" section in order to set your own saloon booking system</p>

                <p>
                    <strong><a class="dismiss-notice"
                               href="<?php echo add_query_arg(array('sln-dismiss' => 'dismiss_admin_notices')) ?>">
                            Dismiss this notice</a></strong>
                </p>
            </div>
        <?php
        }
    }

    public function getTextDomain()
    {
        return self::TEXT_DOMAIN;
    }
}
