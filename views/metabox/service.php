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
<?php /*
<?php $m_attendant_enabled = $settings->get('m_attendant_enabled'); ?>
<?php if($m_attendant_enabled): ?>
<div class="row sln-service-price-time">
    <div class="col-xs-6 col-md-6 col-sm-4 col-lg-4">
        <div class="form-group">
            <?php $attrs = $m_attendant_enabled ? array() : array('disabled'=>'disabled') ?>
            <label><?php _e('Execution Order', 'salon-booking-system'); ?></label>
            <?php SLN_Form::fieldNumeric($helper->getFieldName($postType, 'exec_order'), $service->getExecOrder(), array('min' => 1, 'max' => 10, 'attrs' => $attrs)) ?>
            <?php if (!$m_attendant_enabled) {
                SLN_Form::fieldText($helper->getFieldName($postType, 'exec_order'), $service->getExecOrder(), array('type' => 'hidden'));
            } ?>
?>
            <br/><em><?php _e('Set this option if you have enabled "Multiple assistants selection". Use a number to give this service an order of execution compared to the other services.','salon-booking-system'); ?></em>
            <br/><em><?php _e('Consider that this option will affect the availability of your staff members that you have associated th this service.','salon-booking-system'); ?></em>
        </div>
    </div>
    <div class="sln-clear"></div>
</div>
<?php endif ?>
*/?>
<div class="sln-box--sub sln-booking-rules row">
    <div class="col-xs-6">
        <h2 class="sln-box-title"><?php _e('Not available on','salon-booking-system'); ?></h2>
        <small><?php _e('Leave this option blank if you want this service available for every hour each day', 'salon-booking-system') ?></small>
    </div>
    <div class="sln-clear"></div>
    <div id="sln-booking-rules-wrapper">
        <div class="col-xs-12 sln-booking-rule">
            <h6 class="sln-fake-label">Not available days checked and green.</h6>
            <div class="sln-checkbutton-group">
        <?php foreach (SLN_Func::getDays() as $k => $day) : ?>
            <div class="sln-checkbutton">
                    <?php SLN_Form::fieldCheckboxButton(
                        $helper->getFieldName($postType, 'notav_' . $k),
                        ($service->getNotAvailableOn($k) ? 1 : null),
                        $label = substr($day, 0, 3)
                    ) ?>
            </div>
        <?php endforeach ?>
            <div class="clearfix"></div>
        </div>
        <div class="row">
           <div class="col-xs-12 col-md-8 sln-slider-wrapper">
              <div class="sln-slider">
                  <div class="sliders_step1 col col-slider"><div class="slider-range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 0%; width: 50%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 0%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 50%;"></span></div></div>
                  <div class="col col-time"><span class="slider-time-from">0:00</span> to <span class="slider-time-to">12:00</span>
                      <?php echo SLN_Form::fieldText($helper->getFieldName($postType, 'notav_from'), $service->getNotAvailableFrom()->format('H:i'), array('attrs' => array('class' => 'slider-time-input-from hidden'))) ?>
                      <?php echo SLN_Form::fieldText($helper->getFieldName($postType, 'notav_to'), $service->getNotAvailableTo()->format('H:i'), array('attrs' => array('class' => 'slider-time-input-to hidden'))) ?>
                  </div>
                  <div class="clearfix"></div>
             </div>
           </div>
         </div>
       </div>
    </div>
</div>

<div class="sln-clear"></div>
