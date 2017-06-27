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
        $data['break_duration'] = isset($data['break_duration']) ? $data['break_duration'] : '00:00';
        $this->data = array(
            'service' => SLN_Plugin::getInstance()->createService($data['service']),
            'attendant' => $hasAttendant ? SLN_Plugin::getInstance()->createAttendant($data['attendant']) : false,
            'starts_at' => new SLN_DateTime(
                SLN_Func::filter($data['start_date'], 'date').' '.SLN_Func::filter($data['start_time'], 'time')
            ),
            'duration' => new SLN_DateTime('1970-01-01 '.SLN_Func::filter($data['duration'], 'time')),
            'break_duration' => new SLN_DateTime('1970-01-01 '.SLN_Func::filter($data['break_duration'], 'time')),
            'total_duration' => new SLN_DateTime('1970-01-01 '.SLN_Func::convertToHoursMins(SLN_Func::getMinutesFromDuration($data['duration']) + SLN_Func::getMinutesFromDuration($data['break_duration']))),
            'price' => $data['price'],
            'exec_order' => $data['exec_order'],
        );

        $this->data['service'] = apply_filters('sln.booking_services.buildService', $this->data['service']);
    }

    /**
     * @param SLN_Wrapper_AttendantInterface|false $attendant
     */
    public function setAttendant($attendant = false) {
        $this->data['attendant'] = $attendant;
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
     * @return SLN_DateTime
     */
    public function getTotalDuration()
    {
        return $this->data['total_duration'];
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return floatval($this->data['price']);
    }

    /**
     * @return SLN_Wrapper_ServiceInterface
     */
    public function getService()
    {
        return $this->data['service'];
    }

    /**
     * @return SLN_Wrapper_AttendantInterface|false
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
        $minutes = SLN_Func::getMinutesFromDuration($this->getTotalDuration());
        $endsAt = clone $this->getStartsAt();
        $endsAt->modify('+'.$minutes.' minutes');

        return $endsAt;
    }

    private function processBreakInfo() {
        if (isset($this->breakProcessed)) {
            return;
        }
        $minutes      = SLN_Func::getMinutesFromDuration($this->getDuration());
        $breakMinutes = SLN_Func::getMinutesFromDuration($this->getBreakDuration());

        if ($breakMinutes) {
            $busyTime = $minutes;
            $busyPart = (int) ceil($busyTime / 2);

            $breakStartsAt = clone $this->getStartsAt();
            $breakStartsAt->modify('+'.$busyPart.' minutes');

            $breakEndsAt = clone $this->getEndsAt();
            $breakEndsAt->modify('-'.$busyPart.' minutes');

            $bookingOffsetEnabled = SLN_Plugin::getInstance()->getSettings()->get('reservation_interval_enabled');
            if ($bookingOffsetEnabled) {
                $bookingOffset = SLN_Plugin::getInstance()->getSettings()->get('minutes_between_reservation');
            } else {
                $bookingOffset = 0;
            }

            $breakWithOffsetStartsAt = clone $breakStartsAt;
            $breakWithOffsetStartsAt->modify('+'.$bookingOffset.' minutes');

            $breakWithOffsetEndsAt = clone $breakEndsAt;
            $breakWithOffsetEndsAt->modify('-'.$bookingOffset.' minutes');

//            $durationBeforeBreak = new SLN_DateTime('1970-1-1 '.SLN_Func::convertToHoursMins($busyPart));
//            $durationAfterBreak  = new SLN_DateTime('1970-1-1 '.SLN_Func::convertToHoursMins($busyPart));
//
//            $break = true;
        } else {
//            $break = false;
            $breakStartsAt           = clone $this->getStartsAt();
            $breakWithOffsetStartsAt = clone $this->getStartsAt();
            $breakEndsAt             = clone $this->getStartsAt();
            $breakWithOffsetEndsAt   = clone $this->getStartsAt();
//            $durationBeforeBreak = clone $this->getDuration();
//            $durationAfterBreak = clone $this->getDuration();
        }

//        $this->break = $break;
        $this->breakStartsAt = $breakStartsAt;
        $this->breakEndsAt = $breakEndsAt;
        $this->breakWithOffsetStartsAt = $breakWithOffsetStartsAt;
        $this->breakWithOffsetEndsAt = $breakWithOffsetEndsAt;
//        $this->durationBeforeBreak = $durationBeforeBreak;
//        $this->durationAfterBreak = $durationAfterBreak;
        $this->breakProcessed = true;
    }

//    /**
//     * @return SLN_DateTime
//     */
//    public function isNoBreak()
//    {
//        $this->processBreakInfo();
//
//        return !$this->break;
//    }
//
//    /**
//     * @return SLN_DateTime
//     */
//    public function getDurationBeforeBreak()
//    {
//        $this->processBreakInfo();
//
//        return $this->durationBeforeBreak;
//    }
//
//    /**
//     * @return SLN_DateTime
//     */
//    public function getDurationAfterBreak()
//    {
//        $this->processBreakInfo();
//
//        return $this->durationAfterBreak;
//    }

    /**
     * @return SLN_DateTime
     */
    public function getBreakStartsAt()
    {
        $this->processBreakInfo();

        return $this->breakStartsAt;
    }

    /**
     * @return SLN_DateTime
     */
    public function getBreakEndsAt()
    {
        $this->processBreakInfo();

        return $this->breakEndsAt;
    }

    /**
     * @return SLN_DateTime
     */
    public function getBreakWithOffsetStartsAt()
    {
        $this->processBreakInfo();

        return $this->breakWithOffsetStartsAt;
    }

    /**
     * @return SLN_DateTime
     */
    public function getBreakWithOffsetEndsAt()
    {
        $this->processBreakInfo();

        return $this->breakWithOffsetEndsAt;
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
