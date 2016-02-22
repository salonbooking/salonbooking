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
        <h2><?php _e('Booking status', 'salon-booking-system') ?></h2>
    <?php else : ?> 
        <h2><?php _e('Booking Confirmation', 'salon-booking-system') ?></h2>
    <?php endif ?>

    <?php include '_errors.php'; ?>

    <?php if (isset($payOp) && $payOp == 'cancel'): ?>

        <div class="alert alert-danger">
            <p><?php _e('The payment is failed, please try again.', 'salon-booking-system') ?></p>
        </div>

    <?php else: ?>
        <div class="row">
            <div class="col-md-6 tycol"><?php echo $confirmation ? __('Your booking is pending', 'salon-booking-system') : __('Your booking is confirmed', 'salon-booking-system') ?><br/>
                <?php if($confirmation): ?> 
                    <i class="c glyphicon glyphicon-time"></i>
                <?php else : ?>
                    <i class="glyphicon glyphicon-ok-circle"></i>
                <?php endif ?>
            </div>
            <div class="col-md-6 tycol"><?php _e('Booking number', 'salon-booking-system') ?>
                <br/><span class="num"><?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></span>
            </div>
        </div>
<?php $ppl = false; ?>
<div class="alert ty">
<?php if($confirmation) : ?>
        <p><strong><?php _e('You will receive a confirmation of your booking by email.','salon-booking-system' )?></strong></p>
        <p><?php echo sprintf(__('If you don\'t receive any news from us or you need to change your reservation please call the %s or send an e-mail to %s', 'salon-booking-system'), $plugin->getSettings()->get('gen_phone'),  $plugin->getSettings()->get('gen_email') ? $plugin->getSettings()->get('gen_email') : get_option('admin_email') ); ?>
            <?php echo $plugin->getSettings()->get('gen_phone') ?></p>
        <p class="aligncenter"><a href="<?php echo $confirmUrl ?>"
             data-salon-toggle="direct" data-salon-data="<?php echo $ajaxData.'&mode=confirm' ?>"
             class="btn btn-primary"><?php _e('Complete','salon-booking-system') ?></a></p>
<?php /*
        <p class="aligncenter"><a href="<?php echo home_url() ?>" class="btn btn-primary"><?php _e('Back to home','salon-booking-system') ?></a></p>
*/ ?> 
<?php else : ?>
        <p><?php echo sprintf(__('If you need to change your reservation please call the <strong>%s</strong> or send an e-mail to <strong>%s</strong>', 'salon-booking-system'), $plugin->getSettings()->get('gen_phone'),  $plugin->getSettings()->get('gen_email') ? $plugin->getSettings()->get('gen_email') : get_option('admin_email') ); ?>
            <?php echo $plugin->getSettings()->get('phone') ?>
        </p>
        </div>

            <div id="sln-notifications"></div>
    <div class="row form-actions aligncenter">
        <?php if($paymentMethod): ?>
            <?php echo $paymentMethod->renderPayButton(compact('booking', 'paymentMethod', 'ajaxData', 'payUrl')); ?>
        <?php endif; ?>
        <?php if($paymentMethod && $plugin->getSettings()->get('pay_cash')): ?>
        <?php _e('Or', 'salon-booking-system') ?>
        <a  href="<?php echo $laterUrl ?>" class="btn btn-success"
            <?php if($ajaxEnabled): ?>
                data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
            <?php endif ?>>
            <?php _e('I\'ll pay later', 'salon-booking-system') ?>
        </a>
        <?php elseif(!$paymentMethod) : ?>
        <a  href="<?php echo $laterUrl ?>" class="btn btn-success"
            <?php if($ajaxEnabled): ?>
                data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
            <?php endif ?>>
            <?php _e('Confirm', 'salon-booking-system') ?>
        </a>
        <?php endif ?>
    </div>
<?php endif ?>
    <?php endif ?>
</div>
