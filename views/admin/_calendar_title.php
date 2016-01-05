<?php echo $booking->getDisplayName()?>
 <?php echo __('on ','salon-booking-system'). $booking->getStartsAt()->format('d/m/Y h:i') ?>
 <?php if($attendant = $booking->getAttendant()) :  ?>
  <?php 

echo _e('assisted by ','salon-booking-system');
  echo $attendant->getName() 

  ?>
   <?php endif ?>
 (<?php echo implode(', ',$booking->getServices()) ?>)


