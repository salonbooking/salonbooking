
    <div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Paypal account informations','salon-booking-system');?></h2></div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 sln-input--simple">
            <?php $adminSettings->row_input_text('pay_paypal_email', __('Your PayPal account e-mail', 'salon-booking-system')); ?>
            <p class="sln-input-help"><?php _e('Enter your PayPal e-mail address','salon-booking-system');?></p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 sln-checkbox">
        	<?php $adminSettings->row_input_checkbox('pay_paypal_test', __('Enable PayPal sandbox', 'salon-booking-system')); ?>
        	<p class="sln-input-help"><?php _e('Check this option to test PayPal payments using your PayPal Sandbox account.', 'salon-booking-system') ?></p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 sln-box-maininfo  align-top">
            <p class="sln-box-info"><?php _e('Check this option to test PayPal payments
using your PayPal Sandbox account.','salon-booking-system');?></p>
        </div>

