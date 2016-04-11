<?php

class SLN_Func
{

    public static function getDays()
    {
        $timestamp = strtotime('next Sunday');
        $ret = array();
        for ($i = 1; $i <= 7; $i++) {
            $ret[$i] = self::getDateDayName($timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        return $ret;
    }

    public static function getDateDayName($day)
    {
        if ($day instanceof DateTime) {
            $day = $day->format('U');
        }

        return date_i18n('l', $day);
    }

    public static function countDaysBetweenDatetimes(DateTime $from, DateTime $to)
    {
        $datediff = abs($from->format('U') - $to->format('U'));

        return floor($datediff / (60 * 60 * 24));
    }

    public static function getMonths()
    {
        $timestamp = strtotime("1970-01-01");
        $ret = array();
        for ($i = 1; $i <= 12; $i++) {
            $ret[$i] = date_i18n('M', $timestamp);
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
            return intval($val);
        } elseif ($filter == 'money') {
            return number_format(floatval(str_replace(',', '.', $val)), 2);
        } elseif ($filter == 'float') {
            return floatval(str_replace(',', '.', $val));
        } elseif ($filter == 'time') {
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
        } elseif ($filter == 'date') {
            if (is_array($val)) {
                $val = $val['year'] . '-' . $val['month'] . '-' . $val['day'];
            } elseif (strpos($val, ' ') !== false) {
                $val = self::evalPickedDate($val);
            } else {
                $val = self::evalPickedDate($val);
            }
            $ret = date('Y-m-d', strtotime($val));
            if ($ret == '1970-01-01')
                throw new Exception(sprintf('wrong date %s', $val));
            return $ret;
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
    public static function removeAccents($string) {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), ' '));
    }

    public static function evalPickedDate($date)
    {
        if (strpos($date, '-'))
            return $date;
        $initial = $date;
        $f = SLN_Plugin::getInstance()->getSettings()->get('date_format');
        if ($f == SLN_Enum_DateFormat::_DEFAULT) {
            if(!strpos($date, ' ')) throw new Exception('bad date format');
            $date = explode(' ', $date);
            foreach (SLN_Func::getMonths() as $k => $v) {
//                var_dump([$date[1], $v, self::removeAccents($date[1]) == self::removeAccents($v)]);
//                if (strcasecmp($date[1], $v) == 0) {
                if (self::removeAccents($date[1]) == self::removeAccents($v)) {
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

    static function addUrlParam($url, $k, $v)
    {
        return $url . (strpos($url, '?') === false ? '?' : '&') . http_build_query(array($k => $v));
    }

    static function currPageUrl()
    {
        $pageURL = 'http';
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
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

    public static function getIntervalItemsShort()
    {
        return array(
//            '+10 minutes' => '10 '.__('minutes', 'salon-booking-system'),
//            '+20 minutes' => '20 '.__('minutes', 'salon-booking-system'),
//            '+30 minutes' => '30 '.__('minutes', 'salon-booking-system'),
//            '+45 minutes' => '45 '.__('minutes', 'salon-booking-system'),
            '+1 hour' => '1 ' . __('hour', 'salon-booking-system'),
            '+2 hours' => '2 ' . __('hours', 'salon-booking-system'),
            '+3 hours' => '3 ' . __('hours', 'salon-booking-system'),
            '+4 hours' => '4 ' . __('hours', 'salon-booking-system'),
            '+6 hours' => '6 ' . __('hours', 'salon-booking-system'),
            '+12 hours' => '12 ' . __('hours', 'salon-booking-system'),
            '+24 hours' => '24 ' . __('hours', 'salon-booking-system'),
            '+48 hours' => '48 ' . __('hours', 'salon-booking-system'),
        );
    }

    public static function getIntervalItems()
    {
        return array(
            '' => __('Always', 'salon-booking-system'),
            '+30 minutes' => __('half hour', 'salon-booking-system'),
            '+1 hour' => '1 ' . __('hour', 'salon-booking-system'),
            '+2 hours' => '2 ' . __('hours', 'salon-booking-system'),
            '+3 hours' => '3 ' . __('hours', 'salon-booking-system'),
            '+4 hours' => '4 ' . __('hours', 'salon-booking-system'),
            '+8 hours' => '8 ' . __('hours', 'salon-booking-system'),
            '+16 hours' => '16 ' . __('hours', 'salon-booking-system'),
            '+1 day' => '1 ' . __('day', 'salon-booking-system'),
            '+2 days' => '2 ' . __('days', 'salon-booking-system'),
            '+3 days' => '3 ' . __('days', 'salon-booking-system'),
            '+4 days' => '4 ' . __('days', 'salon-booking-system'),
            '+1 week' => '1 ' . __('week', 'salon-booking-system'),
            '+2 weeks' => '2 ' . __('weeks', 'salon-booking-system'),
            '+3 weeks' => '3 ' . __('weeks', 'salon-booking-system'),
            '+1 month' => '1 ' . __('month', 'salon-booking-system'),
            '+2 months' => '2 ' . __('months', 'salon-booking-system'),
            '+3 months' => '3 ' . __('months', 'salon-booking-system')
        );

        return array(
            '' => 'Always',
            'PT30M' => 'half hour',
            'PT1H' => '1 hour',
            'PT2H' => '2 hours',
            'PT3H' => '3 hours',
            'PT4H' => '4 hours',
            'PT8H' => '8 hours',
            'PT16H' => '16 hours',
            'P1D' => '1 day',
            'P2D' => '2 days',
            'P3D' => '3 days',
            'P4D' => '4 days',
            'P1W' => '1 week',
            'P2W' => '2 weeks',
            'P3W' => '3 weeks',
            'P1M' => '1 month',
            'P2M' => '2 months',
            'P3M' => '3 months'
        );
    }

    public static function getMinutesIntervals($interval = null, $maxItems = null)
    {
        $start = "00:00";

        $curr = strtotime($start);
        $interval = isset($interval) ?
            $interval :
            SLN_Plugin::getInstance()->getSettings()->getInterval();
        $maxItems = isset($maxItems) ?
            $maxItems : (24*60/$interval); // as default it is 24 hours
        $items = array();
        do {
            $items[] = date("H:i", $curr);
            $curr = strtotime('+' . $interval . ' minutes', $curr);
            $maxItems--;
        } while (date("H:i", $curr) != $start && $maxItems > 0);
        return $items;
    }

	/**
     * @param $times
     * @param $startDate
     * @param $endDate
     *
     * @return SLN_DateTime[]
     */
    public static function filterTimes($times, DateTime $startDate, DateTime $endDate){
        $ret = array();
        foreach($times as $t){
            $t = new SLN_DateTime($startDate->format('Y-m-d').' '.$t);
            if($t->format('YmdHi') >= $startDate->format('YmdHi') && $t->format('YmdHi') < $endDate->format('YmdHi') || $t->format('YmdHi') == $startDate->format('YmdHi') ){
//                SLN_Plugin::addLog(__CLASS__.'->'.__METHOD__.' '.$t->format('YmdHi'));
                $ret[] = $t;
            }
        }
        return $ret;
    }

    public static function getMinutesFromDuration($duration)
    {
        if ($duration instanceof DateTime) {
            $duration = $duration->format('H:i');
        }

        if (is_string($duration)) {
            $tmp = explode(':', $duration);
            return ($tmp[0] * 60) + $tmp[1];
        } else {
            return 0;
        }
    }

    public static function convertToHoursMins($time, $format = '%02d:%02d')
    {
        settype($time, 'integer');
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function groupServicesByCategory($services)
    {
        global $wpdb;
        $ret = array(0 => array('term' => false, 'services' => array()));
        $order = get_option(SLN_Plugin::CATEGORY_ORDER, '""');
        $sql = "SELECT * FROM {$wpdb->term_taxonomy} tt, {$wpdb->terms} t WHERE tt.term_id = t.term_id AND tt.taxonomy = '" . SLN_Plugin::TAXONOMY_SERVICE_CATEGORY . "' ORDER BY FIELD(t.term_id, $order)";
        $categories = $wpdb->get_results($sql);
        foreach ($categories as $cat) {
            foreach ($services as $s) {
                $post_terms = get_the_terms($s->getId(), SLN_Plugin::TAXONOMY_SERVICE_CATEGORY);
                
                if (!empty($post_terms)) {
                    foreach ($post_terms as $post_term) {
                        if ($post_term->term_id == $cat->term_id) {
                            $ret[$post_term->term_id]['term'] = $post_term;
                            $ret[$post_term->term_id]['services'][] = $s;
                        }
                    }
                } else {
                    $ret[0]['services'][$s->getId()] = $s;
                }
            }
        }
        if (empty($categories)) {
            $ret[0]['services'] = $services;
        }
        if (empty($ret['0']['services'])) {
            unset($ret['0']);
        }
        return $ret;
    }

    /**
     * @param string       $k
     * @param string|array $data
     * @return bool
     */
    public static function has($k, $data)
    {
        if (!is_array($data)) {
            return strcmp($k, $data) == 0;
        } else {
            foreach ($data as $p) {
                if (strcmp($k, $p) == 0) {
                    return true;
                }
            }

            return false;
        }
    }

}
