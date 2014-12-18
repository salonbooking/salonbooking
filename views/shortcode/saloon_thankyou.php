<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 */

?>
<h2><?php _e('Booking Confirmed','sln')?> #<?php echo $plugin->getBookingBuilder()->getLastBooking()->getId() ?></h2>
<?php if (isset($paypalOp) && $paypalOp == 'cancel'): ?>
    <div class="alert alert-danger">
        <p><?php _e('The payment on paypal is failed, please try again.', 'sln') ?></p>
    </div>
<?php else:  ?>
    <p><?php _e('Check your email for the booking details', 'sln') ?></p>
<?php endif ?>
<a href="<?php echo $paypalUrl ?>" class="btn btn-block btn-success">
    <?php _e('Pay with Paypal', 'sln') ?>
</a>
<p style="text-align: center font-size: 12px; font-weight: bold">or</p>
<a href="<?php echo $laterUrl ?>" class="btn btn-block btn-primary">
    <?php _e('I\'ll pay later', 'sln') ?>
</a>