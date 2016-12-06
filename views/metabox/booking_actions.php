<?php
/**
 * @var SLN_Metabox_Helper $helper
 */
?>
<h3><?php _e('Re-send email notification to ', 'salon-booking-system') ?></h3>
<div class="row">
	<div class="col-xs-12">
		<label for="resend-notification-text"><?php _e('Type a message for your customer', 'salon-booking-system') ?></label>
		<textarea id="resend-notification-text" class="sln-input sln-input--textarea"><?php echo $settings->get('booking_update_message') ?></textarea>
	</div>
</div>
<br/>
<div class="row">
	<div class="col-xs-8"><input type="text" id="resend-notification" class="sln-input sln-input--text" value="<?php echo $booking->getEmail(); ?>"/></div>
	<div class="col-xs-4"><div class="row"><button class="button" id="resend-notification-submit"
	                               value="submit"><?php echo __('Send', 'salon-booking-system') ?></button></div></div>
</div>
<br/>
	<span id="resend-notification-message"></span>
<?php if($settings->get('pay_enabled') && $settings->get('pay')): ?>
	<h3><?php _e('Re-send payment link', 'salon-booking-system') ?></h3>
	<div class="row">
		<div class="col-xs-8"><input type="text" id="resend-payment" class="sln-input sln-input--text" value="<?php echo $booking->getEmail(); ?>"/></div>
		<div class="col-xs-4"><div class="row"><button class="button" id="resend-payment-submit"
		                      value="submit"><?php echo __('Send', 'salon-booking-system') ?></button></div></div>
	</div>
	<br/>
	<span id="resend-payment-message"></span>
<?php endif ?>
