<?php
/**
 * @var SLN_Plugin                $plugin
 * @var SLN_Wrapper_Booking       $booking
 */
$data['to'] = $booking->getEmail();
$data['subject'] = sprintf(__('Payment for booking #%s has been confirmed','salon-booking-system'),$booking->getId());
include dirname(__FILE__).'/_header.php';
?>
<p ><?php _e('Dear', 'salon-booking-system') ?>
    <strong><?php echo esc_attr($booking->getFirstname()) . ' ' . esc_attr($booking->getLastname()); ?></strong>
    <br/>
    <?php _e('We received the payment for your booking.', 'salon-booking-system') ?>
</p>
<?php
include dirname(__FILE__).'/_footer.php';
?>
