    <?php _e('To confirm your booking click on the button and complete the payment', 'sln'); ?>
    <a href="<?php echo $booking->getPayUrl()?>">Pay 
    <?php if($booking->getDeposit()): ?>
        Deposit <?php echo $plugin->format()->money($booking->getDeposit()) ?>
    <?php else: ?>
        <?php echo $plugin->format()->money($booking->getAmount()) ?>
    <?php endif ?>
    </a>

