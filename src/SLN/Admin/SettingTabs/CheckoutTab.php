<?php 	
class SLN_Admin_SettingTabs_CheckoutTab extends SLN_Admin_SettingTabs_AbstractTab
{
    protected $fields = array(
        'enabled_guest_checkout',
        'enabled_force_guest_checkout',
        'enabled_fb_login',
        'fb_app_id',
        'services_count',
        'enable_discount_system',
        'checkout_fields',
    );
}
 ?>