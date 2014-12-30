<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Saloon_ServicesStep $step
 */
$bb = $plugin->getBookingBuilder();
$services = $step->getServices();
?>
<h1>Something more?</h1>
<form id="saloon-step-secondary" method="post" action="<?php echo $formAction ?>" role="form">
    <?php include "_services.php"; ?>
    <?php include "_form_actions.php" ?>
</form>