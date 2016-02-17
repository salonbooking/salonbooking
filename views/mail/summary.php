<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
if(empty($data['to'])){
    $data['to']      = $booking->getEmail();
}

if(isset($remind) && $remind) {
    $data['subject'] = str_replace(
        array(
            '[DATE]',
            '[TIME]',
            '[SALON NAME]'
        ),
        array(
            $plugin->format()->date($booking->getDate()),
            $plugin->format()->time($booking->getTime()),
            $plugin->getSettings()->get('gen_name') ?
                $plugin->getSettings()->get('gen_name') : get_bloginfo('name')
        ),
        $plugin->getSettings()->get('email_subject')
    );
} else {
    $data['subject'] = __('New booking ','salon-booking-system')
                       .' '. $plugin->format()->date($booking->getDate())
                       . ' - ' . $plugin->format()->time($booking->getTime());
}
$forAdmin = false;
include dirname(__FILE__) . '/_header.php';
include dirname(__FILE__) . '/_summary_content.php';
include dirname(__FILE__) . '/_footer.php';
