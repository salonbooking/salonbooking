<?php

class SLN_Func
{
    public static function getDays()
    {
        $timestamp = strtotime('next Sunday');
        $ret       = array();
        for ($i = 1; $i <= 7; $i++) {
            $ret[$i]   = strftime('%A', $timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        return $ret;
    }

    public static function getMonths()
    {
        $timestamp = strtotime("1970-01-01");
        $ret       = array();
        for ($i = 1; $i <= 12; $i++) {
            $ret[$i]   = strftime('%B', $timestamp);
            $timestamp = strtotime('+1 month', $timestamp);
        }

        return $ret;
    }

    public static function getYears($min = null, $max = null)
    {
        if (!isset($min)) {
            $min = date('Y') - 1;
        }
        if (!isset($max)) {
            $max = $min + 2;
        }
        $ret = array();
        for ($i = $min; $i <= $max || count($ret) > 10; $i++) {
            $ret[$i] = $i;
        }

        return $ret;
    }

    public static function filter($val, $filter = null)
    {
        if (empty($filter)) {
            return $val;
        }
        if ($filter == 'int') {
            return intval($filter);
        } elseif ($filter == 'money') {
            return number_format(floatval(str_replace(',', '.', $val)), 2);
        } elseif ($filter == 'float') {
            return floatval(str_replace(',', '.', $val));
        } elseif ($filter == 'time') {
            if ($val instanceof \DateTime) {
                $val = $val->format('H:i');
            }
            if (empty($val)) {
                return null;
            }
            if (strpos($val, ':') === false) {
                $val .= ':00';
            }

            return date('H:i', strtotime('1970-01-01 ' . $val));
        } elseif ($filter == 'date') {
            if (is_array($val)) {
                $val = $val['year'] . '-' . $val['month'] . '-' . $val['day'];
            }
            return date('Y-m-d', strtotime($val));
        } elseif ($filter == 'bool') {
            return $val ? true : false;
        } elseif ($filter == 'set') {
            $ret = array();
            if (!is_array($val)) {
                return $ret;
            }
            foreach ($val as $k => $v) {
                if ($v) {
                    $ret[] = $k;
                }
            }

            return $ret;
        } else {
            return $val;
        }
    }

    static function addUrlParam($url, $k, $v)
    {
        return $url . (strpos($url, '?') === false ? '?' : '&') . http_build_query(array($k => $v));
    }

    static function currPageUrl()
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }

}