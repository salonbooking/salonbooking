        <a data-salon-data="<?php echo $ajaxData.'&mode='.$paymentMethod->getMethodKey() ?>" data-salon-toggle="direct"
        href="<?php echo $payUrl ?>" class="btn btn-primary">
            <?php $deposit = $plugin->getBookingBuilder()->getLastBooking()->getDeposit(); ?> 
            <?php if($deposit > 0): ?>
                <?php echo sprintf(__('Pay %s as a deposit with %s', 'salon-booking-system'), $plugin->format()->money($deposit), $paymentMethod->getMethodLabel()) ?>
            <?php else : ?>
                <?php echo sprintf(__('Pay with %s', 'salon-booking-system'), $paymentMethod->getMethodLabel()) ?>
            <?php endif ?>
        </a>
