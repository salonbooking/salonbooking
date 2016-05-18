<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
echo
    $booking->getFirstname() . ' ' . $booking->getLastname()
    . __('&nbsp;has booked at&nbsp;','salon-booking-system') . $plugin->getSettings()->getSalonName() . __('&nbsp;on&nbsp;','salon-booking-system')
    . ' ' . $plugin->format()->date($booking->getDate()) 
    . ' ' . $plugin->format()->time($booking->getTime())
    . ' - ' .$booking->getId();



