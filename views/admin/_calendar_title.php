<?php echo $booking->getDisplayName()?>
 <?php echo __('on ','sln'). $booking->getStartsAt()->format('d/m/Y h:i') ?>
 <?php if($attendant = $booking->getAttendant()) :  ?>
  <?php 

echo _e('assisted by ','sln');
  echo $attendant->getName() 

  ?>
   <?php endif ?>
 (<?php echo implode(', ',$booking->getServices()) ?>)


