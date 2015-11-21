<div class="row">
        <div class="col-md-4 col-sm-6">
            <?php $adminSettings->row_input_text('pay_paypal_email', __('Enter your PayPal e-mail address', 'sln')); ?>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="form-group">
                <?php $adminSettings->row_input_checkbox('pay_paypal_test', __('Enable PayPal sandbox', 'sln')); ?>
                <p><?php _e('Check this option to test PayPal payments<br /> using your PayPal Sandbox account.', 'sln') ?></p>
            </div>
        </div>
</div> 

