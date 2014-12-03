<?php

require_once SLN_PLUGIN_DIR . '/includes/functions.php';
require_once SLN_PLUGIN_DIR . '/includes/shortcodes.php';
if ( is_admin() )
    require_once SLN_PLUGIN_DIR . '/admin/admin.php';
require_once SLN_PLUGIN_DIR . '/includes/posttype_service.php';
require_once SLN_PLUGIN_DIR . '/includes/posttype_booking.php';
require_once SLN_PLUGIN_DIR . '/includes/install.php';

function sln_action_init(){
    load_plugin_textdomain( 'sln', false, '/saloon/languages' );
    add_shortcode( 'saloon', 'sln_saloon_func' );
    wp_enqueue_style( 'saloon', SLN_PLUGIN_URL . '/css/saloon.css', array(),SLN_VERSION, 'all');
    wp_enqueue_script( 'saloon', SLN_PLUGIN_URL . '/js/saloon.js', array( 'jquery' ), '20140711', true ); 
/*
    register_post_type( 'sln_service', array(
      'labels' => array(
        'name' => __( 'Services', 'sln' ),
        'singular_name' => __( 'Service', 'sln' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'services'),
    ));

    register_post_type( 'sln_booking', array(
      'labels' => array(
        'name' => __( 'Bookings', 'sln' ),
        'singular_name' => __( 'Booking', 'sln' )
      ),
      'public' => true,
      'has_archive' => true,
    ));
*/
}
