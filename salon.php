<?php
/*
Plugin Name: Salon
Description: Just another plugin.
Version: 1.0.0
*/

define('SLN_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SLN_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('SLN_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
define('SLN_VERSION', '1.0.0');
function sln_autoload($className) {
    if (strpos($className, 'SLN_') === 0) {
        include_once(SLN_PLUGIN_DIR . "/src/" . str_replace("_", "/", $className) . '.php');
    }
}
spl_autoload_register('sln_autoload');
SLN_Plugin::getInstance();
