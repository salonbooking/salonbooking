<?php
/**
 * @var $date DateTime
 * @var $plugin SLN_Plugin
 * @var SLN_Shortcode_Salon_DateStep $step
 */
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
?>
<?php ob_start(); ?>
<label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
        'select a day',
        'salon-booking-system'
    ) ?></label>
<?php SLN_Form::fieldJSDate('sln[date]', $date) ?>
<?php $datepicker = ob_get_clean();
ob_start(); ?>
<label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
        'select an hour',
        'salon-booking-system'
    ) ?></label>
<?php SLN_Form::fieldJSTime('sln[time]', $date, array('interval' => $plugin->getSettings()->get('interval'))) ?>
<?php $timepicker = ob_get_clean(); ?>

<?php if ($size == '900'): ?>
    <div class="row sln-box--main">
        <div class="col-sm-6 col-md-4 sln-input sln-input--datepicker">
            <?php echo $datepicker ?>
        </div>
        <div class="col-sm-6 col-md-4 sln-input sln-input--datepicker">
            <?php echo $timepicker ?>
        </div>
        <div class="col-sm-12 col-md-4 sln-input sln-box--formactions">
            <label class="hidden-xs hidden-sm" for="">&nbsp;</label>
            <?php include "_form_actions.php" ?>
        </div>
    </div>
<?php elseif ($size == '600') : ?>
    <div class="row sln-box--main">
        <div class="col-sm-6 col-md-6 sln-input sln-input--datepicker">
            <?php echo $datepicker ?>

        </div>
        <div class="col-sm-6 col-md-6 sln-input sln-input--datepicker">
            <?php echo $timepicker ?>
        </div>
    </div>
    <div class="row sln-box--main sln-box--formactions">
        <div class="col-md-12"><?php include "_form_actions.php" ?></div>
    </div>
<?php elseif ($size == '400'): ?>
    <div class="row sln-box--main">
        <div class="col-md-12 sln-input sln-input--datepicker">
            <?php echo $datepicker ?>
        </div>
        <div class="col-md-12 sln-input sln-input--datepicker">
            <?php echo $timepicker ?>
        </div>
    </div>
    <div class="row sln-box--main sln-box--formactions">
        <div class="col-md-12"><?php include "_form_actions.php" ?></div>
    </div>
<?php else: ?>
    <?php throw new Exception('size not managed') ?>
<?php endif ?>