<?php

class SLN_TimeFunc
{
    public static function startRealTimezone()
    {
        if ($timezone = get_option('timezone_string')) {
            date_default_timezone_set($timezone);
        }
        return $timezone;
    }

    public static function endRealTimezone()
    {
        if ($timezone = get_option('timezone_string')) {
            date_default_timezone_set('UTC');
        }
        return $timezone;
    }
}
