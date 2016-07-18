<?php
$helper->showNonce($postType);
?>

<div class="row sln-service-price-time">
<!-- default settings -->
    <div class="col-sm-6 col-md-3 form-group sln-input--simple">
            <label><?php _e('Price', 'salon-booking-system') . ' (' . $settings->getCurrencySymbol() . ')' ?></label>
            <?php SLN_Form::fieldText($helper->getFieldName($postType, 'price'), $service->getPrice()); ?>
    </div>
    <div class="col-sm-6 col-md-3 form-group sln-select">
            <label><?php _e('Unit per hour', 'salon-booking-system'); ?></label>
            <?php SLN_Form::fieldNumeric($helper->getFieldName($postType, 'unit'), $service->getUnitPerHour()); ?>
    </div>
    <div class="col-sm-6 col-md-3 form-group sln-select">
            <label><?php _e('Duration', 'salon-booking-system'); ?></label>
            <?php SLN_Form::fieldTime($helper->getFieldName($postType, 'duration'), $service->getDuration()); ?>
    </div>
    <div class="col-sm-6 col-md-3 form-group sln-checkbox">
                         <?php SLN_Form::fieldCheckbox($helper->getFieldName($postType, 'secondary'), $service->isSecondary()) ?>
            <label for="_sln_service_secondary"><?php _e('Secondary', 'salon-booking-system'); ?></label>
                    <p><?php _e('Select this if you want this service considered as secondary level service','salon-booking-system'); ?></p>
    </div>
    <div class="sln-clear"></div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-3 form-group sln-select">
        <label><?php _e('Execution Order', 'salon-booking-system'); ?></label>
        <?php SLN_Form::fieldNumeric($helper->getFieldName($postType, 'exec_order'), $service->getExecOrder(), array('min' => 1, 'max' => 10, 'attrs' => array())) ?>
    </div>
    <div class="col-sm-6 col-md-6 form-group sln-box-maininfo align-top">
        <p class="sln-input-help"><?php _e('Use a number to give this service an order of execution compared to the other services.','salon-booking-system'); ?></p>
        <p class="sln-input-help"><?php _e('Consider that this option will affect the availability of your staff members that you have associated with this service.','salon-booking-system'); ?></p>
    </div>
    <div class="col-sm-6 col-md-3 form-group sln-checkbox">
        <?php SLN_Form::fieldCheckbox($helper->getFieldName($postType, 'attendants'), !$service->isAttendantsEnabled()) ?>
        <label for="_sln_service_attendants"><?php _e('No assistant required', 'salon-booking-system'); ?></label>
        <p><?php _e('No assistant required','salon-booking-system'); ?></p>
    </div>
    <div class="sln-clear"></div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-3 form-group sln-select">
        <label><?php _e('Service break', 'salon-booking-system'); ?></label>
        <?php SLN_Form::fieldTime($helper->getFieldName($postType, 'break_duration'), $service->getBreakDuration(), array('maxItems' => (int) SLN_Func::getMinutesFromDuration($service->getDuration())/SLN_Plugin::getInstance()->getSettings()->getInterval())); ?>
    </div>
    <div class="col-sm-6 col-md-6 form-group sln-box-maininfo align-top">
        <p class="sln-input-help"><?php _e('If you set a break this service duration will be splitted up into two equals parts. That means that the during its break other reservations will be available.','salon-booking-system'); ?></p>
    </div>
</div>
<?php echo $plugin->loadView(
    'settings/_tab_booking_rules',
    array(
        'availabilities' => $service->getMeta('availabilities'),
        'base' => '_sln_service_availabilities',
    )
); ?>
<div class="sln-clear"></div>