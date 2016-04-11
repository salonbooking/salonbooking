<?php
/**
 * @var $prefix
 * @var $row
 * @var $rulenumber
 */

if (!function_exists('sln_date_create_from_format')) {
    function sln_date_create_from_format($dformat, $dvalue)
    {

        $schedule = $dvalue;
        $schedule_format = str_replace(
            array('Y', 'm', 'M', 'd', 'H', 'g', 'i', 'a'),
            array('%Y', '%m', '%b', '%d', '%H', '%I', '%M', '%p'),
            $dformat
        );
        // %Y, %m and %d correspond to date()'s Y m and d.
        // %I corresponds to H, %M to i and %p to a
        $ugly = strptime($schedule, $schedule_format);
        $ymd = sprintf(
        // This is a format string that takes six total decimal
        // arguments, then left-pads them with zeros to either
        // 4 or 2 characters, as needed
            '%04d-%02d-%02d %02d:%02d:%02d',
            $ugly['tm_year'] + 1900,  // This will be "111", so we need to add 1900.
            $ugly['tm_mon'] + 1,      // This will be the month minus one, so we add one.
            $ugly['tm_mday'],
            $ugly['tm_hour'],
            $ugly['tm_min'],
            $ugly['tm_sec']
        );
        $new_schedule = new DateTime($ymd);

        return $new_schedule;
    }
}

if (!isset($rulenumber)) {
    $rulenumber = 'New';
}
if (!isset($row)) {
    $row = array();
}
$interval = SLN_Plugin::getInstance()->getSettings()->get('interval');
$dateFormat = SLN_Enum_DateFormat::getPhpFormat(SLN_Enum_DateFormat::_MYSQL);
$dateFrom = isset($row['from_date']) ? $row['from_date'] : date($dateFormat);
$dateFrom = sln_date_create_from_format($dateFormat, $dateFrom);
$dateTo = isset($row['to_date']) ? $row['to_date'] : date($dateFormat);
$dateTo = sln_date_create_from_format($dateFormat, $dateTo);
$timeFormat = SLN_Enum_TimeFormat::getPhpFormat(SLN_Enum_TimeFormat::_DEFAULT);
$timeFrom = isset($row['from_time']) ? $row['from_time'] : date($timeFormat);
$timeFrom = sln_date_create_from_format($timeFormat, $timeFrom);
$timeTo = isset($row['to_time']) ? $row['to_time'] : date($timeFormat);
$timeTo = sln_date_create_from_format($timeFormat, $timeTo);

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
