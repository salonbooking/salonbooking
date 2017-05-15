<?php

class SLN_Helper_AvailabilityItems
{
    /** @var SLN_Helper_AvailabilityItem[] */
    private $items;
    private $weekDayRules;
    private $offset;

    public function __construct($availabilities, $offset = 0)
    {
        if ($availabilities) {
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
     * @return array
     */
    private function mergeRanges($ranges)
    {

        for ($i = 0; $i < count($ranges['from']); $i++) {
            for ($j = 0; $j < count($ranges['from']); $j++) {
                if ($j === $i) {
                    continue;
                }

                $first  = array('from' => $ranges['from'][$i], 'to' => $ranges['to'][$i]);
                $second = array('from' => $ranges['from'][$j], 'to' => $ranges['to'][$j]);
                if (strtotime($second['to']) >= strtotime($first['from']) && strtotime($second['to']) <= strtotime(
                        $first['to']
                    )      // end of 2nd range in 1st range
                    || strtotime($first['to']) >= strtotime($second['from']) && strtotime($first['to']) <= strtotime(
                        $second['to']
                    )   // or end of 1st range in 2nd range
                ) {
                    // 2 ranges merge into one
                    $ranges['from'][$i] = (strtotime($first['from']) <= strtotime(
                        $second['from']
                    ) ? $first['from'] : $second['from']);
                    $ranges['to'][$i]   = (strtotime($first['to']) >= strtotime(
                        $second['to']
                    ) ? $first['to'] : $second['to']);
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

    public function getWeekDayRules()
    {
        if (is_null($this->weekDayRules)) {
            $rules = array();
            if (!empty($this->items) && !(reset($this->items) instanceof SLN_Helper_AvailabilityItemNull)) {
                for ($i = 0; $i < 7; $i++) {
                    $weekDayRules = array();
                    foreach ($this->items as $item) {
                        /** @var SLN_Helper_AvailabilityItem $item */
                        $data   = $item->getData();
                        $offset = $item->getOffset();
                        if (isset($data['days'][$i + 1]) && !empty($data['days'][$i + 1])) {
                            $weekDayRule = array('from' => $data['from'], 'to' => $data['to']);
                            foreach ($weekDayRule['to'] as &$time) {
                                $time = date('H:i', strtotime($time) - $offset);
                            }

                            $weekDayRules['from'] = (isset($weekDayRules['from']) ? array_merge(
                                $weekDayRules['from'],
                                $weekDayRule['from']
                            ) : $weekDayRule['from']);
                            $weekDayRules['to']   = (isset($weekDayRules['to']) ? array_merge(
                                $weekDayRules['to'],
                                $weekDayRule['to']
                            ) : $weekDayRule['to']);
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

    public function isValidDatetimeDuration(DateTime $date, DateTime $duration)
    {
        $minutes = SLN_Func::getMinutesFromDuration($duration);
        $interval = SLN_Plugin::getInstance()->getSettings()->getInterval();
        $steps = $minutes / $interval; 
        $endDate = clone $date;
        $endDate->modify('+'.$minutes.' minutes');
        do {
            if(!$this->isValidDateTime($date)) {
                return false;
            }
            $date = clone $date;
            $date->modify('+'.$interval.' minutes');
            $steps --;
        }while($steps >= 1);

        if(!$this->isValidDateTime($endDate)) {
            return false;
        }
        return true;
    }

    public function isValidDatetime(DateTime $date)
    {
        $time = $date->format('H:i');
        $day = $date->format('Y-m-d');
        if($time == '00:00'){ 
            return $this->isValidTime($day, $time) || $this->isValidTime(date('Y-m-d', strtotime($day.' -1 day')), '24:00');
        }else{
            return $this->isValidTime($day, $time);
        }
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


    public static function processSubmission($data = null)
    {
        if (!$data) {
            return $data;
        }

        foreach ($data as &$item) {
            if (isset($item['always']) && $item['always'] == 1) {
                $item['always']    = true;
                $item['from_date'] = null;
                $item['to_date']   = null;
            } else {
                $item['always']    = false;
                $item['from_date'] = SLN_TimeFunc::evalPickedDate($item['from_date']);
                $item['to_date']   = SLN_TimeFunc::evalPickedDate($item['to_date']);
            }
        }

        return $data;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        foreach ($this->items as $i) {
            $i->setOffset($offset);
        }
    }
    public function getOffset()
    {
        return $this->offset;
    }

    public function getTimeMinMax()
    {
        $times = array_reduce($this->items, function($carry, $item){
            $t = $item->getTimes();
            return array_merge($carry, $t[0], $t[1]);
        }, array());
        $ret = array(date('H:i', min($times)), date('H:i', max($times)));
        if($ret[1] == '00:00') $ret[1] = '24:00';
        return $ret;
    }
}
