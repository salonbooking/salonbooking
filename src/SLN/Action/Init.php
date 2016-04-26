<?php

class SLN_Action_Init
{
    private $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->init();
        if (is_admin()) {
            $this->initAdmin();
        } else {
            $this->initFrontend();
        }
    }

    private function init()
    {
        $p = $this->plugin;
        add_action('init', array($this, 'hook_action_init'));
        register_activation_hook(SLN_PLUGIN_BASENAME, array('SLN_Action_Install', 'execute'));
        $this->plugin->addRepository(
            new SLN_Repository_BookingRepository(
                $this->plugin,
                new SLN_PostType_Booking($p, SLN_Plugin::POST_TYPE_BOOKING)
            )
        );

        $this->plugin->addRepository(
            new SLN_Repository_ServiceRepository(
                $this->plugin,
                new SLN_PostType_Service($p, SLN_Plugin::POST_TYPE_SERVICE)
            )
        );
        $this->plugin->addRepository(
            new SLN_Repository_AttendantRepository(
                $this->plugin,
                new SLN_PostType_Attendant($p, SLN_Plugin::POST_TYPE_ATTENDANT)
            )
        );

        new SLN_TaxonomyType_ServiceCategory(
            $p,
            SLN_Plugin::TAXONOMY_SERVICE_CATEGORY,
            array(SLN_Plugin::POST_TYPE_SERVICE)
        );
        
        add_action('sln_sms_reminder', 'sln_sms_reminder');
        add_action('sln_email_reminder', 'sln_email_reminder');

        new SLN_Action_InitScripts($this->plugin, is_admin());
    }


    private function initAdmin()
    {
        $p = $this->plugin;
        new SLN_Metabox_Service($p, SLN_Plugin::POST_TYPE_SERVICE);
        new SLN_Metabox_Attendant($p, SLN_Plugin::POST_TYPE_ATTENDANT);
        new SLN_Metabox_Booking($p, SLN_Plugin::POST_TYPE_BOOKING);
        new SLN_Metabox_BookingActions($p, SLN_Plugin::POST_TYPE_BOOKING);
        new SLN_Admin_Customers($p);
        new SLN_Admin_Settings($p);
        new SLN_Admin_Calendar($p);
        new SLN_Admin_Tools($p);

        add_action('admin_init', array($this, 'hook_admin_init'));
        add_action('admin_notices', array($this, 'hook_admin_notices'));
        $this->initAjax();
        new SLN_Action_InitComments($p);
    }

    private function initFrontend()
    {
    }

    private function initAjax()
    {
        $callback = array($this->plugin, 'ajax');
        //http://codex.wordpress.org/AJAX_in_Plugins
        add_action('wp_ajax_salon', $callback);
        add_action('wp_ajax_nopriv_salon', $callback);
        add_action('wp_ajax_saloncalendar', $callback);
    }

    public function hook_action_init()
    {
        if (!session_id()) {
            session_start();
        }
        $p = $this->plugin;
        load_plugin_textdomain(SLN_Plugin::TEXT_DOMAIN, false, dirname(SLN_PLUGIN_BASENAME).'/languages');
        SLN_Shortcode_Salon::init($p);
        SLN_Shortcode_SalonMyAccount::init($p);
        SLN_Shortcode_SalonMyAccount_Details::init($p);
    }

    public function hook_admin_init()
    {
        new SLN_Action_Update($this->plugin);
    }

    public function hook_admin_notices()
    {
        if (current_user_can('install_plugins')) {
            $s = $this->plugin->getSettings();
            if (isset($_GET['sln-dismiss']) && $_GET['sln-dismiss'] == 'dismiss_admin_notices') {
                $s->setNoticesDisabled(true)->save();
            }
            if (!$s->getNoticesDisabled()) {
                $dismissUrl = add_query_arg(array('sln-dismiss' => 'dismiss_admin_notices'));
                echo $this->plugin->loadView('admin_notices', compact('dismissUrl'));
            }
            $cnt = get_option(SLN_PLUGIN::F);
            if ($cnt > SLN_Plugin::F1) {
                echo $this->plugin->loadView('trial/admin_end');
            } elseif ($cnt > SLN_Plugin::F2) {
                echo $this->plugin->loadView('trial/admin_near');
            }
        }
    }

}
