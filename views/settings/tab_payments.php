<div class="sln-tab" id="sln-tab-payments">
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Online payment<span>Allow users to pay in advance using PayPal</span></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-switch">
            <input id="switch-1" class="big-check-base sln-switch--round" type="checkbox" checked="checked">
            <label class="sln-switch-btn" for="switch-1"  data-on="On" data-off="Off"></label>
            <label class="sln-switch-text"  for="switch-1" data-on="Online payment is enabled" 
            data-off="Online payment is disabled"></label>
        </div>
        <div class="col-md-4 col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-input-help">Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
    </div>
    <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-md-4 col-sm-8 col-xs-12">
       <h5>Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</h5>
        </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Pay later <span>Let users pay once they are at your salon.</span></h2>
    <div class="row">
            <div class="col-xs-12 form-group sln-switch">
            <h6 class="sln-fake-label">Deposit amount</h6>
            <input type="checkbox" name="salon_settings[pay_cash]" id="salon_settings_pay_cash" value="1" checked="checked">
                <label class="sln-switch-btn" for="salon_settings_pay_cash"  data-on="On" data-off="Off"></label>
                <label class="sln-switch-text"  for="salon_settings_pay_cash" data-on="Pay later is enabled" 
                data-off="Pay later is disabled"></label>
            </div>
        </div>
        <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-xs-12">
       <h5>Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</h5>
        </div>
        </div>
    </div>
    </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Pay a deposit</h2>
    <div class="row">
            <div class="col-xs-12 form-group sln-select  sln-select--info-label">
            <label for="salon_settings_pay_deposit">Deposit amount</label>
            <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
            <select name="salon_settings[pay_deposit]" id="salon_settings_pay_deposit" class="form-control">
                            <option value="0">entire amount (disabled)</option>
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                            <option value="30" selected="selected">30%</option>
                            <option value="40">40%</option>
                            <option value="50">50%</option>
                            <option value="60">60%</option>
                            <option value="70">70%</option>
                            <option value="80">80%</option>
                            <option value="90">90%</option>
                    </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 sln-label--big">
            <label for="salon_settings_pay_deposit">of the total</label>
            </div>
            </div>
        </div>
        </div>
        <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-xs-12">
       <h5>Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</h5>
        </div>
        </div>
    </div>
    </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Currency</h2>
    <div class="row">
            <div class="col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_pay_currency">Set your currency</label>
                        <select name="salon_settings[pay_currency]" id="salon_settings_pay_currency" class="form-control">
                            <option value="AUD">AUD (A$)</option>
                            <option value="CAD">CAD ($)</option>
                            <option value="CZK">CZK (Kč)</option>
                            <option value="DKK">DKK (Kr)</option>
                            <option value="EUR">EUR (€)</option>
                            <option value="HKD">HKD ($)</option>
                            <option value="HUF">HUF (Ft)</option>
                            <option value="ILS">ILS (₩)</option>
                            <option value="JPY">JPY (¥)</option>
                            <option value="MXN">MXN ($)</option>
                            <option value="NOK">NOK (Kr)</option>
                            <option value="NZD">NZD ($)</option>
                            <option value="PHP">PHP (₱)</option>
                            <option value="PLN">PLN (zł)</option>
                            <option value="GBP">GBP (£)</option>
                            <option value="SGD">SGD ($)</option>
                            <option value="SEK">SEK (kr)</option>
                            <option value="CHF">CHF (CHF)</option>
                            <option value="TWD">TWD (NT$)</option>
                            <option value="THB">THB (฿)</option>
                            <option value="USD" selected="selected">USD ($)</option>
                    </select>
            </div>
            <div class="col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_pay_currency_pos">Set your currency position</label>
                         <select name="salon_settings[pay_currency_pos]" id="salon_settings_pay_currency_pos" class="form-control">
                            <option value="left">on left side</option>
                            <option value="right" selected="selected">on right side</option>
                    </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
            </div>
            </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Payment methods</h2>
    <div class="row">
        <div class="sln-radiobox sln-radiobox--fullwidth salon_settings_pay_method col-sm-4">
            <input id="salon_settings_availability_mode--basic" type="radio" name="salon_settings_availability_mode" value="paypal" data-method="paypal">
            <label for="salon_settings_availability_mode--basic">Paypal</label>
        </div>
        <div class="sln-radiobox sln-radiobox--fullwidth salon_settings_pay_method col-sm-4">
            <input id="salon_settings_availability_mode--advanced" type="radio" name="salon_settings_availability_mode" value="stripe" data-method="stripe" checked="checked">
            <label for="salon_settings_availability_mode--advanced">Stripe</label>
        </div>
        <div class="col-sm-4 sln-box-maininfo  align-top">
            <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
    </div>
    <div class="sln-box--sub row payment-mode-data" id="payment-mode-stripe" style="display: block;">
    <div class="col-xs-12"><h2 class="sln-box-title">Stripe account informations</h2></div>
        <div class="col-xs-12 col-sm-4 sln-input--simple">
            <label for="salon_settings_pay_stripe_apiKey">Enter your Stripe API key</label>
                <input type="text" name="salon_settings[pay_stripe_apiKey]" id="salon_settings_pay_stripe_apiKey" value="sk_test_jUzp39d02lXXZGr4AyNvRDDc">
            <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at.</p>
        </div>
        <div class="col-xs-12 col-sm-4 sln-input--simple">
            <label for="salon_settings_pay_stripe_apiKey">Enter your Stripe publishable api key</label>
                <input type="text" name="salon_settings[pay_stripe_apiKeyPublic]" id="salon_settings_pay_stripe_apiKeyPublic" value="pk_test_A7SMBbMwikB6VOncPfBKMBhO">
            <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at.</p>
        </div>
        <div class="col-xs-12 col-sm-4 sln-box-maininfo  align-top">
            <p class="sln-input-help">Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
    </div>
    <div class="sln-box--sub row payment-mode-data" id="payment-mode-paypal" style="display: none;">
    <div class="col-xs-12"><h2 class="sln-box-title">Paypal account informations</h2></div>
        <div class="col-xs-12 col-sm-6 col-md-4 sln-input--simple">
            <label for="salon_settings_gen_name">Enter your PayPal e-mail address</label>
                <input type="text" name="salon_settings[gen_name]" id="salon_settings_gen_name" placeholder="Prova nome salon bis">
            <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at.</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-5 sln-checkbox">
                <input type="checkbox" name="salon_settings[pay_paypal_test]" id="salon_settings_pay_paypal_test" value="1" checked="checked">
        <label for="salon_settings_pay_paypal_test">Enable PayPal sandbox</label>
        <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at.</p>
        </div>
        <!--<div class="col-xs-12 col-sm-4 sln-box-maininfo  align-top">
            <p class="sln-input-help">Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
        <div class="clearfix"></div>-->
        <div class="col-xs-12 col-sm-6 col-md-3 sln-box-maininfo  align-top">
            <p class="sln-input-help">Check this option to test PayPal payments
using your PayPal Sandbox account.</p>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!--
    <div class="row">
        <div class="col-md-4 col-sm-6">
			<?php $settings=$this->hidePriceSettings();?>
            <?php $this->row_input_checkbox('pay_enabled', __('Enable online payments', 'sln'),$settings); ?>
            <p><?php _e('Allow users to pay in advance.', 'sln') ?></p>
        </div>
        <div class="col-md-4 col-sm-6">
            <?php $this->row_input_checkbox('pay_cash', __('Enable "Pay later" option', 'sln')); ?>
            <p><?php _e('Give users the option to pay once they are at your salon.', 'sln') ?></p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3 col-sm-4">
             <label for="salon_settings_pay_currency"><?php _e('Set your currency','sln') ?></label>
                <?php echo SLN_Form::fieldCurrency(
                    "salon_settings[pay_currency]",
                    $this->settings->getCurrency()
                ) ?>
        </div>
        <div class="col-md-3 col-sm-4">
             <label for="salon_settings_pay_currency_pos"><?php _e('Set your currency position','sln') ?></label>
                 <?php echo SLN_Form::fieldSelect(
                        'salon_settings[pay_currency_pos]',
                        array('left' => __('on left side'),'right' => __('on right side')),
                        $this->settings->get('pay_currency_pos'),
                        array(),
                        true
                    ) ?>
        </div>
 
    </div> 
    <div class="row">
        <div class="col-md-3 col-sm-4">
             <label for="salon_settings_pay_currency_pos"><?php _e('Payment method','sln') ?></label>
                 <?php echo SLN_Form::fieldSelect(
                        'salon_settings[pay_method]',
                        SLN_Enum_PaymentMethodProvider::toArray(),
                        $this->settings->getPaymentMethod(),
                        array(),
                        true
                    ) ?>
        </div>
    </div>
<?php 
    foreach(SLN_Enum_PaymentMethodProvider::toArray() as $k => $v){
        ?><div class="payment-mode-data" id="payment-mode-<?php echo $k?>"><?php
        echo SLN_Enum_PaymentMethodProvider::getService($k, $this->plugin)->renderSettingsFields(array('adminSettings' => $this));
        ?></div><?php
    }
?>
    <div class="row">
        <div class="col-md-6 col-sm-6">
             <label for="salon_settings_pay_deposit"><?php _e('Pay a deposit of ','sln') ?></label>
                 <?php echo SLN_Form::fieldSelect(
                        'salon_settings[pay_deposit]',
                        array(
                            '0' => "entire amount (disabled)",
                            '10' => "10%",
                            '20' => "20%",
                            '30' => "30%",
                            '40' => "40%",
                            '50' => "50%",
                            '60' => "60%",
                            '70' => "70%",
                            '80' => "80%",
                            '90' => "90%",
                        ),
                        $this->settings->get('pay_deposit'),
                        array(),
                        true
                    ) ?>
        </div>
    </div>
    -->
</div>
<div class="clearfix"></div>
