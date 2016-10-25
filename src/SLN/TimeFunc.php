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

    public static function evalPickedDate($date)
    {
        if (strpos($date, '-'))
            return $date;
        $initial = $date;
        $f = SLN_Plugin::getInstance()->getSettings()->getDateFormat();
        if ($f == SLN_Enum_DateFormat::_DEFAULT) {
            if(!strpos($date, ' ')) throw new Exception('bad date format');
            $date = explode(' ', $date);
            foreach (SLN_Func::getMonths() as $k => $v) {
                if ($date[1] == $v) {// || SLN_Func::removeAccents($date[1]) == SLN_Func::removeAccents($v)) {
                    $ret = $date[2] . '-' . ($k < 10 ? '0' . $k : $k) . '-' . $date[0];
                    return $ret;
                }
            }
        } elseif ($f == SLN_Enum_DateFormat::_SHORT) {
            $date = explode('/', $date);
            if (count($date) == 3)
                return sprintf('%04d-%02d-%02d', $date[2], $date[1], $date[0]);
            else
                throw new Exception('bad number of slashes');
        }elseif ($f == SLN_Enum_DateFormat::_SHORT_COMMA) {
            $date = explode('-', $date);
            if (count($date) == 3)
                return sprintf('%04d-%02d-%02d', $date[2], $date[1], $date[0]);
            else
                throw new Exception('bad number of commas');
        }else {
            return date('Y-m-d', strtotime($date));
        }
        throw new Exception('wrong date ' . $initial . ' format: ' . $f);
    }

    public static function evalPickedTime($val){
        if ($val instanceof DateTime) {
            $val = $val->format('H:i');
        }
        if (empty($val)) {
            return null;
        }
        if (strpos($val, ':') === false) {
            $val .= ':00';
        }

        return date('H:i', strtotime('1970-01-01 ' . $val)); 
    }
}
