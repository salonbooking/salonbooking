<?php

class SLN_Enum_ShortcodeStyle
{
    const _SMALL = 'small';
    const _MEDIUM = 'medium';
    const _LARGE = 'large';
    const _DEFAULT = self::_MEDIUM;

    private static $labels;
    private static $classes;
    private static $sizes;
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

    public static function getClass($key)
    {
        self::init();

        return self::$classes[$key];
    }

    public static function getSize($key)
    {
        self::init();

        return self::$sizes[$key];
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
            self::_SMALL => __('Use this if your column is at least 400px width', 'salon-booking-system'),
            self::_MEDIUM => __('Use this if your column is at least 600px width', 'salon-booking-system'),
            self::_LARGE => __('Use this if your column is at least 900px width', 'salon-booking-system'),
        );
        self::$labels = array(
            self::_SMALL => __('Small', 'salon-booking-system'),
            self::_MEDIUM => __('Medium', 'salon-booking-system'),
            self::_LARGE => __('Large', 'salon-booking-system'),
        );
        self::$classes = array(
            self::_SMALL => 'sln-salon--s',
            self::_MEDIUM => 'sln-salon--m',
            self::_LARGE => 'sln-salon--l',
        );
        self::$sizes = array(
            self::_SMALL => 400,
            self::_MEDIUM => 600,
            self::_LARGE => 900,
        );
        foreach (self::$labels as $k => $v) {
            self::$images[$k] = SLN_PLUGIN_URL.'/img/shortcode_style/'.$k.'.png';
        }
    }
}
