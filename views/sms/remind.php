<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
echo 
__('Hi&nbsp;','salon-booking-system') . $booking->getFirstname() . ' ' . $booking->getLastname()

. __('&nbsp;don\'t forget your reservation at&nbsp;','salon-booking-system'). $plugin->getSettings()->getSalonName()
. __('&nbsp;on ','salon-booking-system') . $plugin->format()->date($booking->getDate()) 
. __('&nbsp;at ','salon-booking-system') . $plugin->format()->time($booking->getTime())
. __('&nbsp;|&nbsp;Booking ID ','salon-booking-system') .$booking->getId();