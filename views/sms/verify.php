<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
$s = $plugin->getSettings();
$replaces = array(
    '{salon}' => $s->getSalonName(),
    '{phone}' => $s->get('gen_phone'),
    '{address}' => $s->get('gen_address'),
    '{email}' => $s->getSalonEmail(),
    '{code}' => $code
);
echo str_replace(array_keys($replaces), array_values($replaces), __("Hi, \nthis is you verification code on {salon}: \n{code} \nThank you very much. \n{salon} \n{address} \n{phone} \n{email}", 'salon-booking-system'));
