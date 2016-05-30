<?php
/** @var SLN_Wrapper_Booking $booking */
$format = SLN_Plugin::getInstance()->format();
?><strong><?php echo $booking->getDisplayName() . '<br /> ' . $format->time($booking->getStartsAt()) . ' &#8594; ' . $format->time($booking->getEndsAt()) ?><br /></strong>

<?php foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
    <br>
    <?php
    echo $bookingService->getService()->getName() .'<br /><span>'. 
         ($bookingService->getAttendant() ? $bookingService->getAttendant()->getName() : '').'&nbsp;'.
         $format->time($bookingService->getStartsAt()) . ' &#8594; ' .
         $format->time($bookingService->getEndsAt()).'<br /></span>';
         

    ?>
<?php endforeach ?>



