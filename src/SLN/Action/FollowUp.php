<?php

class SLN_Action_FollowUp
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

        $this->execute();
    }

    public function executeEmail()
    {
        $this->mode = self::EMAIL;

        $this->execute();
    }

    private function execute()
    {
        SLN_TimeFunc::startRealTimezone();

        $type = $this->mode;
        $p = $this->plugin;
        $followup = $p->getSettings()->get('follow_up_'.$type);
        if ($followup) {
            $p->addLog($type.' follow up execution');
            if (self::SMS === $type && SLN_Enum_CheckoutFields::isHiddenOrNotRequired('phone')) {
                $p->addLog($type.' phone field is hidden or not required');
            }
            else {
                foreach ($this->getCustomers() as $customer) {
                    $this->send($customer);
                    $p->addLog($type.' follow up sent to '.$customer->getId());
                }
            }
            $p->addLog($type.' follow up execution ended');
        }

        SLN_TimeFunc::endRealTimezone();
    }

    /**
     * @param SLN_Wrapper_Customer $booking
     * @throws Exception
     */
    private function send(SLN_Wrapper_Customer $customer)
    {
        $p = $this->plugin;
        if (self::EMAIL == $this->mode) {
            $p->sendMail('mail/follow_up', compact('customer'));
        } else {
            $p->sms()->send(
                $customer->getMeta('phone'),
                $p->loadView('sms/follow_up', compact('customer'))
            );
        }
    }

    /**
     * @return SLN_Wrapper_Customer[]
     * @throws Exception
     */
    private function getCustomers()
    {
        $ret         = array();

        $interval    = $this->plugin->getSettings()->get('follow_up_interval');
        $currentTime = current_time('Y-m-d');

        $user_query  = new WP_User_Query(array('role' => SLN_Plugin::USER_ROLE_CUSTOMER));

        foreach ( $user_query->get_results() as $user ) {
            $customer = new SLN_Wrapper_Customer($user);

            if ($interval === 'custom') {
                if ($customer->getNextBookingTime() && date('Y-m-d', strtotime('-2 days', strtotime($customer->getNextBookingTime()))) === $currentTime) {
                    $ret[] = $customer;
                }
            }
            else {
                if ($customer->getLastBookingTime() && date('Y-m-d', strtotime($interval, strtotime($customer->getLastBookingTime()))) === $currentTime) {
                    $ret[] = $customer;
                }
            }
        }

        return $ret;
    }
}
