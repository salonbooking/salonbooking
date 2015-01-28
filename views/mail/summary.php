<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
$data['to']      = $booking->getEmail();
$data['subject'] = 'New booking summary for ' . $plugin->format()->date($booking->getDate()) . ' - ' . $plugin->format(
    )->time($booking->getTime());
$forAdmin = false;
include dirname(__FILE__) . '/_header.php';
include dirname(__FILE__) . '/_summary_content.php';
include dirname(__FILE__) . ' /_footer.php';