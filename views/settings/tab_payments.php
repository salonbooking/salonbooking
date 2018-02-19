
    <div class="sln-box sln-box--main">
        <h2 class="sln-box-title"><?php _e('Online payment<span>Allow users to pay in advance using one of the available payments methods.</span>','salon-booking-system');?></h2>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-switch">
                <?php $this->row_input_checkbox_switch(
                    'pay_enabled',
                    'Online payment status',
                    array(
                        'help' => __('Allow users to pay in advance using PayPal.','salon-booking-system'),
                        'bigLabelOn' => 'Online payment is enabled',
                        'bigLabelOff' => 'Online payment is disabled'
                        )
                ); ?>
            </div>
            <div class="col-md-4 col-sm-4 form-group sln-box-maininfo align-top">
                <p class="sln-box-info"><?php _e('If enabled you need to setup one of the available payments methods.','salon-booking-system');?></p>
            </div>
        </div>
        <div class="sln-box-info">
           <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
           <div class="sln-box-info-content row">
           <div class="col-md-4 col-sm-8 col-xs-12">
           <h5><?php _e('In the future we\'ll provide more detailed information about this specific option.','salon-booking-system');?></h5>
            </div>
            </div>
            <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="sln-box sln-box--main sln-box--main--small">
        <h2 class="sln-box-title"><?php _e('Pay later','salon-booking-system');?></span></h2>
        <div class="row">
                <div class="col-xs-12 form-group sln-switch sln-switch--smaller ">
                    <?php $this->row_input_checkbox_switch(
                    'pay_cash',
                    'Pay later status',
                    array(
                        'help' => __('Give users the option to pay once they are at your salon.','salon-booking-system'),
                        'bigLabelOn' => 'Pay later is enabled',
                        'bigLabelOff' => 'Pay later is disabled'
                        )
                ); ?>
                </div>
            </div>
            <!--
            THIS BOX MUST BE HIDDEN IF NOT IN USE
            <div class="sln-box-info">
           <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
           <div class="sln-box-info-content row">
           <div class="col-xs-12">
           <h5><?php _e('In the future we\'ll provide more detailed information about this specific option.','salon-booking-system');?></h5>
            </div>
            </div>
            <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
        </div>
            -->
        </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="sln-box sln-box--main sln-box--main--small">
                <h2 class="sln-box-title"><?php _e('Pay a deposit','salon-booking-system');?></h2>
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-5 form-group sln-input--simple">
    					<?php $this->row_input_text('pay_deposit_fixed_amount', __('Fixed amount', 'salon-booking-system'), array('attrs' => array('data-relate-to' => SLN_Enum_PaymentDepositType::FIXED))); ?>
                        <p class="sln-input-help"><?php _e('specify the amount without currency symbol','salon-booking-system');?></p>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-7 form-group sln-select  sln-select--info-label">
                        <label for="salon_settings_pay_deposit"><?php _e('Percentage ','salon-booking-system') ?></label>
    					<?php SLN_Form::fieldSelect(
    						'salon_settings[pay_deposit]',
    						SLN_Enum_PaymentDepositType::toArray(),
    						$this->settings->get('pay_deposit'),
    						array(),
    						true
    					) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="sln-box sln-box--main sln-box--main--small">
                <h2 class="sln-box-title"><?php _e('Prices visibility','salon-booking-system') ?></h2>
                <div class="row">
                    <div class="col-xs-12 form-group sln-checkbox">
                        <?php $this->row_input_checkbox('hide_prices', __('Hide Prices', 'salon-booking-system')); ?>

                    <div class="sln-box-maininfo">
                        <p class="sln-box-info"><?php _e('Select this Option if you want to hide all prices from the front end.<br/>Note: Online Payment will be disabled.', 'salon-booking-system') ?></p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sln-box sln-box--main">
        <h2 class="sln-box-title"><?php _e('Unpaid reservations','salon-booking-system');?></h2>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 form-group sln-checkbox">
                <?php $this->row_input_checkbox(
                    'pay_offset_enabled',
                    __('Enable cancellation', 'salon-booking-system'),
                    array('help' => __('Select this option if you want to automatically cancel unpaid reservations.','salon-booking-system'))
                ); ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
                <label><?php _e('Delete unpaid reservations after','salon-booking-system');?></label>
                <?php echo SLN_Form::fieldSelect(
                    'salon_settings[pay_offset]',
                    array(
                        
                        '3' => '3m',
                        '60'   => '1h',
                        '120'  => '2h',
                        '360'  => '6h',
                        '720'  => '12h',
                        '1440' => '24h',
                        '2880' => '48h',
                    ),
                    $this->settings->get('pay_offset'),
                    array(),
                    true
                ) ?>
                <p class="help-block"><?php _e('Set the time range to complete the online payment.', 'salon-booking-system') ?></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
            </div>
        </div>
    </div>

    <div class="sln-box sln-box--main">
        <h2 class="sln-box-title"><?php _e('Currency','salon-booking-system');?></h2>
        <div class="row">
                <div class="col-sm-6 col-md-4 form-group sln-select ">
                    <label for="salon_settings_pay_currency"><?php _e('Set your currency','salon-booking-system') ?></label>
                    <?php echo SLN_Form::fieldCurrency(
                        "salon_settings[pay_currency]",
                        $this->settings->getCurrency()
                    ) ?>
                </div>
                <div class="col-sm-6 col-md-4 form-group sln-select ">
                    <label for="salon_settings_pay_currency_pos"><?php _e('Set your currency position','salon-booking-system') ?></label>
                     <?php echo SLN_Form::fieldSelect(
                            'salon_settings[pay_currency_pos]',
                            array('left' => __('on left side'),'right' => __('on right side')),
                            $this->settings->get('pay_currency_pos'),
                            array(),
                            true
                        ) ?>
                </div>
            <div class="col-xs-12 col-sm-6 col-md-4 visible-lg-block sln-box-maininfo">
                <p class="sln-box-info"><?php _e('If you want a new currency to be added please send us an email to support@wpchef.it','salon-booking-system');?></p>
            </div>
            <div class="clearfix visible-lg-block"></div>
            <div class="col-xs-6 col-sm-3 col-md-2 sln-input--simple">
                <?php $this->row_input_text('pay_decimal_separator', __('Decimal separator', 'salon-booking-system')); ?>
            </div>
            <div class="col-xs-6 col-sm-3 col-md-2 sln-input--simple">
                <?php $this->row_input_text('pay_thousand_separator', __('Thousand separator', 'salon-booking-system')); ?>
            </div>
            <?php /* this box is a carbon copy of the one some lines above, this one is visible on smaller screens, the other one on large screens. They must have the same content. */ ?>
            <div class="col-xs-12 col-sm-6 col-md-4 hidden-lg sln-box-maininfo">
                <p class="sln-box-info"><?php _e('If you want a new currency to be added please send us an email to support@wpchef.it','salon-booking-system');?></p>
            </div>
                </div>

        <div class="row">
            
        </div>
    </div>

    <div class="sln-box sln-box--main">
        <h2 class="sln-box-title"><?php _e('Payment methods','salon-booking-system');?></h2>
        <div class="row">
        <?php
        $current_payment_method = $this->settings->getPaymentMethod();
        foreach(SLN_Enum_PaymentMethodProvider::toArray() as $method => $name){
        	$checked = ($current_payment_method == $method) ?  'checked="checked"' : '';
        ?>
        		<div class="sln-radiobox sln-radiobox--fullwidth salon_settings_pay_method col-sm-4">
                    <input class="sln-pay_method-radio" id="salon_settings_availability_mode--<?php echo $method?>" type="radio" name="salon_settings[pay_method]" value="<?php echo $method?>" data-method="<?php echo $method?>" <?php echo $checked; ?> >
                    <label for="salon_settings_availability_mode--<?php echo $method?>"><?php echo $name?></label>
                </div>
        <?php } ?>

            <div class="col-sm-4 sln-box-maininfo  align-top">
                <p class="sln-box-info"><?php _e('If you want to integrate a new custom payment gateway please refere to <strong>custom_payment_gateway.txt</strong> file inside our plugin folder.','salon-booking-system');?></p>
            </div>
        </div>
        <?php
        foreach(SLN_Enum_PaymentMethodProvider::toArray() as $k => $v){
            ?><div class="sln-box--sub row payment-mode-data"  style="display: none;" id="payment-mode-<?php echo $k?>"><?php
            echo SLN_Enum_PaymentMethodProvider::getService($k, $this->plugin)->renderSettingsFields(
                array('adminSettings' => $this));
            ?></div><?php
        }
    ?>
    <?php    ?>
    <div class="clearfix"></div>
</div>
<!--
    <div class="row">
        <div class="col-md-4 col-sm-6">
			<?php $settings=$this->hidePriceSettings();?>
            <?php $this->row_input_checkbox('pay_enabled', __('Enable online payments', 'salon-booking-system'),$settings); ?>
            <p><?php _e('Allow users to pay in advance.', 'salon-booking-system') ?></p>
        </div>
        <div class="col-md-4 col-sm-6">
            <?php $this->row_input_checkbox('pay_cash', __('Enable "Pay later" option', 'salon-booking-system')); ?>
            <p><?php _e('Give users the option to pay once they are at your salon.', 'salon-booking-system') ?></p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3 col-sm-4">
             <label for="salon_settings_pay_currency"><?php _e('Set your currency','salon-booking-system') ?></label>
                <?php echo SLN_Form::fieldCurrency(
                    "salon_settings[pay_currency]",
                    $this->settings->getCurrency()
                ) ?>
        </div>
        <div class="col-md-3 col-sm-4">
             <label for="salon_settings_pay_currency_pos"><?php _e('Set your currency position','salon-booking-system') ?></label>
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
             <label for="salon_settings_pay_currency_pos"><?php _e('Payment method','salon-booking-system') ?></label>
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
             <label for="salon_settings_pay_deposit"><?php _e('Pay a deposit of ','salon-booking-system') ?></label>
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

