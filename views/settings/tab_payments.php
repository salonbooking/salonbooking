<div class="sln-tab" id="sln-tab-payments">

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="saloon_settings[pay_currency]">Currency</label>
                <?php echo SLN_Form::fieldCurrency(
                    "saloon_settings[pay_currency]",
                    $this->settings->getCurrency()
                ) ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php $this->row_input_checkbox('pay_enabled', __('Enable online payments', 'sln')); ?>
        </div>
        <div class="col-md-4">
            <?php $this->row_input_checkbox('pay_cash', __('Client can even pay by cash at the saloon', 'sln')); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-6">
            <?php $this->row_input_text('pay_paypal_email', __('Paypal E-mail', 'sln')); ?>
        </div>
        <div class="col-md-6">
            <?php $this->row_input_checkbox('pay_paypal_test', __('Paypal Test Mode', 'sln')); ?>
        </div>
    </div>
</div>