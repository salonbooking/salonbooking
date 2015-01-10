<?php

class SLN_Enum_BookingStatus
{
    const ERROR     = 'sln-b-error';
    const PENDING   = 'sln-b-pending';
    const PAID      = 'sln-b-paid';
    const PAY_LATER = 'sln-b-paylater';
    const CANCELED  = 'sln-b-canceled';
    const CONFIRMED = 'sln-b-confirmed';

    private static $labels;
    public static function toArray()
    {
        return self::$labels;
    }

    public static function getLabel($key)
    {
        return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::ERROR];
    }

    public static function init(){
        self::$labels = array(
            self::ERROR     => __('ERROR','sln'),
            self::PENDING   => __('Pending','sln'),
            self::PAID      => __('Paid','sln'),
            self::PAY_LATER => __('Pay later','sln'),
            self::CANCELED  => __('Canceled','sln'),
            self::CONFIRMED => __('Confirmed','sln')
        );
    }
}
SLN_Enum_BookingStatus::init();
