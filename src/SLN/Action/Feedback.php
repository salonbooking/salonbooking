<?php

class SLN_Action_Feedback
{
    /** @var SLN_Plugin */
    private $plugin;
    private $mode;
    private $interval = '+1 days';

    public function __construct(SLN_Plugin $plugin) {
        $this->plugin = $plugin;
    }

    public function execute() {
        SLN_TimeFunc::startRealTimezone();

        $type = $this->mode;
        $p = $this->plugin;
        $feedback_reminder = $p->getSettings()->get( 'feedback_reminder' );
        if ($feedback_reminder) {
            $p->addLog( 'feedback reminder execution' );
            foreach ( $this->getCustomers() as $customer ) {
                $this->send( $customer );
                $p->addLog( 'feedback reminder sent to ' . $customer->getId() );
            }

            $p->addLog( 'feedback reminder execution ended' );
        }

        SLN_TimeFunc::endRealTimezone();
    }

    private function getCustomers() {
        $ret         = array();

        $interval    = $this->interval;
        $currentTime = current_time('Y-m-d');

        $user_query  = new WP_User_Query(array('role' => SLN_Plugin::USER_ROLE_CUSTOMER));

        foreach ( $user_query->get_results() as $user ) {
            $customer = new SLN_Wrapper_Customer($user);

            if ($customer->getLastBookingTime() && date('Y-m-d', strtotime($interval, strtotime($customer->getLastBookingTime()))) === $currentTime) {
                $ret[] = $customer;
            }
        }
//echo '<pre>'; print_r($ret); die();
        return $ret;
    }

    private function send( $customer ) {
        $p = $this->plugin;
        $p->sendMail('mail/feedback', compact('customer'));
    }
}