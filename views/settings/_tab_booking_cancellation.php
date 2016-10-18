<?php
/**
 * @var $plugin SLN_Plugin
 * @var $helper SLN_Admin_Settings
 */
$hoursBeforeCancellation = $plugin->getSettings()->get('hours_before_cancellation');
?>

<h2 class="sln-box-title"><?php _e('User booking cancellation','salon-booking-system');?></h2>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-4 form-group sln-checkbox">
        <?php $helper->row_input_checkbox(
            'cancellation_enabled',
            __('Booking cancellation', 'salon-booking-system'),
            array('help' => __('Select this option if you want your users able to cancel a booking from the front-end.','salon-booking-system'))
        ); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
        <label><?php _e('Time in advance','salon-booking-system');?></label>
        <?php $field = "salon_settings[hours_before_cancellation]"; ?>
        <?php echo SLN_Form::fieldSelect(
            $field,
            array(
                '1' => '1h',
                '5' => '5h',
                '12' => '12h',
                '24' => '24h',
                '48' => '48h',
                '72' => '72h',
            ),
            $hoursBeforeCancellation,
            array(),
            true
        ) ?>
        <p class="help-block"><?php _e('How many hours before the appointment the cancellation is still allowed', 'salon-booking-system') ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
        <p class="sln-box-info"><?php _e('Users once logged in inside the MY ACCOUNT BOOKING page will be able to see the list of their upcoming reservations and eventually Cancel them. An email notification will be sent to you and to the customers.','salon-booking-system');?></p>
    </div>
</div>