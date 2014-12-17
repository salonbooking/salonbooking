<?php

class SLN_Enum_BookingStatus
{
    const ERROR = 'error';
    const PENDING = 'pending';
    const PAID = 'paid';
    const PAY_LATER = 'paylater';
    const CANCELED = 'canceled';

    private static $labels = array(
        self::ERROR    => 'ERROR',
        self::PENDING  => 'Pending',
        self::PAID     => 'Paid',
        self::PAID     => 'Pay later',
        self::CANCELED => 'Canceled'
    );

    public static function toArray()
    {
        return self::$labels;
    }

    public static function getLabel($key)
    {
        return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::ERROR];
    }
}