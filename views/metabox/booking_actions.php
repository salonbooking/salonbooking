<?php
/**
 * @var SLN_Metabox_Helper $helper
 */
?>
<h3><?php _e('Re-send email notification to ', 'salon-booking-system') ?></h3>
<input type="text" id="resend-notification"/>
<button class="button" id="resend-notification-submit"
        value="submit"><?php echo __('Send', 'salon-booking-system') ?></button>
<br/>
<span id="resend-notification-message"></span>
<br/>
<a class="button" href="<?php echo $booking->getPayUrl()?>" target="_blank">Pay &raquo;</a>
