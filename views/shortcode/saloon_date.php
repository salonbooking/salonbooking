<?php
/**
 * @var SLN_Plugin                $plugin
 * @var string                    $formAction
 * @var string                    $submitName
 * @var SLN_Shortcode_Saloon_Step $step
 */
$bb = $plugin->getBookingBuilder();
include __DIR__ . 'functions.php';

$date = $bb->getDate();
if (!($date instanceof \DateTime)) {
    $date = new \Datetime('+1 day');
}

?>
<h2>When do you want to come?</h2>
<form method="post" action="<?php echo $formAction ?>">
    <?php include '_errors.php' ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>">select a day</label>
                <?php SLN_Form::fieldDay('sln[date][day]', $date) ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="<?php echo SLN_Form::makeID('sln[date][month]') ?>">select a month</label>
                <?php SLN_Form::fieldMonth('sln[date][month]', $date) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="<?php echo SLN_Form::makeID('sln[date][year]') ?>">select a year</label>
                <?php SLN_Form::fieldYear('sln[date][year]', $date) ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>">select an hour</label>
                <?php SLN_Form::fieldTime('sln[time]', $bb->getTime()) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-success btn-block" name="<?php echo $submitName ?>" value="next">Next
            </button>
        </div>
    </div>
</form>