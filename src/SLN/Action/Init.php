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
        if (!SLN_Action_Install::isInstalled()) {
            register_activation_hook(SLN_PLUGIN_BASENAME, array('SLN_Action_Install', 'execute'));
        }
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

        $this->initSchedules();

        add_action('template_redirect', array($this, 'template_redirect'));

        new SLN_Action_InitScripts($this->plugin, is_admin());
        $this->initPolylangSupport();

        $enableDiscountSystem = $p->getSettings()->get('enable_discount_system');
        if ($enableDiscountSystem) {
            SLB_Discount_Plugin::getInstance();
        }
    }


    private function initAdmin()
    {
        $p = $this->plugin;
        new SLN_Metabox_Service($p, SLN_Plugin::POST_TYPE_SERVICE);
        new SLN_Metabox_Attendant($p, SLN_Plugin::POST_TYPE_ATTENDANT);
        new SLN_Metabox_Booking($p, SLN_Plugin::POST_TYPE_BOOKING);
        new SLN_Metabox_BookingActions($p, SLN_Plugin::POST_TYPE_BOOKING);

        new SLN_Admin_Calendar($p);
        new SLN_Admin_Tools($p);
        new SLN_Admin_Customers($p);
        new SLN_Admin_Reports($p);
        new SLN_Admin_Settings($p);

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

    private function initSchedules() {
        add_filter('cron_schedules', array($this, 'cron_schedules'));

        if (!wp_get_schedule('sln_email_weekly_report')) {
            SLN_TimeFunc::startRealTimezone();
            if (((int)current_time('w')) === (SLN_Enum_DaysOfWeek::MONDAY) &&
                SLN_Func::getMinutesFromDuration(current_time('H:i')) < 8*60) {

                $time  = current_time('timestamp');
                $time -= $time % (24*60*60);
            }
            else {
                $time  = strtotime("next Monday");
            }

            $time += 8 * 60 * 60; // Monday 8:00
            wp_schedule_event($time, 'weekly', 'sln_email_weekly_report');
            unset($time);
            SLN_TimeFunc::endRealTimezone();
        }

        add_action('sln_sms_reminder', 'sln_sms_reminder');
        add_action('sln_email_reminder', 'sln_email_reminder');
        add_action('sln_sms_followup', 'sln_sms_followup');
        add_action('sln_email_followup', 'sln_email_followup');
        add_action('sln_email_feedback', 'sln_email_feedback');
        add_action('sln_cancel_bookings', 'sln_cancel_bookings');
        add_action('sln_email_weekly_report', 'sln_email_weekly_report');
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
        SLN_Shortcode_SalonCalendar::init($p);
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
        $customerHash = isset($_GET['sln_customer_login']) ? $_GET['sln_customer_login'] : '';
        $feedback_id = isset($_GET['feedback_id']) ? $_GET['feedback_id'] : '';
        if (!empty($customerHash)) {
            $userid = SLN_Wrapper_Customer::getCustomerIdByHash($customerHash);
            if ($userid) {
                $user = get_user_by('id', (int) $userid);
                if ($user) {
                    $customer = new SLN_Wrapper_Customer($user);
                    if (!$customer->isEmpty()) {
                        wp_set_auth_cookie($user->ID, false);
                        do_action('wp_login', $user->user_login, $user);

                        // Create redirect URL without autologin code
                        $id = $this->plugin->getSettings()->getBookingmyaccountPageId();
                        if ($id) {
                            $url = get_permalink($id);
                            if(!empty($feedback_id)) {
                                $url .= '?feedback_id='. $feedback_id;
                            }
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

    public function cron_schedules($schedules) {
        $schedules['weekly'] = array(
            'interval' => 60 * 60 * 24 * 7,
            'display' => __('Weekly', 'salon-booking-system')
        );

        return $schedules;
    }
}
