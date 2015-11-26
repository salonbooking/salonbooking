<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
$replaces = array(
    '{salon}' => $plugin->getSettings()->getSalonName(),
    '{code}' => $code
);
echo str_replace(array_keys($replaces), array_values($replaces), __("
Hi,
this is you verification code on {salon}:
{code}
Thank you very much.
{salon}", 'sln'));
