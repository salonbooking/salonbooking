<?php

class SLN_Action_Reminder
{
    const EMAIL = 'email';
    const SMS   = 'sms';

    private $plugin;
    private $date;
    private $time;
    private $errors = array();

    public function executeEmail() {
        $this->execute(self::EMAIL);
    }

    public function executeSms() {
        $this->execute(self::SMS);
    }

    private function execute($type)
    {
        $this->plugin = SLN_Plugin::getInstance();
        $remind = $this->plugin->getSettings()->get($type.'_remind');
        if($remind){
            $this->plugin->addLog($type.' reminder execution');
            $this->dispatchAdvice($type);
            $this->plugin->addLog($type.' reminder execution ended');
        }
    }

    private function dispatchAdvice($type){
        $plugin = $this->plugin;
        $interval = $plugin->getSettings()->get($type.'_remind_interval');
        $date = new DateTime();
        $date->modify($interval);
        $now = new DateTime(); 
        $args = array(
            'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
            'nopaging'   => true,
            'meta_query' => array(
                array(
                    'key'     => '_sln_booking_date',
                    'value'   => $date->format('Y-m-d'),
                    'compare' => '=',
                )
            )
        );
        $query = new WP_Query($args);
        $ret = array();
        foreach ($query->get_posts() as $p) {
            $booking = $plugin->createBooking($p);
            $d = $booking->getStartsAt();
            if($d >= $now && $d <= $date){
                $methodGetRemind = 'get'.(self::EMAIL == $type ? 'Email' : '').'Remind';
                $methodSetRemind = 'set'.(self::EMAIL == $type ? 'Email' : '').'Remind';
                if(!$booking->$methodGetRemind()){
                    $this->plugin->addLog($type.' reminder sent to '.$booking->getId());
                    if (self::EMAIL == $type) {
                        $args = compact('booking');
                        $args['remind'] = true;

                        $plugin->sendMail('mail/summary', $args);
                    } else {
                        $plugin->sendSms($booking->getPhone(), $plugin->loadView('sms/remind', compact('booking')));
                    }
                    $booking->$methodSetRemind(true);
                }
            }
        }
        wp_reset_query();
        wp_reset_postdata();
    }
}
