<?php

function sln_install(){
/*
    if(get_option('saloon_settings'))
        return;
*/
    update_option('saloon_settings', array(
    'sln_gen_name' => 'Sitename',
    'sln_gen_email' => 'email@email.com',
    'sln_gen_phone' => '00391122334455',
    'sln_gen_address' => 'Main Street 123',
    'sln_gen_timetable' => '',
    'sln_soc_facebook' => 'http://www.facebook.com',
    'sln_soc_twitter' => 'http://www.twitter.com',
    'sln_soc_google' => 'www.google.it',
    'thankyou' => true,
    'available_0' => true,
    'available_from' => '09:00',
    'available_to' => '18:00',
    'pay_currency' => 'USD',
    'pay_paypal_email' => 'test@test.com',
    'pay_paypal_api_key' => 'TEST',
    'confirmation' => true,
    'pay_enabled' => true,
    'pay_cash' => true
    ));
    $my_post = array(
     'post_title' => 'Manicure',
     'post_content' => 'manicure',
     'post_status' => 'publish',
     'post_type' => 'sln_service',
    );
    $id = wp_insert_post( $my_post );
    update_post_meta($id, '_sln_service_price',15);
    update_post_meta($id, '_sln_service_unit',3);
    $my_post = array(
     'post_title' => 'Nails styling',
     'post_content' => 'nails styling',
     'post_status' => 'publish',
     'post_type' => 'sln_service',
    );
    $id = wp_insert_post( $my_post );
    update_post_meta($id, '_sln_service_price',10.11);
    update_post_meta($id, '_sln_service_unit',2);
    update_post_meta($id, '_sln_service_secondary',true);
    update_post_meta($id, '_sln_service_notav_from','11');
    update_post_meta($id, '_sln_service_notav_to','15');

    $my_post = array(
     'post_title' => 'Massage',
     'post_content' => 'massage',
     'post_status' => 'publish',
     'post_type' => 'sln_service',
    );
    $id = wp_insert_post( $my_post );
    update_post_meta($id, '_sln_service_price',30.50);
    update_post_meta($id, '_sln_service_unit',1);


}
