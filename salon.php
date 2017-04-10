<?php

/*
  Plugin Name: Salon Booking Wordpress Plugin - Free Version
  Description: Let your customers book you services through your website. Perfect for hairdressing salons, barber shops and beauty centers.
  Version: 3.11.1
  Plugin URI: http://salon.wpchef.it/
  Author: Wordpress Chef / Plugins
  Author URI: http://salon.wpchef.it/
  Text Domain: salon-booking-system
  Domain Path: /languages
 */

define('SLN_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SLN_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('SLN_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
define('SLN_VERSION', '3.11.1');

function sln_autoload($className) {
    if (strpos($className, 'SLN_') === 0) {
        $filename = SLN_PLUGIN_DIR . "/src/" . str_replace("_", "/", $className) . '.php';
        if (file_exists($filename)) {
            include_once($filename);
        }
    }
}

function my_update_notice() {
    $info = __('-', 'salon-booking-system');
    echo '<span class="spam">' . strip_tags($info, '<br><a><b><i><span>') . '</span>';
}

if (is_admin())
    add_action('in_plugin_update_message-' . plugin_basename(__FILE__), 'my_update_notice');

spl_autoload_register('sln_autoload');
load_plugin_textdomain(SLN_Plugin::TEXT_DOMAIN, false, dirname(SLN_PLUGIN_BASENAME).'/languages');
$sln_plugin = SLN_Plugin::getInstance();
do_action('sln.init', $sln_plugin);
//TODO[feature-gcalendar]: move this require in the right place
require_once SLN_PLUGIN_DIR . "/src/SLN/Third/GoogleScope.php";
$sln_googlescope = new SLN_GoogleScope();
$GLOBALS['sln_googlescope'] = $sln_googlescope;
$sln_googlescope->set_settings_by_plugin(SLN_Plugin::getInstance());
$sln_googlescope->wp_init();

ob_start();


