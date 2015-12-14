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
echo str_replace(array_keys($replaces), array_values($replaces), __("
Hi,
this is you verification code on {salon}:
{code}
Thank you very much.
{salon}
{address}
{phone}
{email}", 'sln'));
