<?php
/**
 * @var SLN_Plugin                $plugin
 * @var SLN_Wrapper_Booking       $booking
 */
if(!isset($data['to'])){
    $data['to'] = $booking->getEmail();
}

$data['subject'] = __('Pending payment on booking','salon-booking-system')
    . ' ' . $plugin->format()->date($booking->getDate())
    . ' - ' . $plugin->format()->time($booking->getTime());
$manageBookingsLink = true;
include dirname(__FILE__).'/_header.php';
include dirname(__FILE__).'/_summary_content.php';
include dirname(__FILE__).'/_footer.php';
