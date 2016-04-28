<?php
/**
 * @var SLN_Plugin $plugin
 * @var string $formAction
 * @var string $submitName
 * @var SLN_Shortcode_Salon_Step $step
 */
$bb = $plugin->getBookingBuilder();
$currencySymbol = $plugin->getSettings()->getCurrencySymbol();
$datetime = $bb->getDateTime();
$confirmation = $plugin->getSettings()->get('confirmation');
$showPrices = ($plugin->getSettings()->get('hide_prices') != '1') ? true : false;
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
?>
<form method="post" action="<?php echo $formAction ?>" role="form" id="salon-step-summary">
    <?php
    if(current_user_can('manage_options')) {
        ?>
        <input class="sln-edit-text" id="<?php _e('Booking summary', 'salon-booking-system') ?>"
               value="<?php echo SLN_Plugin::getInstance()->getSettings()->getCustomText(__('Booking summary', 'salon-booking-system')); ?>" />
        <?php
    } else {
        ?>
        <h2 class="sln-step-title"><?php echo SLN_Plugin::getInstance()->getSettings()->getCustomText(__('Booking summary', 'salon-booking-system')); ?></h2>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-md-8">
            <p class="sln-text--dark"><?php _e('Dear', 'salon-booking-system') ?>
                <strong><?php echo esc_attr($bb->get('firstname')).' '.esc_attr($bb->get('lastname')); ?></strong>
                <br/>
                <?php _e('Here the details of your booking:', 'salon-booking-system') ?>
            </p>
        </div>
    </div>
    <?php
    if ($size == '900') {
        include '_salon_summary_900.php';
    } elseif ($size == '600') {
        include '_salon_summary_600.php';
    } elseif ($size == '400') {
        include '_salon_summary_400.php';
    } else {
        throw new Exception('size not managed');
    } ?>
</form>