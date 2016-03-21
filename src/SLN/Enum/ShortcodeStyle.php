<?php

class SLN_Enum_ShortcodeStyle
{
    const _SMALL = 'small';
    const _MEDIUM = 'medium';
    const _LARGE = 'large';
    const _DEFAULT = self::_MEDIUM;

    private static $labels;
    private static $descriptions;
    private static $images = array();

    public static function toArray()
    {
        self::init();

        return self::$labels;
    }

    public static function getLabel($key)
    {
        self::init();

        return self::$labels[$key];
    }

    public static function getDescription($key)
    {
        self::init();

        return self::$descriptions[$key];
    }

    public static function getImage($key)
    {
        self::init();

        return self::$images[$key];
    }

    public static function init()
    {
        if (self::$labels) {
            return;
        }
        self::$descriptions = array(
            self::_SMALL => __('Lorem ipsum', 'salon-booking-system'),
            self::_MEDIUM => __('Medium Lorem ipsum', 'salon-booking-system'),
            self::_LARGE => __('Large Lorem ipsum', 'salon-booking-system'),
        );
        self::$labels = array(
            self::_SMALL => __('Small', 'salon-booking-system'),
            self::_MEDIUM => __('Medium', 'salon-booking-system'),
            self::_LARGE => __('Large', 'salon-booking-system'),
        );
        foreach (self::$labels as $k => $v) {
            self::$images[$k] = SLN_PLUGIN_URL.'/img/shortcode_style/'.$k.'.png';
        }
    }
}