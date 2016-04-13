<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 * @var SLN_Shortcode_Salon_ThankyouStep $step
 */
$confirmation = $plugin->getSettings()->get('confirmation');
$currentStep = $step->getShortcode()->getCurrentStep();
$ajaxData = "sln_step_page=$currentStep&submit_$currentStep=1";
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();

$paymentMethod = $plugin->getSettings()->get('pay_enabled') ? 
SLN_Enum_PaymentMethodProvider::getService($plugin->getSettings()->getPaymentMethod(), $plugin)
: false;
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);

?>
<div id="salon-step-thankyou" class="row sln-thankyou">
<?php
if ($size == '900') {
    include '_salon_thankyou_900.php';
} elseif ($size == '600') {
    include '_salon_thankyou_600.php';
} elseif ($size == '400') {
    include '_salon_thankyou_400.php';
} else {
    throw new Exception('size not managed');
} ?>
</div>
