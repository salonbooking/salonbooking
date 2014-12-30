<?php
/**
 * @var SLN_Plugin                    $plugin
 * @var string                        $formAction
 * @var string                        $submitName
 * @var SLN_Shortcode_Saloon_DateStep $step
 */
$bb          = $plugin->getBookingBuilder();
$date        = $bb->getDate();
$hoursBefore = $plugin->getAvailabilityHelper()->getHoursBeforeString();
if (!($date instanceof \DateTime)) {
    $date = $plugin->getAvailabilityHelper()->getHoursBeforeDateTime()->from;
}else{
    $date = new DateTime($date.' '.$bb->getTime());
}
if ($plugin->getSettings()->isDisabled()):
    ?>
    <div class="alert alert-danger">
        <p><?php echo $plugin->getSettings()->getDisabledMessage() ?></p>
    </div>
<?php
else:
    ?>
    <h2><?php _e('When do you want to come?', 'sln') ?>
        <?php if ($hoursBefore->from && $hoursBefore->to) : ?>
            <em><?php echo sprintf(
                    __('you can book by %s up to %s before', 'sln'),
                    $hoursBefore->from,
                    $hoursBefore->to
                ) ?></em>
        <?php elseif ($hoursBefore->from): ?>
            <em><?php echo sprintf(__('you can book by %s before', 'sln'), $hoursBefore->from) ?></em>
        <?php
        elseif ($hoursBefore->to) : ?>
            <em><?php echo sprintf(__('you can book up to %s before', 'sln'), $hoursBefore->to) ?></em>
        <?php endif ?>
    </h2>
    <form method="post" action="<?php echo $formAction ?>" id="saloon-step-date">
        <?php include '_errors.php' ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
                            'select a day',
                            'sln'
                        ) ?></label>
                    <?php SLN_Form::fieldDay('sln[date][day]', $date) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID('sln[date][month]') ?>"><?php _e(
                            'select a month',
                            'sln'
                        ) ?></label>
                    <?php SLN_Form::fieldMonth('sln[date][month]', $date) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID('sln[date][year]') ?>"><?php _e(
                            'select a year',
                            'sln'
                        ) ?></label>
                    <?php SLN_Form::fieldYear('sln[date][year]', $date) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
                            'select an hour',
                            'sln'
                        ) ?></label>
                    <?php SLN_Form::fieldTime('sln[time]', $date) ?>
                </div>
            </div>
        </div>
        <?php include "_form_actions.php" ?>
    </form>
<?php endif ?>