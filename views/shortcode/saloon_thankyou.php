<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 */

?>
<div id="saloon-step-thankyou">
    <h2><?php _e('Booking Confirmation', 'sln') ?></h2>
    <?php if (isset($paypalOp) && $paypalOp == 'cancel'): ?>
        <div class="alert alert-danger">
            <p><?php _e('The payment on paypal is failed, please try again.', 'sln') ?></p>
        </div>

    <?php else: ?>
        <div class="row">
            <div class="col-md-6 tycol"><?php _e('Booking confirmed', 'sln') ?><br/>
                            <i class="glyphicon glyphicon-ok-circle"></i>
            </div>
            <div class="col-md-6 tycol"><?php _e('Booking number', 'sln') ?>
                <span class="num"><?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></span>
            </div>
        </div>
        <p class="ty"><?php _e('If you need to change your reservation please call the ', 'sln') ?>
            <?php echo $plugin->getSettings()->get('phone') ?></p>
        <p class="ty">
            <?php _e(
                'In case of delay of arrival. we will wait a maximum of 10 minutes from booking time. Then we will release your reservation',
                'sln'
            ) ?>
        </p>
    <?php endif ?>
    <div class="row form-actions aligncenter">
        <a href="<?php echo $paypalUrl ?>" class="btn btn-primary">
            <?php _e('Pay with Paypal', 'sln') ?>
        </a>
        <?php _e('Or', 'sln') ?>
        <a href="<?php echo $laterUrl ?>" class="btn btn-success">
            <?php _e('I\'ll pay later', 'sln') ?>
        </a>
    </div>
</div>