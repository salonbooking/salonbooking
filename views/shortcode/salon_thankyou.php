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
<div id="salon-step-thankyou" class="row sln-thankyou">
<?php
    $size = $_SESSION["size"];
    if ($size == '900') { ?>
    <div class="col-md-8">
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
    <div class="row sln-thankyou--okbox">
        <div class="col-md-12">
        <h1 class="sln-icon-wrapper"><?php echo $confirmation ? __('Your booking is pending', 'salon-booking-system') : __('Your booking is confirmed', 'salon-booking-system') ?>
            <?php if($confirmation): ?> 
                <i class="c glyphicon glyphicon-time"></i>
            <?php else : ?>
                <i class="sln-icon sln-icon--checked--square"></i>
            <?php endif ?>
        </h1>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="col-md-12">
        <h2><?php _e('Booking number', 'salon-booking-system') ?></h2>
        <h3><?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></h3>
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
    <!-- form actions -->
<?php endif ?>
<?php endif ?>
    </div>
    <div class="col-md-4 sln-form-actions-wrapper sln-input--action">
        <?php if($paymentMethod): ?>
        <div class="sln-form-actions sln-payment-actions row">
            <div class="col-md-12">
                <div class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth">
                    <?php echo $paymentMethod->renderPayButton(compact('booking', 'paymentMethod', 'ajaxData', 'payUrl')); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if($paymentMethod && $plugin->getSettings()->get('pay_cash')): ?>
        <div class="sln-form-actions sln-payment-actions row">
            <div class="col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('I\'ll pay later', 'salon-booking-system') ?>
                </a>
            </div>
            <div class="col-md-4 pull-right">
                <h4><?php _e('Or', 'salon-booking-system') ?></h4>
            </div>
        </div>
        <?php elseif(!$paymentMethod) : ?>
        <div class="form-actions row">
            <div class="col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('Confirm', 'salon-booking-system') ?>
                </a>
            </div>
        </div>
        <?php endif ?>
    </div>
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
    <div class="col-md-12">
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
    <div class="row sln-thankyou--okbox">
        <div class="col-md-12">
        <h1 class="sln-icon-wrapper"><?php echo $confirmation ? __('Your booking is pending', 'salon-booking-system') : __('Your booking is confirmed', 'salon-booking-system') ?>
            <?php if($confirmation): ?> 
                <i class="c glyphicon glyphicon-time"></i>
            <?php else : ?>
                <i class="sln-icon sln-icon--checked--square"></i>
            <?php endif ?>
        </h1>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="col-md-12">
        <h2><?php _e('Booking number', 'salon-booking-system') ?></h2>
        <h3><?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></h3>
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
    <!-- form actions -->
<?php endif ?>
<?php endif ?>
    </div>
    <div class="col-md-12 sln-form-actions-wrapper sln-input--action">
        <div class="sln-form-actions sln-payment-actions row">
        <?php if($paymentMethod): ?>
            <div class="col-md-6">
                <div class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth">
                    <?php echo $paymentMethod->renderPayButton(compact('booking', 'paymentMethod', 'ajaxData', 'payUrl')); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($paymentMethod && $plugin->getSettings()->get('pay_cash')): ?>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-8 pull-right">
                        <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                            <?php if($ajaxEnabled): ?>
                                data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                            <?php endif ?>>
                            <?php _e('I\'ll pay later', 'salon-booking-system') ?>
                        </a>
                    </div>
                    <div class="col-md-4 pull-right">
                        <h4><?php _e('Or', 'salon-booking-system') ?></h4>
                    </div>
                </div>
            </div>
        <?php elseif(!$paymentMethod) : ?>
        <div class="form-actions row">
            <div class="col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('Confirm', 'salon-booking-system') ?>
                </a>
            </div>
        <?php endif ?>
        </div>
    </div>
    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
    <div class="col-md-12">
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
    <div class="row sln-thankyou--okbox">
        <div class="col-md-12">
        <h1 class="sln-icon-wrapper"><?php echo $confirmation ? __('Your booking is pending', 'salon-booking-system') : __('Your booking is confirmed', 'salon-booking-system') ?>
            <?php if($confirmation): ?> 
                <i class="c glyphicon glyphicon-time"></i>
            <?php else : ?>
                <i class="sln-icon sln-icon--checked--square"></i>
            <?php endif ?>
        </h1>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="col-md-12">
        <h2><?php _e('Booking number', 'salon-booking-system') ?></h2>
        <h3><?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></h3>
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
    <!-- form actions -->
<?php endif ?>
<?php endif ?>
    </div>
    <div class="col-md-12 sln-form-actions-wrapper sln-input--action">
        <?php if($paymentMethod): ?>
        <div class="sln-form-actions sln-payment-actions row">
            <div class="col-md-12">
                <div class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth">
                    <?php echo $paymentMethod->renderPayButton(compact('booking', 'paymentMethod', 'ajaxData', 'payUrl')); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if($paymentMethod && $plugin->getSettings()->get('pay_cash')): ?>
        <div class="sln-form-actions sln-payment-actions row">
            <div class="col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('I\'ll pay later', 'salon-booking-system') ?>
                </a>
            </div>
            <div class="col-md-4 pull-right">
                <h4><?php _e('Or', 'salon-booking-system') ?></h4>
            </div>
        </div>
        <?php elseif(!$paymentMethod) : ?>
        <div class="form-actions row">
            <div class="col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('Confirm', 'salon-booking-system') ?>
                </a>
            </div>
        </div>
        <?php endif ?>
    </div>
    <?php
    // IF SIZE 400 // END
    } else  { ?>

    <?php
    // ELSE // END
    }  ?>

</div>
