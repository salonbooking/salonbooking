<?php
/**
 * @var SLN_Shortcode_Salon_Step $step
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
foreach ($steps as $step) {
    $i++;
    if ($current == $step) {
        $currentNum = $i;
    }
}
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();
?>
<div class="form-actions row">
    <div class="col-md-7 pull-right">
        <div class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth">
            <button
                <?php if($ajaxEnabled): ?>
                    data-salon-data="<?php echo "sln_step_page=$current&$submitName=next" ?>" data-salon-toggle="next"
                <?php endif?>
                id="sln-step-submit" type="submit" class="" name="<?php echo $submitName ?>" value="next">
                <?php echo $nextLabel ?> <i class="glyphicon glyphicon-chevron-right"></i>
            </button>
        </div>
    </div>
        <div class="col-md-4 pull-right">
            <a class="sln-btn sln-btn--nobkg sln-btn--big"
                <?php if($ajaxEnabled): ?>
                    data-salon-data="<?php echo "sln_step_page=".$salon->getPrevStep() ?>" data-salon-toggle="direct"
                <?php endif?>
                href="<?php echo $backUrl ?> ">
                <i class="glyphicon glyphicon-chevron-left"></i> <?php _e('Back', 'salon-booking-system') ?>
            </a>
        </div>
        <div class="col-md-1 pull-right"></div>
        <?php if ($backUrl && $currentNum > 1) : ?>
        <?php endif ?>
            <?php /* if ($currentNum > 1): ?>
                <span class="sln-step-num"><?php echo sprintf(__('step %s of %s', 'salon-booking-system'), $currentNum, $count) ?></span>
            <?php endif */ ?>
</div>
