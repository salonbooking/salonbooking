<?php
/**
 * @var SLN_Shortcode_Salon_Step $step
 * @var $submitName
 * @var $nextLabel
 * @var $backUrl
 */

if (!isset($nextLabel)) {
    $nextLabel = __('Next step', 'salon-booking-system');
}
$i       = 0;
$salon  = $step->getShortcode();
$steps   = $salon->getSteps();
$count   = count($steps);
$current = $salon->getCurrentStep();
$count   = count($steps);
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
foreach ($steps as $step) {
    $i++;
    if ($current == $step) {
        $currentNum = $i;
    }
}
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();

ob_start();
?>
            <button
                <?php if($ajaxEnabled): ?>
                    data-salon-data="<?php echo "sln_step_page=$current&$submitName=next" ?>" data-salon-toggle="next"
                <?php endif?>
                id="sln-step-submit" type="submit" name="<?php echo $submitName ?>" value="next">
                <?php echo $nextLabel ?> <i class="glyphicon glyphicon-chevron-right"></i>
            </button>
<?php
$nextBtn = ob_get_clean();
if ($backUrl && $currentNum > 1){
ob_start();
?>
            <a class="sln-btn <?php echo $size == '900' ? 'sln-btn--nobkg' : 'sln-btn--borderonly'?> sln-btn--medium sln-btn--icon sln-btn--icon--left sln-icon--back"
                <?php if($ajaxEnabled): ?>
                    data-salon-data="<?php echo "sln_step_page=".$salon->getPrevStep() ?>" data-salon-toggle="direct"
                <?php endif?>
                href="<?php echo $backUrl ?> ">
                <i class="glyphicon glyphicon-chevron-left"></i> <?php _e('Back', 'salon-booking-system') ?>
            </a> 
<?php
$backBtn = ob_get_clean();
}
?>
<?php
        if ($size == '900') {
        ?>
        <div class="sln-box--formactions form-actions row">
    <div class="col-xs-12 col-sm-6 pull-right">
        <div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
            <?php echo $nextBtn ?>
        </div>
    </div>
        <?php if (isset($backBtn)) : ?>
        <div class="col-xs-12 col-sm-5 pull-right">
            <?php echo $backBtn ?>
        </div>
        <div class="hidden-xs hidden-sm col-md-1 pull-right"></div>
        <?php endif ?>
            <?php /* if ($currentNum > 1): ?>
                <span class="sln-step-num"><?php echo sprintf(__('step %s of %s', 'salon-booking-system'), $currentNum, $count) ?></span>
            <?php endif */ ?>
</div>
<?php
        // IF SIZE == 900 // END
        } else if ($size == '600') {
        ?>
        <div class="sln-box--formactions form-actions row">
    <div class="col-xs-12 col-sm-6 col-md-6 pull-right">
        <div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
            <?php echo $nextBtn ?>
        </div>
    </div>
        <?php if (isset($backBtn)) : ?>
        <div class="col-xs-12 col-sm-6 col-md-6 pull-right">
            <?php echo $backBtn ?>
        </div>
        <?php endif ?>
            <?php /* if ($currentNum > 1): ?>
                <span class="sln-step-num"><?php echo sprintf(__('step %s of %s', 'salon-booking-system'), $currentNum, $count) ?></span>
            <?php endif */ ?>
</div>
        <?php
        // IF SIZE == 600 // END
        } else if ($size == '400') {
        ?>
<div class="sln-box--formactions form-actions row">
    <div class="col-xs-12 col-sm-6 col-md-7 pull-right">
        <div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
            <?php echo $nextBtn ?>
        </div>
    </div>
        <?php if (isset($backBtn)) : ?>
        <div class="col-xs-12 col-sm-6 col-md-5 pull-right">
            <?php echo $backBtn ?>
        </div>
        <div class="col-md-1 pull-right"></div>
        <?php endif ?>
            <?php /* if ($currentNum > 1): ?>
                <span class="sln-step-num"><?php echo sprintf(__('step %s of %s', 'salon-booking-system'), $currentNum, $count) ?></span>
            <?php endif */ ?>
</div>
        <?php
        // IF SIZE == 400 // END
        } else {
        ?>
        <div class="form-actions row">
    <div class="col-md-7 pull-right">
        <div class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth">
            <?php echo $nextBtn ?>
        </div>
    </div>
        <div class="col-md-4 pull-right">
            <?php if (isset($backBtn)) : ?>
                <?php echo $backBtn ?>
            <?php endif ?>
        </div>
        <div class="col-md-1 pull-right"></div>
            <?php /* if ($currentNum > 1): ?>
                <span class="sln-step-num"><?php echo sprintf(__('step %s of %s', 'salon-booking-system'), $currentNum, $count) ?></span>
            <?php endif */ ?>
</div>
        <?php
        // IF SIZE ELSE // END
        }
        ?>
