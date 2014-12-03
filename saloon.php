<?php
/*
Plugin Name: Saloon
Description: Just another plugin.
Version: 1.0.0
*/

define( 'SLN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SLN_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'SLN_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'SLN_VERSION', '1.0.0' );
require_once SLN_PLUGIN_DIR . '/settings.php';

add_action('init', 'sln_action_init');
register_activation_hook( __FILE__, 'sln_install' );
