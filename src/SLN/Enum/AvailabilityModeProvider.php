<?php

class SLN_Enum_AvailabilityModeProvider
{

    private static $labels;

    private static $classes;

    public static function toArray()
    {
        return self::$labels;
    }

    public static function getLabel($key)
    {
        if (isset(self::$labels[$key])) {
            throw new Exception('label not found');
        }

        return self::$labels[$key];
    }

    /**
     * @param $key
     * @param DateTime $date
     * @param SLN_Wrapper_Booking $booking
     *
     * @return SLN_Helper_Availability_AbstractDayBookings
     * @throws Exception
     */
    public static function getService($key, DateTime $date, SLN_Wrapper_Booking $booking = null)
    {
        $name = self::getServiceName($key);

        return new $name($date, $booking);
    }

    public static function getServiceName($key)
    {
        if (!isset(self::$classes[$key])) {
            throw new Exception(sprintf('provider "%s" not found', $key));
        }

        return self::$classes[$key];
    }

    public static function init()
    {
        self::$labels = array(
            'basic' => __('Basic (checks only the booking date)', 'salon-booking-system'),
            'advanced' => __('Advanced (evaluates also booking duration)', 'salon-booking-system'),
            'highend' => __('High end (evaluates also service duration and priority)', 'salon-booking-system'),
        );
        self::$classes = array(
            'basic' => 'SLN_Helper_Availability_Basic_DayBookings',
            'advanced' => 'SLN_Helper_Availability_Advanced_DayBookings',
            'highend' => 'SLN_Helper_Availability_Highend_DayBookings',
        );
    }
}

SLN_Enum_AvailabilityModeProvider::init();
