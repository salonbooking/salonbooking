<?php

/*
  Plugin Name: Salon Booking Wordpress Plugin - Free Version
  Description: Let your customers book you services through your website. Perfect for hairdressing salons, barber shops and beauty centers.
  Version: 1.1.0
  Plugin URI: http://salon.wpchef.it/
  Author: Wordpress Chef / Plugins
  Author URI: http://plugins.wpchef.it/
  Text Domain: sln
 */


define('SLN_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SLN_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('SLN_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
define('SLN_VERSION', '1.1.0');

function sln_autoload($className) {
    if (strpos($className, 'SLN_') === 0) {
        include_once(SLN_PLUGIN_DIR . "/src/" . str_replace("_", "/", $className) . '.php');
    }
}

function my_update_notice() {
    $info = __('ATTENTION! Back-up your translations files before update.', 'sln');
    echo '<span class="spam">' . strip_tags($info, '<br><a><b><i><span>') . '</span>';
}

if (is_admin())
    add_action('in_plugin_update_message-' . plugin_basename(__FILE__), 'my_update_notice');

spl_autoload_register('sln_autoload');
$sln_plugin = SLN_Plugin::getInstance();

//TODO[feature-gcalendar]: move this require in the right place
require_once SLN_PLUGIN_DIR . "/src/SLN/Third/GoogleScope.php";
SLN_GoogleScope::set_settings_by_plugin($sln_plugin);
if (is_admin() && !isset($_GET['error']) && !isset($_SESSION['stop_asking'])) {
    SLN_GoogleScope::wp_init();
} else {
    $_SESSION['stop_asking'] = true;
}

ob_start();


