<?php
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 */
$id = $plugin->getSettings()->getBookingmyaccountPageId();
if ($id) {
	$url = get_permalink($id);
}else{
	$url = home_url();
}

$msg = $plugin->getSettings()->get('follow_up_message') . "\r\n" . $url;
$msg = str_replace(array('[NAME]', '[SALON NAME]'), array($customer->getName(), $plugin->getSettings()->getSalonName()), $msg);
echo $msg;