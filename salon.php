<?php
/*
Plugin Name: Salon Booking Wordpress Plugin
Description: Let your customers book you services through your website. Perfect for hairdressing salons, barber shops and beauty centers.
Version: 1.0.3
Plugin URI: http://salon.wordpresschef.it/
Author: Wordpress Chef / Plugins 
Author URI: http://plugins.wordpresschef.it/
*/

define('SLN_STORE_URL', 'http://plugins.wordpresschef.it');
define('SLN_ITEM_NAME', 'Salon booking wordpress plugin');
define('SLN_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SLN_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('SLN_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
define('SLN_VERSION', '1.0.3');

function sln_autoload($className)
{
    if (strpos($className, 'SLN_') === 0) {
        include_once(SLN_PLUGIN_DIR . "/src/" . str_replace("_", "/", $className) . '.php');
    }
}

spl_autoload_register('sln_autoload');
SLN_Plugin::getInstance();
ob_start();
