<?php
$helper->showNonce($postType);
?>
<h3><?php _e('Not Available At','sln'); ?></h3>
<?php
$days = SLN_Func::getDays();
?>
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
            <?php echo __('From', 'sln') ?>
            <?php SLN_Form::fieldTime(
                $helper->getFieldName($postType, 'notav_from'),
                $attendant->getNotAvailableFrom()
            ) ?>
        </label>
    </div>
    <div class="col-xs-6 col-md-3 col-lg-2 col-sm-3  attendants-notavailable-h">
        <label>
            <?php echo __('To', 'sln') ?>
            <?php SLN_Form::fieldTime(
                $helper->getFieldName($postType, 'notav_to'),
                $attendant->getNotAvailableTo()
            ) ?>
        </label>

    </div>
</div>
<em><?php _e('Leave blank if you want this attendant available everydays at every hour', 'sln') ?></em>
<div class="sln-clear"></div>
