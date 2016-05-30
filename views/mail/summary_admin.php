<?php
/**
 * @var SLN_Plugin $plugin
 * @var SLN_Wrapper_Booking $booking
 */
$recipients = array();

$adminEmail           = $plugin->getSettings()->getSalonEmail();
$attendantEmailOption = $plugin->getSettings()->get('attendant_email');

if(isset($updated) && $updated) {
    if ($attendantEmailOption) {
        $bookingAttendants = $booking->getAttendants();
        if (!empty($bookingAttendants)) {
            foreach($bookingAttendants as $attendant) {
                $recipients[] = $attendant->getEmail();
            }
        }
    }
    $recipients = array_unique(array_filter($recipients));

    if (empty($recipients)) {
        $recipients[] = $adminEmail;
    }

    $data['to'] = implode(',', $recipients);
    $data['subject'] = __('Reservation has been modified ','salon-booking-system')
                       . $plugin->format()->date($booking->getDate())
                       . ' - ' . $plugin->format()->time($booking->getTime());
} else {
    $data['to'] = $adminEmail;
    if ($attendantEmailOption
         && ($attendant = $booking->getAttendant())
         && ($email = $attendant->getEmail())
    ) {
        $data['to'] = array($data['to'], $email);
    }
    $data['subject'] = __('New booking for ','salon-booking-system')
                       . $plugin->format()->date($booking->getDate())
                       . ' - ' . $plugin->format()->time($booking->getTime());
}
$forAdmin = true;
include dirname(__FILE__) . '/_header.php';
include dirname(__FILE__) . '/_summary_content.php';
include dirname(__FILE__) . '/_footer.php';
