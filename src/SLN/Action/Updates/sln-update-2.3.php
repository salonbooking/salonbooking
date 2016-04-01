<?php

global $wpdb;

$query = $wpdb->prepare("SELECT DISTINCT post_author FROM {$wpdb->prefix}posts WHERE post_type = %s", array(SLN_Plugin::POST_TYPE_BOOKING));
$users = $wpdb->get_col($query);
foreach ($users as $userId) {
	$user = new WP_User($userId);
	if (array_search('administrator', $user->roles) === false && array_search('subscriber', $user->roles) !== false) {
		$user->set_role(SLN_Plugin::USER_ROLE_CUSTOMER);
	}
}
