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
    $label = __('Booking summary', 'salon-booking-system');
    $value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

    if(current_user_can('manage_options')) {
    ?>
        <div class="editable">
            <div class="text">
                <?php echo $value; ?>
            </div>
            <div class="input">
                <input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
            </div>
            <i class="fa fa-gear fa-fw"></i>
        </div>
    <?php
    } else {
    ?>
        <h2 class="sln-step-title"><?php echo $value; ?></h2>
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