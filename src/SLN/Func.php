<?php

class SLN_Func
{
    private static $cachedFilterTimes = array();
    private static $cachedTimes = array();
    private static $cachedTs = array();
    private static $cachedDate;

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
        if( setlocale(LC_TIME,0) !== get_locale() ){ setlocale(LC_TIME, get_locale()); }
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
            return SLN_TimeFunc::evalPickedTime($val);
        } elseif ($filter == 'date') {
            if (is_array($val)) {
                $val = $val['year'] . '-' . $val['month'] . '-' . $val['day'];
//            } elseif (strpos($val, ' ') !== false) {
//                $val = SLN_TimeFunc::evalPickedDate($val);
            } else {
                $val = SLN_TimeFunc::evalPickedDate($val);
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
        if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != 443) {
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
            '+3 months' => '3 ' . __('months', 'salon-booking-system'),
            '+6 months' => '6 ' . __('months', 'salon-booking-system'),
            '+1 year' => '1 ' . __('years', 'salon-booking-system'),
            '+2 years' => '2 ' . __('years', 'salon-booking-system')
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
            'P3M' => '3 months',
            'P6M' => '6 months',
            'P12M' => '1 year',
            'P24M' => '2 years'
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
            $curr += (int)60*$interval;
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
        $startT = $startDate->format('U');
        $endT = $endDate->format('U');

        $date = $startDate->format('Y-m-d');
        if (self::$cachedDate !== $date) {
            self::$cachedDate  = $date;
            self::$cachedTimes = array();
            self::$cachedTs    = array();
            self::$cachedFilterTimes    = array();
        }

        // cache "self::$cachedFilterTimes" don't depend on $times arg!
        $cachedFilterTimesKey = md5($startDate->format('Y-m-d H:i').'#'.$endDate->format('Y-m-d H:i'));
        if (isset(self::$cachedFilterTimes[$cachedFilterTimesKey])) {
            return self::$cachedFilterTimes[$cachedFilterTimesKey];
        }

        foreach($times as $t){
            $key = $date.' '.$t;
            if (!isset(self::$cachedTimes[$key])) {
                self::$cachedTimes[$key] = new SLN_DateTime($key);
                self::$cachedTs[$key]    = self::$cachedTimes[$key]->format('U');
            }
            $tT = self::$cachedTs[$key];
            if($tT >= $startT && $tT < $endT || $tT == $startT ){
                $ret[] = self::$cachedTimes[$key];
            }
        }

        self::$cachedFilterTimes[$cachedFilterTimesKey] = $ret;

        return $ret;
    }

    public static function getMinutesFromDuration($duration)
    {
        if ($duration instanceof DateTime) {
            $duration = $duration->format('H:i');
        }

        if (is_string($duration) && !empty($duration)) {
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
            return '00:00';
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
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

    public static function isSalonPage()
    {
        global $pagenow, $post;
        $ret = false;

        $types = array(SLN_Plugin::POST_TYPE_SERVICE, SLN_Plugin::POST_TYPE_ATTENDANT, SLN_Plugin::POST_TYPE_BOOKING);
        $pt = null; 
        if($pagenow == 'post.php') {
            if ($post) {
                $pt = $post->post_type;
            }
            elseif (isset($_REQUEST['post'])) {
                $pt = get_post_type($_REQUEST['post']);
            }
            elseif (isset($_REQUEST['post_ID'])) {
                $pt = get_post_type($_REQUEST['post_ID']);
            }
        } elseif($pagenow == 'edit.php' || $pagenow == 'post-new.php') {
            $pt = isset($_GET['post_type']) ? $_GET['post_type'] : null;
        }

        if($pt){
            $ret = strpos($pt, 'sln_') === 0;
        }
        if($pagenow == 'admin.php'){
            $ret = isset($_GET['page']) && strpos($_GET['page'], 'salon') === 0;
        }

        $ret = apply_filters('sln.func.isSalonPage', $ret);

        return $ret;
    }


    public static function savePosts($posts){
        $ids  = array();
        foreach ($posts as $label => $post) {
            if (!self::checkPost($post['post']['post_title'], $post['post']['post_type'])) {
                $id = wp_insert_post($post['post']);
                if (isset($post['meta'])) {
                    foreach ($post['meta'] as $k => $v) {
                        add_post_meta($id, $k, $v);
                    }
                }
                $ids[$label] = $id;
            }
        }
    }

    private static function checkPost($title, $post_type)
    {
        return get_page_by_title($title, null, $post_type) ? true : false;
    }

    public static function zerofill($mStretch, $iLength = 2)
    {
        $sPrintfString = '%0' . (int)$iLength . 's';
        return sprintf($sPrintfString, $mStretch);
    }

    public static function get_translated_page_id($page_id, $lang = NULL)
    {
        return isset($page_id) ? apply_filters( 'wpml_object_id', $page_id , 'page', true, $lang ) : NULL;
    }
}
