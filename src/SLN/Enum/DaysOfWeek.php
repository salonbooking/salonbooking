<?php

class SLN_Enum_DaysOfWeek
{
    const SUNDAY    = 0;
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;

    private static $labels;

    public static function toArray()
    {
        return self::$labels;
    }

    public static function getLabel($key)
    {
        return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::SUNDAY];
    }

    public static function init()
    {
        self::$labels = array(
            self::SUNDAY    => __('Sunday', 'salon-booking-system'),
            self::MONDAY    => __('Monday', 'salon-booking-system'),
            self::TUESDAY   => __('Tuesday', 'salon-booking-system'),
            self::WEDNESDAY => __('Wednesday', 'salon-booking-system'),
            self::THURSDAY  => __('Thursday', 'salon-booking-system'),
            self::FRIDAY    => __('Friday', 'salon-booking-system'),
            self::SATURDAY  => __('Saturday', 'salon-booking-system'),
        );
    }
}

SLN_Enum_DaysOfWeek::init();
