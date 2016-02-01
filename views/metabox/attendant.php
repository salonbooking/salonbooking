<?php
$helper->showNonce($postType);
?>

<?php
$days = SLN_Func::getDays();
?>
<div class="row">

    <div class="col-xs-3 col-md-3 col-lg-3 col-sm-3  attendants-notavailable-h">
        <?php
        $helper->showFieldtext(
            $helper->getFieldName($postType, 'email'),
            __('E-mail', 'salon-booking-system'),
            $attendant->getEmail()
        ); ?>
    </div>
    <div class="col-xs-3 col-md-3 col-lg-3 col-sm-3  attendants-notavailable-h">
        <?php
        $helper->showFieldtext(
            $helper->getFieldName($postType, 'phone'),
            __('Phone', 'salon-booking-system'),
            $attendant->getPhone()
        ); ?>
    </div>
    <div class="col-xs-6 col-md-6 col-lg-6 col-sm-6 sln-select-wrapper">
        <label><?php _e('Services', 'salon-booking-system'); ?></label>
        <select class="sln-select" multiple="multiple" data-placeholder="<?php _e('Select or search one or more services', 'salon-booking-system')?>"
                name="_sln_attendant_services[]" id="_sln_attendant_services">
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
<h3><?php _e('Not available on','salon-booking-system'); ?></h3>
<div class="row">
    <div class="col-md-12 attendants-notavailable">
        <?php foreach ($days as $k => $day) { ?>
            <label>
                <?php SLN_Form::fieldCheckbox(
                    $helper->getFieldName($postType, 'notav_' . $k),
                    $attendant->getNotAvailableOn($k)
                ) ?>
                <?php echo substr($day, 0, 3) ?>
            </label>
        <?php } ?>
    </div>
    <div class="col-xs-6 col-md-3 col-lg-2 col-sm-3  attendants-notavailable-h">
        <label>
            <?php echo __('From', 'salon-booking-system') ?>
            <?php SLN_Form::fieldTime(
                $helper->getFieldName($postType, 'notav_from'),
                $attendant->getNotAvailableFrom()
            ) ?>
        </label>
    </div>
    <div class="col-xs-6 col-md-3 col-lg-2 col-sm-3  attendants-notavailable">
        <label>
            <?php echo __('To', 'salon-booking-system') ?>
            <?php SLN_Form::fieldTime(
                $helper->getFieldName($postType, 'notav_to'),
                $attendant->getNotAvailableTo()
            ) ?>
        </label>
    </div>
    <div class="col-xs-12 col-md-12 col-lg-12 col-sm-12  attendants-notavailable-h">
    <em><?php _e('Leave this option blank if you want this assistant available for every hour each day', 'salon-booking-system') ?></em>
</div>

</div>
<div class="sln-clear"></div>
