<?php
/** @var SLN_Wrapper_Booking $booking */
$format = SLN_Plugin::getInstance()->format();
?><strong><?php echo $booking->getDisplayName() . ' ' . $format->time($booking->getStartsAt()) . '&#8594;' . $format->time($booking->getEndsAt()) ?></strong>
<?php foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
    <br>
    <?php
    echo $bookingService->getService()->getName() . ': ' .
         $format->time($bookingService->getStartsAt()) . '&#8594;' .
         $format->time($bookingService->getEndsAt()) . ' - ' .
         $bookingService->getAttendant()->getName();

    ?>
<?php endforeach ?>
