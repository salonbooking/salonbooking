<?php

class SLN_Helper_HolidayItems
{
    private $items = array();

    public function __construct($holidays)
    {
        if (empty($holidays)) {
            return;
        }
        foreach ($holidays as $item) {
            $this->items[] = new SLN_Helper_HolidayItem($item);
        }
    }

    /**
     * @return SLN_Helper_HolidayItem[]
     */
    public function toArray()
    {
        return $this->items;
    }

    public function isValidDatetime(DateTime $date)
    {
        return $this->isValidTime($date->format('Y-m-d H:i'));
    }

    public function isValidDate($day)
    {
        foreach ($this->toArray() as $h) {
            if (!$h->isValidDate($day)) {
                return false;
            }
        }

        return true;
    }

    public function isValidTime($date)
    {
        foreach ($this->toArray() as $h) {
            if (!$h->isValidTime($date)) {
                return false;
            }
        }

        return true;
    }

    public static function processSubmission($data = null)
    {
        if(!$data) return $data;
        foreach ($data as &$holidayData) {
            $holidayData['from_date'] = SLN_TimeFunc::evalPickedDate($holidayData['from_date']);
            $holidayData['to_date'] = SLN_TimeFunc::evalPickedDate($holidayData['to_date']);
            $holidayData['from_time'] = SLN_TimeFunc::evalPickedTime($holidayData['from_time']);
            $holidayData['to_time'] = SLN_TimeFunc::evalPickedTime($holidayData['to_time']);
        }
        return $data;
    }
}
