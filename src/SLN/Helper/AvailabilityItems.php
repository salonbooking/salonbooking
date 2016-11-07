<?php

class SLN_Helper_AvailabilityItems
{
    private $items;
    private $weekDayRules;

    public function __construct($availabilities, $offset = 0)
    {
        if($availabilities){
            foreach ($availabilities as $item) {
                $this->items[] = new SLN_Helper_AvailabilityItem($item, $offset);
            }
        }
        if (empty($this->items)) {
            $this->items = array(new SLN_Helper_AvailabilityItemNull(array()));
        }
    }

	/**
     * @param array $ranges Array with keys 'from' & 'to'
     */
    private function mergeRanges($ranges) {

        for($i = 0; $i < count($ranges['from']); $i++) {
            for($j = 0; $j < count($ranges['from']); $j++) {
                if ($j === $i) {
                    continue;
                }

                $first       = array('from' => $ranges['from'][$i], 'to' => $ranges['to'][$i]);
                $second      = array('from' => $ranges['from'][$j], 'to' => $ranges['to'][$j]);
                if (strtotime($second['to']) >= strtotime($first['from']) && strtotime($second['to']) <= strtotime($first['to'])      // end of 2nd range in 1st range
                    || strtotime($first['to']) >= strtotime($second['from']) && strtotime($first['to']) <= strtotime($second['to'])   // or end of 1st range in 2nd range
                ) {
                    // 2 ranges merge into one
                    $ranges['from'][$i] = (strtotime($first['from']) <= strtotime($second['from']) ? $first['from'] : $second['from']);
                    $ranges['to'][$i]   = (strtotime($first['to']) >= strtotime($second['to']) ? $first['to'] : $second['to']);
                    unset($ranges['from'][$j], $ranges['to'][$j]);

                    $ranges['from'] = array_values($ranges['from']);
                    $ranges['to']   = array_values($ranges['to']);

                    $j--;
                    continue;
                }
            }
        }

        return $ranges;
    }

    public function getWeekDayRules() {
        if (is_null($this->weekDayRules)) {
            $rules = array();
            if (!empty($this->items) && !(reset($this->items) instanceof SLN_Helper_AvailabilityItemNull)) {
                for($i = 0; $i < 7; $i++) {
                    $weekDayRules = array();
                    foreach($this->items as $item) {
                        /** @var SLN_Helper_AvailabilityItem $item */
                        $data   = $item->getData();
                        $offset = $item->getOffset();
                        if (isset($data['days'][$i+1]) && !empty($data['days'][$i+1])) {
                            $weekDayRule = array('from' => $data['from'], 'to' => $data['to']);
                            foreach($weekDayRule['to'] as &$time) {
                                $time = date('H:i', strtotime($time)-$offset);
                            }

                            $weekDayRules['from'] = (isset($weekDayRules['from']) ? array_merge($weekDayRules['from'], $weekDayRule['from']) : $weekDayRule['from']);
                            $weekDayRules['to']   = (isset($weekDayRules['to']) ? array_merge($weekDayRules['to'], $weekDayRule['to']) : $weekDayRule['to']);
                        }
                    }

                    if (!empty($weekDayRules)) {
                        $weekDayRules = $this->mergeRanges($weekDayRules);
                    }
                    $rules[$i] = $weekDayRules;
                }
            }

            $this->weekDayRules = $rules;
        }
        return $this->weekDayRules;
    }

    /**
     * @return SLN_Helper_AvailabilityItem[]
     */
    public function toArray()
    {
        return $this->items;
    }

    public function isValidDatetime(DateTime $date)
    {
        return $this->isValidTime($date->format('Y-m-d'), $date->format('H:i'));
    }

    public function isValidDate($day)
    {
        foreach ($this->toArray() as $av) {
            if ($av->isValidDate($day)) {
                return true;
            }
        }

        return false;
    }

    public function isValidTime($date, $time)
    {
        foreach ($this->toArray() as $av) {
            if ($av->isValidTime($date, $time)) {
                return true;
            }
        }

        return false;
    }


}
