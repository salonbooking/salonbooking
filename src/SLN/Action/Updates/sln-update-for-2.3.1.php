<?php

// START UPDATE HOLIDAYS RULES
$holidays = SLN_Plugin::getInstance()->getSettings()->get('holidays');
$holidays = !empty($holidays) ? $holidays : array();
foreach($holidays as $k => &$holidayData) {
	try {
		$from_date = SLN_TimeFunc::evalPickedDate($holidayData['from_date']);
	} catch(Exception $e) {
		if (!strtotime($holidayData['from_date'])) {
			unset($holidays[$k]);
			continue;
		}
		$from_date = date('Y-m-d', strtotime($holidayData['from_date']));
	}
	$holidayData['from_date'] = $from_date;

	try {
		$to_date = SLN_TimeFunc::evalPickedDate($holidayData['to_date']);
	} catch(Exception $e) {
		if (!strtotime($holidayData['to_date'])) {
			unset($holidays[$k]);
			continue;
		}
		$to_date = date('Y-m-d', strtotime($holidayData['to_date']));
	}
	$holidayData['to_date'] = $to_date;

	$holidayData['from_time'] = date('H:i', strtotime($holidayData['from_time']));
	$holidayData['to_time']   = date('H:i', strtotime($holidayData['to_time']));
}
SLN_Plugin::getInstance()->getSettings()->set('holidays', $holidays);
// END UPDATE HOLIDAYS RULES
