<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 */

?>
<h2>Booking Confirmed #<?php echo $plugin->getBookingBuilder()->getLastBooking()->getId()?></h2>
<p>Check your email for the booking details</p>
<a href="<?php echo $paypalUrl ?>"
   class="btn btn-block btn-success">Pay with Paypal</a>
<p style="text-align: center font-size: 12px; font-weight: bold">or</p>
<a href="<?php echo $laterUrl  ?>" class="btn btn-block btn-primary">
    I'll pay later
</a>