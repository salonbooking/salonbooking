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
    if (!$intervalsArray['times']):
        $hb = $plugin->getAvailabilityHelper()->getHoursBeforeHelper()->getToDate();
        ?>
        <div class="sln-alert sln-alert--problem">
            <p><?php echo __('No more slots available until', 'salon-booking-system') ?><?php echo $plugin->format(
                )->datetime($hb) ?></p>
        </div>
    <?php else: ?>
        <form method="post" action="<?php echo $formAction ?>" id="salon-step-date"
              data-intervals="<?php echo esc_attr(json_encode($intervals->toArray())); ?>">
            <?php
            if(current_user_can('manage_options')) {
            ?>
            <input class="sln-edit-text" id="<?php _e('When do you want to come?', 'salon-booking-system') ?>"
                   value="<?php echo SLN_Plugin::getInstance()->getSettings()->getCustomText(__('When do you want to come?', 'salon-booking-system')); ?>" />
            <?php
            } else {
            ?>
                <h2 class="sln-step-title"><?php echo SLN_Plugin::getInstance()->getSettings()->getCustomText(__('When do you want to come?', 'salon-booking-system')); ?></h2>
            <?php
            }
            ?>
            <?php include '_salon_date_pickers.php' ?>
            <?php include '_errors.php'; ?>
        </form>
    <?php endif ?>
    <?php
}
