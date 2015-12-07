
    <div class="col-xs-12"><h2 class="sln-box-title">Paypal account informations</h2></div>
        <div class="col-xs-12 col-sm-6 col-md-4 sln-input--simple">
            <?php $adminSettings->row_input_text('pay_paypal_email', __('Enter your PayPal e-mail address', 'sln')); ?>
            <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at.</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-5 sln-checkbox">
        	<?php $adminSettings->row_input_checkbox('pay_paypal_test', __('Enable PayPal sandbox', 'sln')); ?>
        	<p class="sln-input-help"><?php _e('Check this option to test PayPal payments<br /> using your PayPal Sandbox account.', 'sln') ?></p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 sln-box-maininfo  align-top">
            <p class="sln-input-help">Check this option to test PayPal payments
using your PayPal Sandbox account.</p>
        </div>

