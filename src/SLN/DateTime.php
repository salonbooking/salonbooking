<?php

class SLN_DateTime extends DateTime
{
    public static $Format = 'Y-m-d H:i:s';
 
    public function formatWithWordpress($format){
        return date_i18n($format, $this->format('U'));
    }
    public function formatLocal($format){
        $off = get_option('gmt_offset');
        $this->modify(($off > 0 ? '+'.$off : $off).' hours');
        $ret = parent::format($format);
        $this->modify(($off > 0 ? '-'.$off : '+'.abs($off) ).' hours');
        return $ret;
    } 

    public function __toString()
    {
        return (string)parent::format(self::$Format);
    }

    public static function getWpTimezone() {
        $timezone = get_option( 'timezone_string' );
        if( empty( $timezone ) ) {
            $timezone = sprintf( 'UTC%+.4g', get_option( 'gmt_offset', 0 ) );
        }
        return $timezone;
    }

    public static function getWpTimezoneString() {
        // if site timezone string exists, return it
        if ($timezone = get_option('timezone_string'))
            return $timezone;

        // get UTC offset, if it isn't set then return UTC
        if (0 === ($utc_offset = get_option('gmt_offset', 0)))
            return 'UTC';

        // adjust UTC offset from hours to seconds
        $utc_offset *= 3600;

        // attempt to guess the timezone string from the UTC offset
        if ($timezone = timezone_name_from_abbr('', $utc_offset, 0)) {
            return $timezone;
        }

        // last try, guess timezone string manually
        $is_dst = date('I');

        foreach (timezone_abbreviations_list() as $abbr) {
            foreach ($abbr as $city) {
                if ($city['dst'] == $is_dst && $city['offset'] == $utc_offset)
                    return $city['timezone_id'];
            }
        }

        // fallback to UTC
        return 'UTC';
    }
}
