<?php

// START UPDATE HOLIDAYS RULES
$holidays = SLN_Plugin::getInstance()->getSettings()->get('holidays');
$holidays = !empty($holidays) ? $holidays : array();
foreach($holidays as &$holidayData) {
	$holidayData['from_date'] = SLN_Func::evalPickedDate($holidayData['from_date']);
	$holidayData['to_date']   = SLN_Func::evalPickedDate($holidayData['to_date']);
	$holidayData['from_time'] = date('H:i', strtotime($holidayData['from_time']));
	$holidayData['to_time']   = date('H:i', strtotime($holidayData['to_time']));
}
SLN_Plugin::getInstance()->getSettings()->set('holidays', $holidays);
// END UPDATE HOLIDAYS RULES