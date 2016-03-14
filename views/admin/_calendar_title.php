<?php 
$format = SLN_Plugin::getInstance()->format();
?><strong><?php echo $booking->getDisplayName()?></strong>
<?php echo ' ' . $format->time($booking->getStartsAt()) . '&#8594;' . $format->time($booking->getEndsAt()) . ' - ' . implode(', ',$booking->getServices()) ?>
<?php foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
    <?php echo $bookingService->getService()->getName(); ?>
    (<?php echo $format->time($bookingService->getStartsAt()) ?>
    <?php echo $bookingService->getAttendant()->getName(); ?>)
<?php endforeach ?>
 <?php /* if($attendant = $booking->getAttendant()) :  ?>
  <?php
  echo ' - ';
  echo _e('assisted by ','salon-booking-system');
  echo $attendant->getName() 

  ?>
   <?php endif */ ?>
