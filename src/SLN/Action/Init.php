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
        add_action('sln_sms_followup', 'sln_sms_followup');
        add_action('sln_email_followup', 'sln_email_followup');
        add_action('sln_cancel_bookings', 'sln_cancel_bookings');

        add_action('template_redirect', array($this, 'template_redirect'));

        new SLN_Action_InitScripts($this->plugin, is_admin());
        $this->initPolylangSupport();
    }


    private function initAdmin()
    {
        $p = $this->plugin;
        new SLN_Metabox_Service($p, SLN_Plugin::POST_TYPE_SERVICE);
        new SLN_Metabox_Attendant($p, SLN_Plugin::POST_TYPE_ATTENDANT);
        new SLN_Metabox_Booking($p, SLN_Plugin::POST_TYPE_BOOKING);
        new SLN_Metabox_BookingActions($p, SLN_Plugin::POST_TYPE_BOOKING);
        new SLN_Admin_Customers($p);
        new SLN_Admin_Reports($p);
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
        SLN_Shortcode_Salon::init($p);
        SLN_Shortcode_SalonMyAccount::init($p);
        SLN_Shortcode_SalonMyAccount_Details::init($p);
        SLN_Shortcode_BookingCalendar::init($p);
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

    public function initPolylangSupport()
    {
        add_filter('pll_get_post_types', array($this, 'hook_pll_get_post_types'));
    }

    public function hook_pll_get_post_types($types)
    {
        unset ($types['sln_booking']);
        //decomment this to have "single language services and attendant
        //unset($types['sln_service']);
        //unset($types['sln_attendant']);

        return $types;
    }

    public function template_redirect() {
        global $wp_query;

        $name = $wp_query->get('name');

        if (!empty($name)) {
            $userid = SLN_Wrapper_Customer::getCustomerIdByHash($name);
            if ($userid) {
                $user = get_user_by('id', (int) $userid);
                if ($user) {
                    $customer = new SLN_Wrapper_Customer($user);
                    if (!$customer->isEmpty()) {
                        wp_set_auth_cookie($user->ID, false);
                        do_action('wp_login', $user->user_login, $user);

                        // Create redirect URL without autologin code
                        $id = $this->plugin->getSettings()->get('pay');
                        if ($id) {
                            $url = get_permalink($id);
                        }else{
                            $url = home_url();
                        }

                        wp_redirect($url);
                        exit;
                    }
                }
            }
        }
    }
}
