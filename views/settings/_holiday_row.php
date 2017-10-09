<?php
/**
 * @var $prefix
 * @var $row
 * @var $rulenumber
 */
if (!function_exists('strptime'))
{
    /**
     *
     *
     * @param string $buffer
     * @param string $pattern
     * @return string
     * @access private
     */
    function _strptime_match(&$buffer,$pattern)
    {
        if (is_array($pattern)) {
            $pattern = implode('|',$pattern);
        }
        $pattern = '/^('.$pattern.')/i';

        $ret = null;
        $matches;
        if (preg_match($pattern,$buffer,$matches))
        {
            $ret = $matches[0];

            //Remove the match from the buffer
            $buffer = preg_replace($pattern,'',$buffer);
        }
        return $ret;
    }

    /**
     *
     *
     * @param int $n
     * @param int $min
     * @param int $max
     * @return int
     * @access private
     */
    function _strptime_clamp($n,$min,$max) {
        return max(min($n,$max),$min);
    }

    /**
     *
     *
     * @param string $p
     * @return array
     * @access private
     */
    function _strptime_wdays($p)
    {
        $locales = array();

        for ($i = 0; $i < 7; $i++)
        {
            $dayTime = strtotime('next Sunday +'.$i.' days');
            $locales[$i] = strftime('%'.$p,$dayTime);
        }

        return $locales;
    }

    /**
     *
     *
     * @param string $p
     * @return array
     * @access private
     */
    function _strptime_months($p)
    {
        $locales = array();

        for ($i = 1; $i <= 12; $i++) {
            $locales[$i] = strftime('%'.$p,mktime(0,0,0,$i));
        }

        return $locales;
    }

    /**
     *
     *
     * @param string $date
     * @param string $format
     * @return array
     */
    function strptime($date,$format)
    {
        //Default return values
        $tmSec = 0;
        $tmMin = 0;
        $tmHour = 0;
        $tmMday = 1;
        $tmMon = 1;
        $tmYear = 1900;
        $tmWday = 0;
        $tmYday = 0;

        $buffer = $date;
        $length = strlen($format);
        $lastc = null;

        for ($i = 0; $i < $length; $i++)
        {
            $c = $format[$i];

            //Remove spaces
            $buffer = ltrim($buffer);

            if ($lastc == '%')
            {
                switch ($c)
                {
                    case 'A':
                    case 'a':
                        _strptime_match($buffer,_strptime_wdays($c));
                        break;

                    case 'B':
                    case 'b':
                    case 'h':
                        $months = _strptime_months($c);
                        $month = _strptime_match($buffer,$months);
                        $tmMon = array_search($month,$months);
                        break;

                    case 'D':
                        //Unsupported by strftime on Windows
                        _strptime_match($buffer,'\d{2}\/\d{2}\/\d{2}');
                        break;

                    //case 'e':
                    case 'd':
                        $tmMday = intval(_strptime_match($buffer,'\d{2}'));
                        break;

                    case 'F':
                        //Unsupported by strftime on Windows
                        if ($ret = _strptime_match($buffer,'\d{4}-\d{2}-\d{2}'))
                        {
                            $frags = explode('-',$ret);
                            $tmYear = intval($frags[0]);
                            $tmMon = intval($frags[1]);
                            $tmMday = intval($frags[2]);
                        }
                        break;

                    case 'H':
                        $tmHour = intval(_strptime_match($buffer,'\d{2}'));
                        break;

                    case 'M':
                        $tmMin = intval(_strptime_match($buffer,'\d{2}'));
                        break;

                    case 'm':
                        $tmMon = intval(_strptime_match($buffer,'\d{2}'));
                        break;

                    case 'S':
                        $tmSec = intval(_strptime_match($buffer,'\d{2}'));
                        break;

                    case 'Y':
                        $tmYear = intval(_strptime_match($buffer,'\d{4}'));
                        break;

                    case 'y':
                        $year = intval(_strptime_match($buffer,'\d{2}'));
                        if ($year < 69) {
                            $tmYear = 2000 + $year;
                        } else {
                            $tmYear = 1900 + $year;
                        }
                        break;

                }
            }
            else {
                $buffer = ltrim($buffer,$c);
            }

            $lastc = $c;
        }

        //Date must exists!
        if (!checkdate($tmMon,$tmMday,$tmYear)) {
            return false;
        }

        //Clamp hours values
        $tmHour = _strptime_clamp($tmHour,0,23);
        $tmMin = _strptime_clamp($tmMin,0,59);
        $tmSec = _strptime_clamp($tmSec,0,61); //>59 = Leap seconds

        //Compute wday and yday
        $timestamp = mktime($tmHour,$tmMin,$tmSec,$tmMon,$tmMday,$tmYear);
        $tmWday = date('w',$timestamp);
        $tmYday = date('z',$timestamp);

        //Return
        $time = array();
        $time['tm_sec'] = $tmSec;
        $time['tm_min'] = $tmMin;
        $time['tm_hour'] = $tmHour;
        $time['tm_mday'] = $tmMday;
        $time['tm_mon'] = ($tmMon-1); //0-11
        $time['tm_year'] = ($tmYear-1900);
        $time['tm_wday'] = $tmWday;
        $time['tm_yday'] = $tmYday;
        $time['unparsed'] = $buffer; //Unparsed buffer
        return $time;
    }
}
if (!isset($rulenumber)) {
    $rulenumber = 'New';
}
if (!isset($row)) {
    $row = array();
}
$interval = SLN_Plugin::getInstance()->getSettings()->get('interval');
try {
    $dateFrom = new SLN_DateTime(isset($row['from_date']) ? $row['from_date'] : null);
    $dateTo   = new SLN_DateTime(isset($row['to_date']) ? $row['to_date'] : null);
    $timeFrom = new SLN_DateTime(isset($row['from_time']) ? $row['from_time'] : null);
    $timeTo   = new SLN_DateTime(isset($row['to_time']) ? $row['to_time'] : null);
}catch(\Exception $e){
    $dateFrom = new SLN_DateTime();
    $dateTo   = new SLN_DateTime();
    $timeFrom = new SLN_DateTime();
    $timeTo   = new SLN_DateTime();
}
?>
<div class="col-xs-12 sln-booking-rule">
    <h2 class="sln-box-title"><?php _e('Rule', 'salon-booking-system'); ?>
        <strong><?php echo $rulenumber; ?></strong></h2>
    <div class="row">
        <div class="col-xs-12 col-md-4 sln-slider-wrapper">
            <h6 class="sln-fake-label"><?php _e('Start on', 'salon-booking-system') ?></h6>
            <div class="sln_datepicker"><?php SLN_Form::fieldJSDate($prefix."[from_date]", $dateFrom) ?></div>
        </div>
        <div class="col-xs-12 col-md-4 sln-slider-wrapper">
            <h6 class="sln-fake-label"><?php _e('End on', 'salon-booking-system') ?></h6>
            <div class="sln_datepicker"><?php SLN_Form::fieldJSDate($prefix."[to_date]", $dateTo) ?></div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
            <button class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash"
                    data-collection="remove"><?php echo __('Remove', 'salon-booking-system') ?></button>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4 sln-slider-wrapper">
            <h6 class="sln-fake-label"><?php _e('at', 'salon-booking-system') ?></h6>
            <div class="sln_timepicker"><?php SLN_Form::fieldJSTime(
                    $prefix."[from_time]",
                    $timeFrom,
                    compact('interval')
                ) ?></div>
        </div>
        <div class="col-xs-12 col-md-4 sln-slider-wrapper">
            <h6 class="sln-fake-label"><?php _e('at', 'salon-booking-system') ?></h6>
            <div class="sln_timepicker"><?php SLN_Form::fieldJSTime(
                    $prefix."[to_time]",
                    $timeTo,
                    compact('interval')
                ) ?></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
