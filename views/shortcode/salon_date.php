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
?>
<?php
if ($plugin->getSettings()->isDisabled()):
    $message =  $plugin->getSettings()->getDisabledMessage(); 
    ?>
    <div class="sln-alert sln-alert--problem">
        <?php echo empty($message) ? __('On-line booking is disabled', 'salon-booking-system') : $message ?>
    </div>
<?php
else:
        if($timezone = get_option('timezone_string'))
            date_default_timezone_set($timezone);


    $bb        = $plugin->getBookingBuilder();
    $intervals = $plugin->getIntervals($bb->getDateTime());
    $date      = $intervals->getSuggestedDate();
?>
    <form method="post" action="<?php echo $formAction ?>" id="salon-step-date" 
          data-intervals="<?php echo esc_attr(json_encode($intervals->toArray()));?>">
    <h2 class="salon-step-title"><?php _e('When do you want to come?', 'salon-booking-system') ?></h2>
        <?php
        $size = $_SESSION["size"];
        if ($size == '900') {
        ?>
        <div class="row sln-box--main">
            <div class="col-sm-6 col-md-4 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
                            'select a day',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSDate('sln[date]', $date) ?>
            </div>
           <div class="col-sm-6 col-md-4 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
                            'select an hour',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSTime('sln[time]', $date, array('interval' =>  $plugin->getSettings()->get('interval') )) ?>
            </div>
           <div class="col-sm-12 col-md-4 sln-input sln-box--formactions">
           <label class="hidden-xs hidden-sm" for="">&nbsp;</label>
           <?php include "_form_actions.php" ?></div>
        </div>
        <?php
        // IF SIZE == 900 // END
        } else if ($size == '600') {
        ?>
        <div class="row sln-box--main">
            <div class="col-md-6 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
                            'select a day',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSDate('sln[date]', $date) ?>
            </div>
           <div class="col-md-6 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
                            'select an hour',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSTime('sln[time]', $date, array('interval' =>  $plugin->getSettings()->get('interval') )) ?>
            </div>
        </div>
        <div class="row sln-box--main sln-box--formactions">
           <div class="col-md-12">
           <?php include "_form_actions.php" ?></div>
        </div>
        <?php
        // IF SIZE == 600 // END
        } else if ($size == '400') {
        ?>
        <div class="row sln-box--main">
            <div class="col-md-12 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
                            'select a day',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSDate('sln[date]', $date) ?>
            </div>
           <div class="col-md-12 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
                            'select an hour',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSTime('sln[time]', $date, array('interval' =>  $plugin->getSettings()->get('interval') )) ?>
            </div>
        </div>
        <div class="row sln-box--main sln-box--formactions">
           <div class="col-md-12">
           <?php include "_form_actions.php" ?></div>
        </div>
        <?php
        // IF SIZE == 400 // END
        } else {
        ?>
        <div class="row sln-box--main">
            <div class="col-md-4 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
                            'select a day',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSDate('sln[date]', $date) ?>
            </div>
           <div class="col-md-4 sln-input sln-input--datepicker">
                    <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
                            'select an hour',
                            'salon-booking-system'
                        ) ?></label>
                    <?php SLN_Form::fieldJSTime('sln[time]', $date, array('interval' =>  $plugin->getSettings()->get('interval') )) ?>
            </div>
           <div class="col-md-4">
           <label for="">&nbsp;</label>
           <?php include "_form_actions.php" ?></div>
        </div>
        <?php
        // IF SIZE ELSE // END
        }
        // errors are wrapped in a .row.sln-box--main
        include '_errors.php'; ?>
    </form>
<?php endif ?>

