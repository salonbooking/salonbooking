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

?>
<div id="salon-step-thankyou">
    <?php if($confirmation) : ?>
        <h2><?php _e('Booking status', 'sln') ?></h2>
    <?php else : ?> 
        <h2><?php _e('Booking Confirmation', 'sln') ?></h2>
    <?php endif ?>

    <?php include '_errors.php'; ?>

    <?php if (isset($payOp) && $payOp == 'cancel'): ?>

        <div class="alert alert-danger">
            <p><?php _e('The payment is failed, please try again.', 'sln') ?></p>
        </div>

    <?php else: ?>
        <div class="row">
            <div class="col-md-6 tycol"><?php echo $confirmation ? __('Your booking is pending', 'sln') : __('Your booking is confirmed', 'sln') ?><br/>
                <?php if($confirmation): ?> 
                    <i class="c glyphicon glyphicon-time"></i>
                <?php else : ?>
                    <i class="glyphicon glyphicon-ok-circle"></i>
                <?php endif ?>
            </div>
            <div class="col-md-6 tycol"><?php _e('Booking number', 'sln') ?>
                <br/><span class="num"><?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></span>
            </div>
        </div>
<?php $ppl = false; ?>
<div class="alert ty">
<?php if($confirmation) : ?>
        <p><strong><?php _e('You will receive a confirmation of your booking by email.','sln' )?></strong></p>
        <p><?php echo sprintf(__('If you don\'t receive any news from us or you need to change your reservation please call the %s or send an e-mail to %s', 'sln'), $plugin->getSettings()->get('gen_phone'),  $plugin->getSettings()->get('gen_email') ? $plugin->getSettings()->get('gen_email') : get_option('admin_email') ); ?>
            <?php echo $plugin->getSettings()->get('gen_phone') ?></p>
        <p class="aligncenter"><a href="<?php echo home_url() ?>" class="btn btn-primary"><?php _e('Back to home','sln') ?></a></p>
<?php else : ?>
        <p><?php echo sprintf(__('If you need to change your reservation please call the <strong>%s</strong> or send an e-mail to <strong>%s</strong>', 'sln'), $plugin->getSettings()->get('gen_phone'),  $plugin->getSettings()->get('gen_email') ? $plugin->getSettings()->get('gen_email') : get_option('admin_email') ); ?>
            <?php echo $plugin->getSettings()->get('phone') ?>
        </p>
        </div>

            <div id="sln-notifications"></div>
    <div class="row form-actions aligncenter">
        <?php if($paymentMethod): ?>
<?php if($paymentMethod->getMethodKey() == 'paypal'): ?>
        <a data-salon-data="<?php echo $ajaxData.'&mode='.$paymentMethod->getMethodKey() ?>" data-salon-toggle="direct"
        href="<?php echo $payUrl ?>" class="btn btn-primary">
            <?php $deposit = $plugin->getBookingBuilder()->getLastBooking()->getDeposit(); ?> 
            <?php if($deposit > 0): ?>
                <?php echo sprintf(__('Pay %s as a deposit with %s', 'sln'), $plugin->format()->money($deposit), $paymentMethod->getMethodLabel()) ?>
            <?php else : ?>
                <?php sprintf(_e('Pay with %s', 'sln'), $paymentMethod->getMethodLabel()) ?>
            <?php endif ?>
        </a>
<?php elseif($paymentMethod->getMethodKey() == 'stripe'): ?>
<form method="POST" action="<?php echo $booking->getPayUrl() ?>&mode=<?php echo $paymentMethod->getMethodKey() ?>">
<script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="<?php echo $paymentMethod->getApiKeyPublic()?>"
<?php /*    data-image="/img/documentation/checkout/marketplace.png"
    data-name="Stripe.com"
*/?>
    data-description="Booking #<?php echo $booking->getId() ?>"
    data-amount="<?php echo intval($booking->getToPayAmount() * 100) ?>"
    data-label="<?php $deposit = $plugin->getBookingBuilder()->getLastBooking()->getDeposit(); ?>
            <?php if($deposit > 0): ?>
                <?php echo sprintf(__('Pay %s as a deposit with %s', 'sln'), $plugin->format()->money($deposit), $paymentMethod->getMethodLabel()) ?>
            <?php else : ?>
                <?php sprintf(_e('Pay with %s', 'sln'), $paymentMethod->getMethodLabel()) ?>
            <?php endif ?>"
    data-email="<?php echo $booking->getEmail() ?>"
    data-currency="<?php echo $plugin->getSettings()->getCurrency() ?>"
    data-locale="auto">
  </script>
</form>
<?php endif ?>
        <?php endif; ?>
        <?php if($paymentMethod && $plugin->getSettings()->get('pay_cash')): ?>
        <?php _e('Or', 'sln') ?>
        <a  href="<?php echo $laterUrl ?>" class="btn btn-success"
            <?php if($ajaxEnabled): ?>
                data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
            <?php endif ?>>
            <?php _e('I\'ll pay later', 'sln') ?>
        </a>
        <?php elseif(!$paymentMethod) : ?>
        <a  href="<?php echo $laterUrl ?>" class="btn btn-success"
            <?php if($ajaxEnabled): ?>
                data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
            <?php endif ?>>
            <?php _e('Confirm', 'sln') ?>
        </a>
        <?php endif ?>
    </div>
<?php endif ?>
    <?php endif ?>
</div>
