<?php

class SLN_Action_Reminder
{
    const EMAIL = 'email';
    const SMS = 'sms';

    /** @var SLN_Plugin */
    private $plugin;
    private $mode;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function executeSms()
    {
        $this->mode = self::SMS;

        return $this->execute();
    }

    public function executeEmail()
    {
        $this->mode = self::EMAIL;

        return $this->execute();
    }

    private function execute()
    {
        $type = $this->mode;
        $p = $this->plugin;
        $remind = $p->getSettings()->get($type.'_remind');
        if (!$remind) {
            return;
        }
        $p->addLog($type.' reminder execution');
        foreach ($this->getBookings() as $booking) {
            $this->send($booking);
            $p->addLog($type.' reminder sent to '.$booking->getId());
            $booking->setMeta($type.'_remind', true);
        }
        $p->addLog($type.' reminder execution ended');
    }

    /**
     * @param SLN_Wrapper_Booking $booking
     * @throws Exception
     */
    private function send(SLN_Wrapper_Booking $booking)
    {
        $p = $this->plugin;
        if (self::EMAIL == $this->mode) {
            $args = compact('booking');
            $args['remind'] = true;
            $p->sendMail('mail/summary', $args);
        } else {
            $p->sms()->send(
                $booking->getPhone(),
                $p->loadView('sms/remind', compact('booking'))
            );
        }
    }

    /**
     * @return SLN_Wrapper_Booking[]
     * @throws Exception
     */
    private function getBookings()
    {
        $min = $this->getMin();
        $max = $this->getMax();
        /** @var SLN_Repository_BookingRepository $repo */
        $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_BOOKING);
        $tmp = $repo->get(array('day' => $max));
        $ret = array();
        foreach ($tmp as $booking) {
            $d = $booking->getStartsAt();
            $done = $booking->getMeta($this->mode.'_remind');
            if ($d >= $min && $d <= $max && !$done) {
                $ret[] = $booking;
            }
        }

        return $ret;
    }


    /**
     * @return DateTime
     */
    private function getMin()
    {
        return new DateTime();
    }

    /**
     * @return DateTime
     */
    private function getMax()
    {
        $interval = $this->plugin->getSettings()->get($this->mode.'_remind_interval');
        $date = new DateTime();
        $date->modify($interval);

        return $date;
    }
}
