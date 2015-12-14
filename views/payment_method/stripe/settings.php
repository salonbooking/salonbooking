
    <div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Stripe account informations','sln');?></h2></div>
        <div class="col-xs-12 col-sm-4 sln-input--simple">
            <?php $adminSettings->row_input_text('pay_stripe_apiKey', __('Enter your Stripe api key', 'sln')); ?>
            <p class="sln-input-help"><?php _e('-','sln');?></p>
        </div>
        <div class="col-xs-12 col-sm-4 sln-input--simple">
            <?php $adminSettings->row_input_text('pay_stripe_apiKeyPublic', __('Enter your Stripe publishable api key', 'sln')); ?>
        <p class="sln-input-help"><?php _e('-','sln');?></p>
        </div>
        <div class="col-xs-12 col-sm-4 sln-box-maininfo  align-top">
            <p class="sln-input-help"><?php _e('To use this payment method you need an account with Stripe.','sln');?></p>
        </div>

