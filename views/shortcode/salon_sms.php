<?php
/**
 * @var SLN_Plugin $plugin
 * @var string $formAction
 * @var string $submitName
 * @var SLN_Shortcode_Salon_Step $step
 */
$bb = $plugin->getBookingBuilder();
$valid = isset($_SESSION['sln_sms_valid']) ? $_SESSION['sln_sms_valid'] : false;
$currentStep = $step->getShortcode()->getCurrentStep();
$ajaxData = "sln_step_page=$currentStep&submit_$currentStep=1";
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
?>
<?php if (isset($_GET['resend'])): ?>
    <div class="alert alert-success">
        <p><?php _e('SMS sent successfully.') ?></p>
    </div>
<?php endif ?>
<h2><?php _e('SMS Verification', 'salon-booking-system') ?>
    <br/>
    <em><?php _e('We have sent an SMS text on your mobile phone.', 'salon-booking-system') ?></em>
</h2>
<form method="post" action="<?php echo $formAction ?>" role="form">
    <?php if ($valid): ?>
        <div class="alert alert-success">
            <p><?php _e('Your telephone number is verified', 'salon-booking-system') ?></p>
        </div>
        <?php include "_form_actions.php" ?>
    <?php else: ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID('sln_verification') ?>">
                        <?php _e('digit your verification code', 'salon-booking-system'); ?>
                    </label>
                </div>
            </div>
                        <div class="col-md-6">
                <div class="form-group">
                   <?php SLN_Form::fieldText('sln_verification', '', array('required' => true)) ?>
                    <a href="<?php echo $formAction ?>&resend=1" class="recover"
                        <?php if($ajaxEnabled): ?>
                       data-salon-data="<?php echo $ajaxData.'&resend=1' ?>" data-salon-toggle="direct"
                        <?php endif ?>>
                        <?php _e('I didn\'t received the code, please send it again', 'salon-booking-system') ?>
                    </a>
                </div>
            </div>
        </div>
        <?php include '_errors.php'; ?>
        <?php include "_form_actions.php"; ?>
    <?php endif ?>
</form>

