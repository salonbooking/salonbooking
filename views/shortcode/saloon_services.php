<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Saloon_ServicesStep $step
 */
$bb             = $plugin->getBookingBuilder();
$currencySymbol = $plugin->getSettings()->getCurrencySymbol();
$services = $step->getServices();
?>
<h2>What do you need?</h2>
<form id="saloon-step-services" method="post" action="<?php echo $formAction ?>" role="form">
    <?php include '_errors.php' ?>
    <?php include "_services.php"; ?>
    <?php include "_form_actions.php" ?>
</form>