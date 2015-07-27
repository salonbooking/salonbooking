<?php
/*
Plugin Name: Salon Booking Wordpress Plugin - Free Version
Description: Let your customers book you services through your website. Perfect for hairdressing salons, barber shops and beauty centers.
<<<<<<< HEAD
Version: 1.0.5
=======
Version: 1.0.6
>>>>>>> master
Plugin URI: http://salon.wpchef.it/
Author: Wordpress Chef / Plugins 
Author URI: http://plugins.wpchef.it/
Text Domain: sln
*/


define('SLN_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SLN_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('SLN_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
<<<<<<< HEAD
define('SLN_VERSION', '1.0.5');
=======
define('SLN_VERSION', '1.0.6');
>>>>>>> master

function sln_autoload($className)
{
    if (strpos($className, 'SLN_') === 0) {
        include_once(SLN_PLUGIN_DIR . "/src/" . str_replace("_", "/", $className) . '.php');
    }
}


function my_update_notice() {
	$info = __( 'ATTENTION! Back-up your translations files before update.', 'sln' );
	echo '<span class="spam">' . strip_tags( $info, '<br><a><b><i><span>' ) . '</span>';
}

if ( is_admin() )
	add_action( 'in_plugin_update_message-' . plugin_basename(__FILE__), 'my_update_notice' );




spl_autoload_register('sln_autoload');
SLN_Plugin::getInstance();
ob_start();


