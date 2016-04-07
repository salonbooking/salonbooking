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
        $this->data = array(
            'service' => SLN_Plugin::getInstance()->createService($data['service']),
            'attendant' => SLN_Plugin::getInstance()->createAttendant($data['attendant']),
            'starts_at' => new SLN_DateTime(
                SLN_Func::filter($data['start_date'], 'date').' '.SLN_Func::filter($data['start_time'], 'time')
            ),
            'duration' => new SLN_DateTime('1970-01-01 '.SLN_Func::filter($data['duration'], 'time')),
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
     * @return SLN_Wrapper_Attendant
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
        $durationParts = explode(':', $this->getDuration()->format('H:i'));
        $h = intval($durationParts[0]);
        $i = intval($durationParts[1]);
        $minutes = $h * 60 + $i;
        $endsAt = clone $this->getStartsAt();
        $endsAt->modify('+'.$minutes.' minutes');

        return $endsAt;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'attendant' => @$this->data['attendant']->getId(),
            'service' => $this->data['service']->getId(),
            'duration' => $this->data['duration']->format('H:i'),
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
