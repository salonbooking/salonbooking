<?php
$helper->showNonce($postType);
?>

<div class="row sln-service-price-time">
<!-- default settings -->
    <div class="col-sm-4 col-md-4 col-lg-4 form-group sln-input--simple">
            <label><?php echo __('Price', 'salon-booking-system') . ' (' . $settings->getCurrencySymbol() . ')' ?></label>
            <?php SLN_Form::fieldText($helper->getFieldName($postType, 'price'), $service->getPrice()); ?>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4 form-group sln-select">
            <label><?php _e('Unit per hour', 'salon-booking-system'); ?></label>
            <?php SLN_Form::fieldNumeric($helper->getFieldName($postType, 'unit'), $service->getUnitPerHour()); ?>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4 form-group sln-select">
            <label><?php _e('Duration', 'salon-booking-system'); ?></label>
            <?php SLN_Form::fieldTime($helper->getFieldName($postType, 'duration'), $service->getDuration()); ?>
    </div>
    <div class="sln-clear"></div>
</div>
<div class="sln-separator"></div>
<div class="row">
    <div class="col-sm-4 col-md-6 col-lg-4 form-group sln-checkbox">
        <?php SLN_Form::fieldCheckbox($helper->getFieldName($postType, 'secondary'), $service->isSecondary(), array('attrs' => array('data-action' => 'change-service-type', 'data-target' => '#secondary_details'))) ?>
        <label for="_sln_service_secondary"><?php _e('Secondary', 'salon-booking-system'); ?></label>
        <p><?php _e('Select this if you want this service considered as secondary level service','salon-booking-system'); ?></p>
    </div>
    <div id="exclusive_service" class="col-sm-4 col-md-6 col-lg-4 form-group sln-checkbox <?php echo ($service->isSecondary() ? 'hide' : ''); ?>">
        <?php SLN_Form::fieldCheckbox($helper->getFieldName($postType, 'exclusive'), $service->isExclusive() ) ?>
        <label for="_sln_service_exclusive"><?php _e('Exclusive service', 'salon-booking-system'); ?></label>
        <p><?php _e('If enabled, when a customer choose this service no other services can be booked during the same reservation','salon-booking-system'); ?></p>
    </div>
    
    <div id="secondary_details" class="<?php echo ($service->isSecondary() ? '' : 'hide'); ?>">
        <div class="col-sm-8 col-md-6 col-lg-4 form-group sln-select">
            <label><?php _e('Display if', 'salon-booking-system'); ?></label>
            <?php SLN_Form::fieldSelect(
                $helper->getFieldName($postType, 'secondary_display_mode'),
                array(
                    'always'   => __('always', 'salon-booking-system'),
                    'category' => __('belong to the same category', 'salon-booking-system'),
                    'service'  => __('is child of selected service', 'salon-booking-system'),
                ),
                $service->getMeta('secondary_display_mode'),
                array('attrs' => array('data-action' => 'change-secondary-service-mode', 'data-target' => '#secondary_parent_services')),
                true
            ); ?>
        </div>
        <div id="secondary_parent_services" class="col-sm-12 col-md-12 form-group sln-select <?php echo ($service->getMeta('secondary_display_mode') === 'service' ? '' : 'hide_'); ?>">
            <label><?php _e('Select parent services', 'salon-booking-system'); ?></label>
            <?php
            /** @var SLN_Wrapper_Service[] $services */
            $services = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_SERVICE)->getAllPrimary();
            $items    = array();
            foreach($services as $s) {
                if ($service->getId() != $s->getId()) {
                    $items[$s->getId()] = $s->getName();
                }
            }
            SLN_Form::fieldSelect(
                $helper->getFieldName($postType, 'secondary_parent_services[]'),
                $items,
                (array)$service->getMeta('secondary_parent_services'),
                array('attrs' => array('multiple' => true, 'placeholder' => __('select one or more services', 'salon-booking-system'), 'data-containerCssClass' => 'sln-select-wrapper-no-search')),
                true
            ); ?>
        </div>
    </div>
    <div class="sln-clear"></div>
</div>
<div class="sln-separator"></div>
<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-4 form-group sln-select">
        <label><?php _e('Execution Order', 'salon-booking-system'); ?></label>
        <?php SLN_Form::fieldNumeric($helper->getFieldName($postType, 'exec_order'), $service->getExecOrder(), array('min' => 1, 'max' => 10, 'attrs' => array())) ?>
    </div>
    <div class="col-sm-6 col-md-6 form-group sln-box-maininfo align-top">
        <p class="sln-input-help"><?php _e('Use a number to give this service an order of execution compared to the other services.','salon-booking-system'); ?></p>
        <p class="sln-input-help"><?php _e('Consider that this option will affect the availability of your staff members that you have associated with this service.','salon-booking-system'); ?></p>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-4 form-group sln-checkbox">
        <?php SLN_Form::fieldCheckbox($helper->getFieldName($postType, 'attendants'), !$service->isAttendantsEnabled()) ?>
        <label for="_sln_service_attendants"><?php _e('No assistant required', 'salon-booking-system'); ?></label>
        <p><?php _e('No assistant required','salon-booking-system'); ?></p>
    </div>
    <div class="sln-clear"></div>
</div>
<?php if('highend' === $settings->getAvailabilityMode()): ?>
<div class="row">
    <div class="col-sm-6 col-md-3 form-group sln-select">
        <label><?php _e('Service break', 'salon-booking-system'); ?></label>
        <?php SLN_Form::fieldTime($helper->getFieldName($postType, 'break_duration'), $service->getBreakDuration(), array('maxItems' => (int) SLN_Func::getMinutesFromDuration(SLN_Constants::BREAK_DURATION_MAX)/SLN_Plugin::getInstance()->getSettings()->getInterval() + 1)); ?>
    </div>
    <div class="col-sm-6 col-md-6 form-group sln-box-maininfo align-top">
        <p class="sln-input-help"><?php _e('If you set a break this service duration will be splitted up into two equals parts. That means that the during its break other reservations will be available.','salon-booking-system'); ?></p>
    </div>
</div>
<?php endif; ?>
<?php echo $plugin->loadView(
    'settings/_tab_booking_rules',
    array(
        'availabilities' => $service->getMeta('availabilities'),
        'base' => '_sln_service_availabilities',
    )
); ?>
<div class="sln-clear"></div>
<?php if($plugin->getSettings()->isFormStepsAltOrder()): ?>
<?php $directLink = add_query_arg(
    array(
        'sln_step_page' => 'services',
        'submit_services' => 1,
        'sln' => array('services' => array($service->getId() => $service->getId())),
    ),
    get_permalink(SLN_Plugin::getInstance()->getSettings()->get('pay'))
);
?>
<div class="row">
    <div class="col-sm-12 form-group sln-select">
        <h2 class="sln-box-title"><?php echo __('Direct link', 'salon-booking-system') ?></h2>
<p><a href="<?php echo $directLink?>"><?php echo $directLink?></a></p>
        <p class="sln-input-help"><?php _e('Use this link to move the user directly to the booking page with the service already selected.','salon-booking-system'); ?></p>
    </div>
</div>
<div class="sln-clear"></div>
<?php endif ?>
<?php do_action('sln.template.service.metabox',$service); ?>
