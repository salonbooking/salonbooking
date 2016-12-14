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
    $args = array(
        'label'        => __('Booking summary', 'salon-booking-system'),
        'tag'          => 'h2',
        'textClasses'  => 'salon-step-title',
        'inputClasses' => '',
        'tagClasses'   => 'salon-step-title',
    );
    echo $plugin->loadView('shortcode/_editable_snippet', $args);
    ?>
    <div class="row">
        <div class="col-md-8">
            <p class="sln-text--dark">
                <?php if(!SLN_Enum_CheckoutFields::isHidden('firstname') || !SLN_Enum_CheckoutFields::isHidden('lastname')) {
                    _e('Dear', 'salon-booking-system');
                ?>
                    <strong><?php echo (!SLN_Enum_CheckoutFields::isHidden('firstname') ? esc_attr($bb->get('firstname')) : '').' '.(!!SLN_Enum_CheckoutFields::isHidden('lastname') ? esc_attr($bb->get('lastname')) : ''); ?></strong>
                    <br/>
                <?php } ?>
                <?php _e('Here are the details of your booking:', 'salon-booking-system') ?>
            </p>
        </div>
    </div>
    <?php include '_salon_summary_'.$size.'.php'; ?>
</form>