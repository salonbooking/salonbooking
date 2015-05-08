<?php
/*
Plugin Name: Salon Booking Wordpress Plugin
Description: Let your customers book you services through your website. Perfect for hairdressing salons, barber shops and beauty centers.
Version: 1.0.1
Plugin URI: http://salon.wordpresschef.it/
Author: Wordpress Chef / Plugins 
Author URI: http://plugins.wordpresschef.it/
*/

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'EDD_SL_STORE_URL', 'http://plugins.wordpresschef.it/' );
// the name of your product. This should match the download name in EDD exactly
define( 'EDD_SL_ITEM_NAME', 'Salon booking wordpress plugin' );
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
// load our custom updater
include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}
// retrieve our license key from the DB
$license_key = trim( get_option( 'edd_sample_license_key' ) );
// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater( EDD_SL_STORE_URL, __FILE__, array(
'version' => '1.0', // current version number
'license' => $license_key, // license key (used get_option above to retrieve from DB)
'item_name' => EDD_SL_ITEM_NAME, // name of this plugin
'author' => 'Wordpress Chef', // author of this plugin
'url' => home_url()
)
); 

// QUI COMINCIA MARINO

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
ob_start();
