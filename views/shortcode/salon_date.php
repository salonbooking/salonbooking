<?php
/**
 * @var SLN_Plugin $plugin
 * @var string $formAction
 * @var string $submitName
 */
if ($plugin->getSettings()->isDisabled()) {
    $message = $plugin->getSettings()->getDisabledMessage();
    ?>
    <div class="sln-alert sln-alert--problem">
        <?php echo empty($message) ? __('On-line booking is disabled', 'salon-booking-system') : $message ?>
    </div>
    <?php
} else {
    if ($timezone = get_option('timezone_string')) {
        date_default_timezone_set($timezone);
    }
    $bb = $plugin->getBookingBuilder();
    $intervals = $plugin->getIntervals($bb->getDateTime());
    $date = $intervals->getSuggestedDate();

    if ($plugin->getSettings()->isFormStepsAltOrder()) {
        $obj = new SLN_Action_Ajax_CheckDateAlt($plugin);
        $obj->setDate(SLN_Func::filter($date, 'date'))->setTime(SLN_Func::filter($date, 'time'));
        $intervalsArray = $obj->getIntervalsArray();
        $date = new SLN_DateTime($intervalsArray['suggestedYear'].'-'.$intervalsArray['suggestedMonth'].'-'.$intervalsArray['suggestedDay'].' '.$intervalsArray['suggestedTime']);
        $errors = $obj->checkDateTimeServicesAndAttendants($bb->getAttendantsIds(), $date);
    } else {
        $intervalsArray = $intervals->toArray();
    }

    if (!$plugin->getSettings()->isFormStepsAltOrder() && !$intervalsArray['times']):
        $hb = $plugin->getAvailabilityHelper()->getHoursBeforeHelper()->getToDate();
        ?>
        <div class="sln-alert sln-alert--problem">
            <p><?php echo __('No more slots available until', 'salon-booking-system') ?> <?php echo $plugin->format(
                )->datetime($hb) ?></p>
        </div>
    <?php else: ?>
        <form method="post" action="<?php echo $formAction ?>" id="salon-step-date"
              data-intervals="<?php echo esc_attr(json_encode($intervalsArray)); ?>">
            <?php
            $args = array(
                'label'        => __('When do you want to come?', 'salon-booking-system'),
                'tag'          => 'h2',
                'textClasses'  => 'salon-step-title',
                'inputClasses' => '',
                'tagClasses'   => 'salon-step-title',
            );
            echo $plugin->loadView('shortcode/_editable_snippet', $args);
            ?>
            <?php include '_salon_date_pickers.php' ?>
            <?php include '_errors.php'; ?>
        </form>
    <?php endif ?>
    <?php
}
