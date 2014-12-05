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
            $max = $min + 1;
        }
        for ($i = $min; $i <= $max; $i++) {
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
            return date('Y-m-d', strtotime($val));
        } elseif ($filter == 'bool') {
            return $val ? true : false;
        } else {
            return $val;
        }
    }
}