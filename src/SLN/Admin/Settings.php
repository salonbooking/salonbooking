<?php

class SLN_Admin_Settings
{

    const PAGE = 'salon-settings';

    protected $plugin;    
    private $tabs = array(
        'homepage' => 'Home',
        'general' => 'General',
        'booking' => 'Booking Rules',
        'checkout' => 'Checkout',
        'payments' => 'Payments',
        'style' => 'Style',
        'gcalendar' => 'Google Calendar',
        'documentation' => 'Support',
    );

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->tabs = apply_filters('sln.settings.tabs', $this->tabs, $plugin);
        add_action('admin_menu', array($this, 'admin_menu'), 12);
        $this->addTabHooks();
    }

    public function admin_menu()
    {
        $pagename = add_submenu_page(
            'salon',
            __('Salon Settings', 'salon-booking-system'),
            __('Settings', 'salon-booking-system'),
            apply_filters('salonviews/settings/capability', 'manage_options'),
            self::PAGE,
            array($this, 'show')
        );
        add_action('load-'.$pagename, array($this, 'enqueueAssets'));
    }

    public function show()
    {
        $current = $this->getCurrentTab();
        if(!in_array($current,array_keys($this->tabs))){
          throw new Exception('Tab with slug '.$current.' not registered');   
        }
        $class_name = 'SLN_Admin_SettingTabs_'.ucfirst($current).'Tab';
        if (!class_exists ( $class_name )) 
        {
                throw new Exception('Class '.$class_name.' is not existent');
        }
        if(!is_subclass_of( $class_name, 'SLN_Admin_SettingTabs_AbstractTab')){
            throw new Exception('Class '.$class_name.' not implement SLN_Admin_SettingTabs_AbstractTab');   
        }
        $tab = new $class_name($current, $this->tabs[$current],$this->plugin);
        ?>
        <div id="sln-salon--admin" class="wrap sln-bootstrap sln-salon--settings">
            <?php screen_icon(); ?>
            <div class="row">
                <div class="col-xs-12"><h2><?php _e('Salon Settings', 'salon-booking-system'); ?></h2></div>
                <div class="sln-admin-nav hidden-xs hidden-sm col-sm-12 col-md-8">
                    <ul class="sln-admin-nav">
                        <li><a href="admin.php?page=salon" class="sln-btn--icon sln-icon--calendar">Calendar</a></li>
                        <li><a href="edit.php?post_type=sln_booking"
                               class="sln-btn--icon sln-icon--booking">Bookings</a></li>
                        <li><a href="edit.php?post_type=sln_service"
                               class="sln-btn--icon sln-icon--services">Services</a></li>
                        <li><a href="edit.php?post_type=sln_attendant" class="sln-btn--icon sln-icon--assistants">Assistants</a>
                        </li>
                        <li class="current"><a href="admin.php?page=salon-settings"
                                               class="current sln-btn--icon sln-icon--settings">Settings</a></li>
                    </ul>
                </div>
            </div>

            <?php settings_errors(); ?>
            <?php $this->showTabsBar(); ?>
            <form method="post" action="<?php admin_url('admin.php?page='.self::PAGE); ?>"
                  enctype="multipart/form-data">
                <?php
                $tab->show();
                wp_nonce_field(self::PAGE.$current);
                if ($current != 'homepage') {
                    submit_button(esc_attr__('Update Settings', 'salon-booking-system'), 'primary');
                }
                ?>
            </form>

        </div><!-- wrap -->
        <?php
    }

    private function addTabHooks(){
        add_filter('sln.settings.payments.fields',array($this,'initGateways'));
    }

    private function showTabsBar()
    {
        echo '<h2 class="sln-nav-tab-wrapper nav-tab-wrapper">';
        $page = self::PAGE;
        $current = $this->getCurrentTab();
        foreach ($this->tabs as $tab => $name) {
            $class = ($tab == $current) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=$page&tab=$tab'>$name</a>";
        }
        echo '</h2>';
    }


    function getCurrentTab()
    {
        return isset($_GET['tab']) ? $_GET['tab'] : 'homepage';
    }


    public function enqueueAssets()
    {
        SLN_Action_InitScripts::enqueueTwitterBootstrap(true);
        SLN_Action_InitScripts::enqueueSelect2();
        SLN_Action_InitScripts::enqueueAdmin();
        wp_enqueue_script(
            'salon-customSettings',
            SLN_PLUGIN_URL.'/js/admin/customSettings.js',
            array('jquery'),
            SLN_Action_InitScripts::ASSETS_VERSION,
            true
        );

        if (isset($_GET['tab']) && $_GET['tab'] == 'style') {
            SLN_Action_InitScripts::enqueueColorPicker();
            wp_enqueue_script(
                'salon-customColors',
                SLN_PLUGIN_URL.'/js/admin/customColors.js',
                array('jquery'),
                SLN_Action_InitScripts::ASSETS_VERSION,
                true
            );
        }
    }    

    public function initGateways($fields)
    {
        foreach (SLN_Enum_PaymentMethodProvider::toArray() as $k => $v) {
            $fields = array_merge(
                $fields,
                SLN_Enum_PaymentMethodProvider::getService($k, $this->plugin)->getFields()
            );
        }
        return $fields;
    }
    
}
