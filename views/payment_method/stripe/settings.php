
    <div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Stripe account informations','salon-booking-system');?></h2></div>
        <div class="col-xs-12 col-sm-4 sln-input--simple">
            <?php $adminSettings->row_input_text('pay_stripe_apiKey', __('Secret key', 'salon-booking-system')); ?>
            <p class="sln-input-help"><?php _e('-','Enter your Stripe api key');?></p>
        </div>
        <div class="col-xs-12 col-sm-4 sln-input--simple">
            <?php $adminSettings->row_input_text('pay_stripe_apiKeyPublic', __('Publishable key', 'salon-booking-system')); ?>
        <p class="sln-input-help"><?php _e('Enter your Stripe publishable api key','salon-booking-system');?></p>
        </div>
        <div class="col-xs-12 col-sm-4 sln-box-maininfo  align-top">
            <p class="sln-box-info"><?php _e('To use this payment method you need an account with Stripe.','salon-booking-system');?></p>
        </div>

