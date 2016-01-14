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


    public function isValidDate($day)
    {
        foreach ($this->toArray() as $av) {
            if (!$av->isValidDate($day)) {
                return false;
            }
        }

        return true;
    }

}