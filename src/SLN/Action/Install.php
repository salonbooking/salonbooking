<?php

class SLN_Action_Install
{
    /** @var array DB updates that need to be run */
    private static $db_updates = array(
        '2.2.1' => 'Updates/sln-update-2.2.1.php',
    );

    public static function init()
    {
        if (!empty($_GET['do_update_sln'])) {
            self::update();
        }
    }

    public static function execute($force = false)
    {
        $data = require SLN_PLUGIN_DIR . '/_install_data.php';
        $ids  = array();
        foreach ($data['posts'] as $label => $post) {
            if (!self::checkPost($post['post']['post_title'], $post['post']['post_type'])) {
                $id = wp_insert_post($post['post']);
                if (isset($post['meta'])) {
                    foreach ($post['meta'] as $k => $v) {
                        add_post_meta($id, $k, $v);
                    }
                }
                $ids[$label] = $id;
            }
        }
        if (!get_option(SLN_Settings::KEY)) {
            if (isset($ids['thankyou'])) {
                $data['settings']['thankyou'] = $ids['thankyou'];
            }
            if (isset($ids['booking'])) {
                $data['settings']['booking'] = $ids['booking'];
                $data['settings']['pay'] = $ids['pay'];
            }

            update_option(SLN_Settings::KEY, $data['settings']);
        }

        new SLN_UserRole_SalonStaff(SLN_Plugin::getInstance(), SLN_Plugin::USER_ROLE_STAFF, __('Salon staff', 'salon-booking-system'));
        new SLN_UserRole_SalonCustomer(SLN_Plugin::getInstance(), SLN_Plugin::USER_ROLE_CUSTOMER, __('Salon customer', 'salon-booking-system'));
        self::updateVersion();
    }

    /**
     * Update salon-booking-system version to current.
     *
     * @param string|null $version
     */
    private static function updateVersion($version = null)
    {
        update_option('salon-booking-system-version', is_null($version) ? SLN_VERSION : $version);
    }

    /**
     * Handle updates.
     */
    private static function update()
    {
        $current_version = get_option('salon-booking-system-version', '0.0.0');

        foreach (self::$db_updates as $version => $updater) {
            if (version_compare($current_version, $version, '<')) {
                include($updater);
                self::updateVersion($version);
            }
        }

        self::updateVersion();
    }

    private static function checkPost($title, $post_type)
    {
        return get_page_by_title($title, null, $post_type) ? true : false;
    }
}
