<?php
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 */

$msg = $plugin->getSettings()->get('follow_up_message') . "\r\n" . home_url() . '/' . $customer->generateHash();
$msg = str_replace(array('[NAME]', '[SALON NAME]'), array($customer->getName(), $plugin->getSettings()->get('follow_up_message')), $msg);
echo $msg;