<div class="col-md-8">
    <?php if($confirmation) : ?>
        <h2 class="salon-step-title"><?php _e('Booking status', 'salon-booking-system') ?></h2>
    <?php else : ?>
        <?php
        $args = array(
            'label'        => __('Booking Confirmation', 'salon-booking-system'),
            'tag'          => 'h2',
            'textClasses'  => 'salon-step-title',
            'inputClasses' => '',
            'tagClasses'   => 'salon-step-title',
        );
        echo $plugin->loadView('shortcode/_editable_snippet', $args);
        ?>
    <?php endif ?>

    <?php include '_errors.php'; ?>

    <?php if (isset($payOp) && $payOp == 'cancel'): ?>

        <div class="alert alert-danger">
            <p><?php _e('The payment is failed, please try again.', 'salon-booking-system') ?></p>
        </div>

    <?php else: ?>
        <div class="row sln-thankyou--okbox <?php if($confirmation): ?> sln-bkg--attention<?php else : ?> sln-bkg--ok<?php endif ?>">
            <div class="col-md-12">
                <h1 class="sln-icon-wrapper"><?php echo $confirmation ? __('Your booking is pending', 'salon-booking-system') : ($pendingPayment ? __('PLEASE COMPLETE PAYMENT', 'salon-booking-system') : __('Your booking is completed', 'salon-booking-system')) ?>
                    <?php if($confirmation): ?>
                        <i class="sln-icon sln-icon--time"></i>
                    <?php else : ?>
                        <i class="sln-icon sln-icon--checked--square"></i>
                    <?php endif ?>
                </h1>
            </div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-12">
                <h2 class="salon-step-title"><?php _e('Booking number', 'salon-booking-system') ?></h2>
                <h3><?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></h3>
            </div>
        </div>
        <?php $ppl = false; ?>
        <?php include '_salon_thankyou_alert.php' ?>
    <?php endif ?>
    <?php if($paymentMethod): ?>
        <div class="sln-btn sln-btn--emphasis sln-btn--noheight sln-btn--fullwidth">
            <?php echo $paymentMethod->renderPayButton(compact('booking', 'paymentMethod', 'ajaxData', 'payUrl')); ?>
        </div>
    <?php endif; ?>
</div>
<div class="col-md-4 sln-form-actions-wrapper sln-input--action">
    <div class="sln-form-actions sln-payment-actions row">
        <?php if($paymentMethod && $payLater): ?>
            <div class="col-sm-10 col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--noheight sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('I\'ll pay later', 'salon-booking-system') ?>
                </a>
            </div>
            <div class="col-sm-2 col-md-4 pull-right">
                <h4><?php _e('Or', 'salon-booking-system') ?></h4>
            </div>
        <?php elseif(!$paymentMethod) : ?>
            <div class="col-sm-6 col-md-8 pull-right">
                <a  href="<?php echo $laterUrl ?>" class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth"
                    <?php if($ajaxEnabled): ?>
                        data-salon-data="<?php echo $ajaxData.'&mode=later' ?>" data-salon-toggle="direct"
                    <?php endif ?>>
                    <?php _e('Complete', 'salon-booking-system') ?>
                </a>
            </div>
        <?php endif ?>
    </div>
</div>