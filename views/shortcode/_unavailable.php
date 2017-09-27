<div class="row sln-box--main">
    <div class="col-md-12">
        <div class="sln-alert sln-alert--warning">
            <p><?php _e('SORRY! This slot is no more available', 'salon-booking-system') ?></p>
            <hr/>
            <p><strong><?php _e('Please start over your reservation', 'salon-booking-system') ?></strong></p>
        </div>
    </div>
</div>
<?php
/** @var SLN_Shortcode_Salon_SummaryStep $step */

$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();
$step = $step->getShortcode()->getSteps();
$step = $step[0];
$backUrl = add_query_arg(array('sln_step_page' => $step));
$nextBtn = '';
ob_start();
?>
<a class="sln-btn <?php echo $size == '900' ? 'sln-btn--nobkg' : 'sln-btn--borderonly'?> sln-btn--medium sln-btn--icon sln-btn--icon--left sln-icon--back"
    <?php if($ajaxEnabled): ?>
        data-salon-data="<?php echo "sln_step_page=".$step ?>" data-salon-toggle="direct"
    <?php endif?>
   href="<?php echo $backUrl ?> ">
    <i class="glyphicon glyphicon-chevron-left"></i> <?php _e('Back', 'salon-booking-system') ?>
</a>
<?php
$backBtn = ob_get_clean();
?>
<?php
if ($size == '900') {
    ?>
    <div class="sln-box--formactions form-actions row">
        <?php if (isset($backBtn)) : ?>
            <div class="col-xs-12 col-sm-5 pull-right">
                <?php echo $backBtn ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-1 pull-right"></div>
        <?php endif ?>
    </div>
    <?php
    // IF SIZE == 900 // END
} else if ($size == '600') {
    ?>
    <div class="sln-box--formactions form-actions row">
        <?php if (isset($backBtn)) : ?>
            <div class="col-xs-12 col-sm-6 col-md-6 pull-right">
                <?php echo $backBtn ?>
            </div>
        <?php endif ?>
    </div>
    <?php
    // IF SIZE == 600 // END
} else if ($size == '400') {
    ?>
    <div class="sln-box--formactions form-actions row">
        <?php if (isset($backBtn)) : ?>
            <div class="col-xs-12 col-sm-6 col-md-5 pull-right">
                <?php echo $backBtn ?>
            </div>
            <div class="col-md-1 pull-right"></div>
        <?php endif ?>
    </div>
    <?php
    // IF SIZE == 400 // END
} else {
    ?>
    <div class="form-actions row">
        <div class="col-md-4 pull-right">
            <?php if (isset($backBtn)) : ?>
                <?php echo $backBtn ?>
            <?php endif ?>
        </div>
        <div class="col-md-1 pull-right"></div>
    </div>
    <?php
    // IF SIZE ELSE // END
}