<?php

class SLN_Action_Ajax_ResendNotification extends SLN_Action_Ajax_Abstract
{
    public function execute()
    {
       if(!current_user_can( 'manage_salon' )) throw new Exception('not allowed');
        $booking = new SLN_Wrapper_Booking($_POST['post_id']);
        if(isset($_POST['emailto'])){
            $p = $this->plugin;

            $args                    = compact('booking');
            $args['to']              = $_POST['emailto'];
            $args['updated']         = true;
            $args['updated_message'] = $_POST['message'];
            $p->sendMail('mail/summary', $args);

            return array('success' => __('E-mail sent'));
        }else{
            return array('error' => __('Please specify an email'));
        }
 
       return $ret;
    }
}
