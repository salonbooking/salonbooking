<?php

class SLN_Enum_BookingStatus
{
    const PENDING_PAYMENT = 'sln-b-pendingpayment';
    const PENDING = 'sln-b-pending';
    const ERROR = 'sln-b-error';
    const PAID = 'sln-b-paid';
    const PAY_LATER = 'sln-b-paylater';
    const CANCELED = 'sln-b-canceled';
    const CONFIRMED = 'sln-b-confirmed';

    private static $labels;

    private static $colors = array(
            self::PENDING_PAYMENT => 'warning',
            self::PENDING   => 'warning',
            self::PAID      => 'success',
            self::PAY_LATER => 'info',
            self::CANCELED  => 'danger',
            self::CONFIRMED => 'success',
            self::ERROR     => 'default',
    );

    // algolplus start
    private static $icons  = array(
	        self::PENDING_PAYMENT => 'glyphicon-clock',
	        self::PENDING   => 'glyphicon-clock',
	        self::PAID      => 'glyphicon-thumbs-up',
	        self::PAY_LATER => 'glyphicon-hourglass',
	        self::CANCELED  => 'glyphicon-ban-circle',
	        self::CONFIRMED => 'glyphicon-ok-sign',
	        self::ERROR     => 'glyphicon-warning-sign',
    );
    // algolplus end

    public static $noTimeStatuses = array(
        self::ERROR,
        self::CANCELED,
    );

    public static function toArray()
    {
        return self::$labels;
    }

    public static function getLabel($key)
    {
        return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::ERROR];
    }
    public static function getColor($key)
    {
        return isset(self::$colors[$key]) ? self::$colors[$key] : self::$colors[self::ERROR];
    }
    // algolplus start
    public static function getIcon($key)
    {
        return isset(self::$icons[$key]) ? self::$icons[$key] : self::$icons[self::ERROR];
    }
    // algolplus end


    public static function init()
    {
        self::$labels = array(
            self::PENDING_PAYMENT   => __('Pending payment', 'salon-booking-system'),
            self::PENDING   => __('Pending', 'salon-booking-system'),
            self::PAID      => __('Paid', 'salon-booking-system'),
            self::PAY_LATER => __('Pay later', 'salon-booking-system'),
            self::CANCELED  => __('Canceled', 'salon-booking-system'),
            self::CONFIRMED => __('Confirmed', 'salon-booking-system'),
            self::ERROR     => __('ERROR', 'salon-booking-system'),
        );
    }
}

SLN_Enum_BookingStatus::init();
