<?php
/**
 * @var $plugin SLN_Plugin
 */
$mode = $plugin->getSettings()->get('availability_mode');
?>
<h2 class="sln-box-title"><?php _e('Availability mode','salon-booking-system');?> <span><?php _e('Select your favourite booking system mode.', 'salon-booking-system') ?></span></h2>
<div class="row">
    <div class="col-xs-12 col-sm-8">
        <div class="sln-radiobox">
            <?php $field = "salon_settings[availability_mode]"; ?>
            <?php echo SLN_Form::fieldRadioboxGroup(
                $field,
                SLN_Enum_AvailabilityModeProvider::toArray(),
                $mode,
                array(),
                true
            ) ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 form-group sln-box-maininfo align-top">
        <p class="sln-input-help"><?php _e('You need to choose which kind of booking algorithm want to use for your salon. Click over "i" icon to get more information about this option.','salon-booking-system');?></p>
    </div>
</div>
<div class="sln-box-info">
    <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
    <div class="sln-box-info-content row">
        <div class="col-md-4 col-sm-8 col-xs-12">
            <h5><?php _e('The BASIC one sets a fixed duration for each booking and it doesn\'t care about the number and the duration of the booked services. <br />You are able to set a fixed duration using the "Session average duration" option.<br /><br />The ADVANCED mode is more complex and complete as it takes in count the duration of each services booked for every single reservation. <br />We suggest to use this one.','salon-booking-system');?></h5>
        </div>
    </div>
    <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
</div>
