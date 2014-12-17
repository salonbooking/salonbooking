<?php
/**
 * @var SLN_Shortcode_Saloon_Step $step
 */

if (!isset($nextLabel)) {
    $nextLabel = __('Go next', 'sln');
}
?>
<div class="sln-separator"></div>
<div class="form-actions row">
    <div class="col-md-2">
        <?php $i = 0;
        $count   = count($steps);
        $saloon = $step->getShortcode();
        $steps = $saloon->getSteps();
        $current = $saloon->getCurrentStep();
        $count = count($steps);
        foreach ($steps as $step) {
            $i++;
            if ($current == $step) {
                echo "<span class=\"sln-step-num\"><span>$i</span>/" . $count . '</span>';
            }
        }
        ?>
    </div>
    <div class="col-md-5">
        <?php if ($backUrl) : ?>
            <a class="btn btn-danger btn-block" href="<?php echo $backUrl ?> ">
                <i class="glyphicon glyphicon-chevron-left"></i> Back
            </a>
        <?php endif ?>
    </div>
    <div class="col-md-5">
        <button type="submit" class="btn btn-success btn-block" name="<?php echo $submitName ?>" value="next">
            <?php echo $nextLabel ?> <i class="glyphicon glyphicon-chevron-right"></i>
        </button>
    </div>
</div>