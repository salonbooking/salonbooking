<strong><?php echo $booking->getDisplayName()?></strong>
 <?php echo ' ' . $booking->getStartsAt()->format('h:i') . '/' . $booking->getEndsAt()->format('h:i') . ' - ' . implode(', ',$booking->getServices()) ?>
 <?php if($attendant = $booking->getAttendant()) :  ?>
  <?php
  echo ' - ';
  echo _e('assisted by ','salon-booking-system');
  echo $attendant->getName() 

  ?>
   <?php endif ?>


