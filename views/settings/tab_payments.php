<div class="sln-tab" id="sln-tab-payments">
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <?php $this->row_input_checkbox('pay_enabled', __('Enable online payments', 'sln')); ?>
        </div>
        <div class="col-md-4 col-sm-6">
            <?php $this->row_input_checkbox('pay_cash', __('Enable "pay later" option', 'sln')); ?>
        </div>
  </div>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <?php $this->row_input_text('pay_paypal_email', __('Set your PayPal email address', 'sln')); ?>
        </div>
        <div class="col-md-3 col-sm-4">
             <label for="salon_settings_pay_currency"><?php _e('Set your currency','sln') ?></label>
                <?php echo SLN_Form::fieldCurrency(
                    "salon_settings[pay_currency]",
                    $this->settings->getCurrency()
                ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="form-group">
                <?php $this->row_input_checkbox('pay_paypal_test', __('Enable paypal sandbox', 'sln')); ?>
            </div>
        </div>
    </div> 
</div>
