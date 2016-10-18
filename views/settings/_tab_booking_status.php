<?php
/**
 * @var $plugin SLN_Plugin
 */
$disabled = $plugin->getSettings()->get('disabled');
$disabledMessage = $plugin->getSettings()->get('disabled_message');
?>

<h2 class="sln-box-title"><?php _e('Pause online booking service <span class="block">If ON the online booking form will be disabled and your users will see a message.</span>','salon-booking-system');?></h2>
<div class="row">
    <div class="col-xs-12 form-group sln-switch sln-moremargin--bottom">
        <h6 class="sln-fake-label"><?php _e('Online Booking status','salon-booking-system');?></h6>
        <!--<input type="checkbox" name="salon_settings[disabled]" id="salon_settings_disabled" value="1">
            <label class="sln-switch-btn" for="salon_settings_disabled"  data-on="On" data-off="Off"></label>
            <label class="sln-switch-text"  for="salon_settings_disabled" data-on="Online Booking is active"
            data-off="Online Booking is paused"></label>-->
        <?php SLN_Form::fieldCheckboxSwitch(
            "salon_settings[disabled]",
            $disabled,
            $labelOn = "Online Booking is disabled",
            $labelOff = "Online Booking is active"
        ) ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-input--simple">
        <label for="<?php echo SLN_form::makeID("salon_settings[disabled_message]") ?>"><?php _e(
                'Message on disabled booking',
                'salon-booking-system'
            ) ?></label>
        <?php
        $admin_email = $plugin->getSettings()->getSalonEmail();
        SLN_Form::fieldTextarea(
            "salon_settings[disabled_message]",
            $disabledMessage,
            array(
                'attrs' => array(
                    'placeholder' => __('Booking is not available at the moment, please contact us at ') . $admin_email,
                    'rows'        => 5,
                    'class'       => 'form-control',
                    'style'       => 'width: 100%;'
                )
            )
        ) ?>
    </div>
</div>
