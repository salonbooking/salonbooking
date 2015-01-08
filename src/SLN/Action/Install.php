<?php

class SLN_Action_Install
{
    public static function execute($force = false)
    {
        $data = require SLN_PLUGIN_DIR . '/_install_data.php';
        $ids  = array();
        foreach ($data['posts'] as $label => $post) {
            if (!self::checkPost($post['post']['post_title'], $post['post']['post_type'])) {
                $id = wp_insert_post($post['post']);
                foreach ($post['post']['meta'] as $k => $v) {
                    update_post_meta($id, $k, $v);
                }
                $ids[$label] = $id;
            }
        }
        if (!get_option('salon_settings')) {
            if ($ids['thankyou']) {
                $data['settings']['thankyou'] = $ids['thankyou'];
            }
            if ($ids['booking']) {
                $data['settings']['booking'] = $ids['booking'];
            }
            update_option(SLN_Settings::KEY, $data['settings']);
        }
    }

    private static function checkPost($title, $post_type)
    {
        return get_page_by_title($title, null, $post_type) ? true : false;
    }
}
