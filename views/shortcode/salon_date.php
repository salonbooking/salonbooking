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
    $intervalsArray = $intervals->toArray();

    if ($plugin->getSettings()->isFormStepsAltOrder()) {
        foreach($intervalsArray['times'] as $k => $t) {
            $tempDate = new SLN_DateTime($intervalsArray['suggestedYear'].'-'.$intervalsArray['suggestedMonth'].'-'.$intervalsArray['suggestedDay'].' '.$t);
            $obj = new SLN_Action_Ajax_CheckDateAlt($plugin);
            $tempErrors = $obj->checkDateTimeServicesAndAttendants($tempDate);
            if (!empty($tempErrors)) {
                unset($intervalsArray['times'][$k]);
            }
        }
        if (!isset($intervalsArray['times'][$date->format('H:i')]) && !empty($intervalsArray['times'])) {
            $date = new SLN_DateTime($intervalsArray['suggestedYear'].'-'.$intervalsArray['suggestedMonth'].'-'.$intervalsArray['suggestedDay'].' '.reset($intervalsArray['times']));
        }
    }

    if (!$plugin->getSettings()->isFormStepsAltOrder() && !$intervalsArray['times']):
        $hb = $plugin->getAvailabilityHelper()->getHoursBeforeHelper()->getToDate();
        ?>
        <div class="sln-alert sln-alert--problem">
            <p><?php echo __('No more slots available until', 'salon-booking-system') ?><?php echo $plugin->format(
                )->datetime($hb) ?></p>
        </div>
    <?php else: ?>
        <form method="post" action="<?php echo $formAction ?>" id="salon-step-date"
              data-intervals="<?php echo esc_attr(json_encode($intervalsArray)); ?>">
            <?php
            $label = __('When do you want to come?', 'salon-booking-system');
            $value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

            if(current_user_can('manage_options')) {
            ?>
                <div class="editable">
                    <h2 class="salon-step-title text">
                        <?php echo $value; ?>
                    </h2>
                    <div class="input">
                        <input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
                    </div>
                    <i class="fa fa-gear fa-fw"></i>
                </div>
            <?php
            } else {
            ?>
                <h2 class="salon-step-title"><?php echo $value; ?></h2>
            <?php
            }
            ?>
            <?php include '_salon_date_pickers.php' ?>
            <?php include '_errors.php'; ?>
        </form>
    <?php endif ?>
    <?php
}
