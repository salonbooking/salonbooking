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
        return $this->isValidTime($date->format('Y-m-d'), $date->format('H:i'));
    }

    public function isValidTime($date, $time)
    {
        foreach ($this->toArray() as $h) {
            if (!$h->isValidTime($date, $time)) {
                return false;
            }
        }

        return true;
    }

}