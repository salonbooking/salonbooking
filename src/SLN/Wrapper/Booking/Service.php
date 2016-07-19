<?php


final class SLN_Wrapper_Booking_Service
{
    private $data;

    /**
     * SLN_Wrapper_Booking_Service constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $hasAttendant = isset($data['attendant']) && !empty($data['attendant']);
        $this->data = array(
            'service' => SLN_Plugin::getInstance()->createService($data['service']),
            'attendant' => $hasAttendant ? SLN_Plugin::getInstance()->createAttendant($data['attendant']) : false,
            'starts_at' => new SLN_DateTime(
                SLN_Func::filter($data['start_date'], 'date').' '.SLN_Func::filter($data['start_time'], 'time')
            ),
            'duration' => new SLN_DateTime('1970-01-01 '.SLN_Func::filter($data['duration'], 'time')),
            'break_duration' => new SLN_DateTime('1970-01-01 '.SLN_Func::filter($data['break_duration'], 'time')),
            'price' => $data['price'],
            'exec_order' => $data['exec_order'],
        );
    }

    /**
     * @return SLN_DateTime
     */
    public function getDuration()
    {
        return $this->data['duration'];
    }

    /**
     * @return SLN_DateTime
     */
    public function getBreakDuration()
    {
        return $this->data['break_duration'];
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return floatval($this->data['price']);
    }

    /**
     * @return SLN_Wrapper_Service
     */
    public function getService()
    {
        return $this->data['service'];
    }

    /**
     * @return SLN_Wrapper_Attendant|bool
     */
    public function getAttendant()
    {
        return $this->data['attendant'];
    }

    /**
     * @return SLN_DateTime
     */
    public function getStartsAt()
    {
        return $this->data['starts_at'];
    }

    /**
     * @return SLN_DateTime
     */
    public function getEndsAt()
    {
        $minutes = SLN_Func::getMinutesFromDuration($this->getDuration());
        $endsAt = clone $this->getStartsAt();
        $endsAt->modify('+'.$minutes.' minutes');

        return $endsAt;
    }

    private function processBreakInfo() {
        $minutes      = SLN_Func::getMinutesFromDuration($this->getDuration());
        $breakMinutes = SLN_Func::getMinutesFromDuration($this->getBreakDuration());

        if ($breakMinutes) {
            $busyTime = $minutes - $breakMinutes;
            $busyPart = (int) ceil($busyTime / 2);

            $breakStartsAt = clone $this->getStartsAt();
            $breakStartsAt->modify('+'.$busyPart.' minutes');

            $breakEndsAt = clone $this->getEndsAt();
            $breakEndsAt->modify('-'.$busyPart.' minutes');

//            $durationBeforeBreak = new SLN_DateTime('1970-1-1 '.SLN_Func::convertToHoursMins($busyPart));
//            $durationAfterBreak  = new SLN_DateTime('1970-1-1 '.SLN_Func::convertToHoursMins($busyPart));
//
//            $break = true;
        } else {
//            $break = false;
            $breakStartsAt = clone $this->getStartsAt();
            $breakEndsAt = clone $this->getStartsAt();
//            $durationBeforeBreak = clone $this->getDuration();
//            $durationAfterBreak = clone $this->getDuration();
        }

//        $this->break = $break;
        $this->breakStartsAt = $breakStartsAt;
        $this->breakEndsAt = $breakEndsAt;
//        $this->durationBeforeBreak = $durationBeforeBreak;
//        $this->durationAfterBreak = $durationAfterBreak;
    }

//    /**
//     * @return SLN_DateTime
//     */
//    public function isNoBreak()
//    {
//        if (!isset($this->break)) {
//            $this->processBreakInfo();
//
//        }
//        return !$this->break;
//    }
//
//    /**
//     * @return SLN_DateTime
//     */
//    public function getDurationBeforeBreak()
//    {
//        if (!isset($this->durationBeforeBreak)) {
//            $this->processBreakInfo();
//
//        }
//        return $this->durationBeforeBreak;
//    }
//
//    /**
//     * @return SLN_DateTime
//     */
//    public function getDurationAfterBreak()
//    {
//        if (!isset($this->durationAfterBreak)) {
//            $this->processBreakInfo();
//
//        }
//        return $this->durationAfterBreak;
//    }

    /**
     * @return SLN_DateTime
     */
    public function getBreakStartsAt()
    {
        if (!isset($this->breakStartsAt)) {
            $this->processBreakInfo();

        }
        return $this->breakStartsAt;
    }

    /**
     * @return SLN_DateTime
     */
    public function getBreakEndsAt()
    {
        if (!isset($this->breakEndsAt)) {
            $this->processBreakInfo();

        }
        return $this->breakEndsAt;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'attendant' => @is_object($this->data['attendant']) ? $this->data['attendant']->getId() : $this->data['attendant'],
            'service' => $this->data['service']->getId(),
            'duration' => $this->data['duration']->format('H:i'),
            'break_duration' => $this->data['break_duration']->format('H:i'),
            'start_date' => $this->data['starts_at']->format('Y-m-d'),
            'start_time' => $this->data['starts_at']->format('H:i'),
            'price' => floatval($this->data['price']),
            'exec_order' => intval($this->data['exec_order']),
        );
    }

    public function __toString()
    {
        return $this->getService()->__toString();
    }
}
