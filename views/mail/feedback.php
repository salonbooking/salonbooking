<?php   // algolplus
/**
 * @var SLN_Plugin           $plugin
 * @var SLN_Wrapper_Customer $customer
 */
$data['to']      = $customer->get('user_email');
$data['subject'] = $plugin->getSettings()->getSalonName();
$manageBookingsLink = true;

include dirname(__FILE__).'/_header.php';
?>


<?php echo home_url() . '?sln_customer_login=' . $customer->getHash() . '&feedback_id=186'; ?>