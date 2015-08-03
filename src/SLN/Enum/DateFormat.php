<?php

class SLN_Enum_DateFormat
{
    const _DEFAULT = 'default';
    const _SHORT   = 'short';
    private static $labels = array();
    private static $phpFormats = array(
        self::_DEFAULT => 'd M Y',
        self::_SHORT => 'd/m/Y',
    );
    private static $jsFormats = array(
        self::_DEFAULT => 'd M yyyy',
        self::_SHORT => 'd/m/yyyy'
    );

    public static function toArray()
    {
        return self::$labels;
    }

    public static function getLabel($key)
    {
        return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::_DEFAULT];
    }
    public static function getPhpFormat($key)
    {
        return isset(self::$phpFormats[$key]) ? self::$phpFormats[$key] : self::$phpFormats[self::_DEFAULT];
    }
    public static function getJsFormat($key)
    {
        return isset(self::$jsFormats[$key]) ? self::$jsFormats[$key] : self::$jsFormats[self::_DEFAULT];
    }

    public static function init()
    {
        $d = new \DateTime;
        $d = $d->format('U');
        foreach(self::$phpFormats as $k => $v){
            self::$labels[$k] = date_i18n($v,$d); 
        }
    }
}

SLN_Enum_DateFormat::init();
