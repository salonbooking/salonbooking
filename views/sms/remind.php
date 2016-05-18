<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
echo 
__('Hi','salon-booking-system') .' ' . $booking->getFirstname() . ' ' . $booking->getLastname()

. ' ' . __('don\'t forget your reservation at','salon-booking-system').' '. $plugin->getSettings()->getSalonName()
. ' ' . __('on','salon-booking-system').' '. $plugin->format()->date($booking->getDate()) 
. ' ' . __('at','salon-booking-system').' '. $plugin->format()->time($booking->getTime())
. ' ' . __('| Booking ID ','salon-booking-system') .$booking->getId();