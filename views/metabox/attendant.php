<?php
$helper->showNonce($postType);
?>
<div class="row sln-service-price-time">
    <div class="col-sm-6 col-md-3 form-group sln-input--simple">
            <label for="_sln_attendant_email"><?php echo __('E-mail', 'salon-booking-system') ?></label>
            <input type="text" name="_sln_attendant_email" id="_sln_attendant_email" value="<?php echo $attendant->getEmail() ?>" class="form-control">
    </div>
    <div class="col-sm-6 col-md-3 form-group sln-select">
            <label for="_sln_attendant_phone"><?php echo __('Phone', 'salon-booking-system') ?></label>
            <input type="text" name="_sln_attendant_phone" id="_sln_attendant_phone" value="<?php echo $attendant->getPhone() ?>" class="form-control">
    </div>

    <div class="col-sm-12 col-md-6 form-group sln-select sln-select--multiple">
            <label><?php echo __('Services', 'salon-booking-system') ?></label>
            <select class="sln-select select2-hidden-accessible" multiple="multiple" data-placeholder="<?php _e('Select or search one or more services')?>"
                    name="_sln_attendant_services[]" id="_sln_attendant_services" tabindex="-1" aria-hidden="true">
                <?php foreach ($plugin->getServices() as $service) : ?>
                    <option
                        class="red"
                        value="sln_attendant_services_<?php echo $service->getId() ?>"
                        data-price="<?php echo $service->getPrice(); ?>"
                        <?php echo $attendant->hasService($service) ? 'selected="selected"' : '' ?>
                        ><?php echo $service->getName(); ?>
                        (<?php echo $plugin->format()->money($service->getPrice()) ?>)
                    </option>
                <?php endforeach ?>
            </select>
    </div>
</div>

<div class="sln-box--sub sln-booking-rules row">
    <div class="col-xs-6">
        <h2 class="sln-box-title"><?php _e('Not available on','salon-booking-system'); ?></h2>
        <p class="help-block"><?php _e('Leave this option blank if you want this attendant available for every hour each day', 'salon-booking-system') ?></p>
    </div>
    <div class="sln-clear"></div>
    <div id="sln-booking-rules-wrapper">
        <div class="col-xs-12 sln-booking-rule">
            <h6 class="sln-fake-label"><?php echo __('Not available days checked and green.', 'salon-booking-system') ?></h6>
            <div class="sln-checkbutton-group">
        <?php foreach (SLN_Func::getDays() as $k => $day) : ?>
            <div class="sln-checkbutton">
                    <?php SLN_Form::fieldCheckboxButton(
                        $helper->getFieldName($postType, 'notav_' . $k),
                        ($attendant->getNotAvailableOn($k) ? 1 : null),
                        $label = substr($day, 0, 3)
                    ) ?>
            </div>
        <?php endforeach ?>
            <div class="clearfix"></div>
        </div>
        <div class="row">
           <div class="col-xs-12 col-md-8 sln-slider-wrapper">
              <div class="sln-slider">
                  <div class="sliders_step1 col col-slider"><div class="slider-range"></div></div>
                  <div class="col col-time"><span class="slider-time-from">0:00</span> to <span class="slider-time-to">12:00</span>
                      <?php echo SLN_Form::fieldText($helper->getFieldName($postType, 'notav_from'), $attendant->getNotAvailableFrom()->format('H:i'), array('attrs' => array('class' => 'slider-time-input-from hidden'))) ?>
                      <?php echo SLN_Form::fieldText($helper->getFieldName($postType, 'notav_to'), $attendant->getNotAvailableTo()->format('H:i'), array('attrs' => array('class' => 'slider-time-input-to hidden'))) ?>
                  </div>
                  <div class="clearfix"></div>
             </div>
           </div>
         </div>
       </div>
    </div>
</div>
<div class="sln-clear"></div>
