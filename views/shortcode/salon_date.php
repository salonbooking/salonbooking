<?php
/**
 * @var SLN_Plugin                   $plugin
 * @var string                       $formAction
 * @var string                       $submitName
 * @var SLN_Shortcode_Salon_DateStep $step
 */
if(!function_exists('salon_date_hoursbefore'))
{
function salon_date_hoursbefore($hoursBefore)
{
    if ($hoursBefore->from && $hoursBefore->to) : ?>
        <em><?php echo sprintf(
                __('you may book from %s up to %s in advance', 'salon-booking-system'),
                $hoursBefore->from,
                $hoursBefore->to
            ) ?></em>
    <?php elseif ($hoursBefore->from): ?>
        <em><?php echo sprintf(__('you may book %s in advance', 'salon-booking-system'), $hoursBefore->from) ?></em>
    <?php
    elseif ($hoursBefore->to) : ?>
        <em><?php echo sprintf(__('you may book up to %s in advance', 'salon-booking-system'), $hoursBefore->to) ?></em>
    <?php endif;
}
}

if ($plugin->getSettings()->isDisabled()):
    $message =  $plugin->getSettings()->getDisabledMessage(); 
    ?>
    <div class="alert alert-danger">
        <p><?php echo empty($message) ? __('On-line booking is disabled', 'salon-booking-system') : $message ?></p>
    </div>
<?php
else:
        if($timezone = get_option('timezone_string'))
            date_default_timezone_set($timezone);


    $bb        = $plugin->getBookingBuilder();
    $intervals = $plugin->getIntervals($bb->getDateTime());
    $date      = $intervals->getSuggestedDate();
    $intervalsArray = $intervals->toArray();

if (!$intervalsArray['times']):
    $hb = $plugin->getAvailabilityHelper()->getHoursBeforeHelper()->getToDate();
    ?>
    <div class="alert alert-danger">
        <p><?php echo __('No more slots available until', 'salon-booking-system') ?> <?php echo $plugin->format()->datetime($hb) ?></p>
    </div>
<?php
else:

    ?>
    <h2><?php _e('When do you want to come?', 'salon-booking-system') ?></h2>
    <form method="post" action="<?php echo $formAction ?>" id="salon-step-date" 
          data-intervals="<?php echo esc_attr(json_encode($intervalsArray));?>">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
                            'select a day',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSDate('sln[date]', $date) ?>
                </div>
            </div>
        </div>
        <div class="row">
           <div class="col-md-12">
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
                            'select an hour',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSTime('sln[time]', $date, array('interval' =>  $plugin->getSettings()->get('interval') )) ?>
                </div>
            </div>
        </div>
        <?php include '_errors.php' ?>
        <?php include "_form_actions.php" ?>
    </form>
<?php endif ?>
<?php endif ?>

