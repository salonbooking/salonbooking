<?php

class SLN_Shortcode_Salon_ThankyouStep extends SLN_Shortcode_Salon_Step
{
    private $op;

    public function setOp($op){
        $this->op = $op;
    }

    protected function dispatchForm()
    {
        $plugin = $this->getPlugin();
        $settings = $plugin->getSettings(); 
        $bb = $plugin->getBookingBuilder();
        if($_GET['sln_booking_id']){
            $bb->clear($_GET['sln_booking_id']);
           
        }
        $booking = $bb->getLastBooking();
        $paymentMethod = SLN_Enum_PaymentMethodProvider::getService($settings->getPaymentMethod(), $plugin);
        if(isset($_GET['op']) || (isset($_GET['mode']) && $_GET['mode'] != 'later')){
            if($error = $paymentMethod->dispatchThankyou($this, $booking)){
                $this->addError($error); 
            }
        } elseif (isset($_GET['mode']) && $_GET['mode'] == 'later') {
            $booking->setStatus(SLN_Enum_BookingStatus::PAY_LATER);
            $this->goToThankyou();
        }


        return false;
    }

    public function goToThankyou()
    {
        $id = $this->getPlugin()->getSettings()->getThankyouPageId();
        if ($id) {
            $this->redirect(get_permalink($id));
        }
    }

    public function getViewData()
    {
        $ret = parent::getViewData();
        $formAction = $ret['formAction'];

        return array_merge(
            $ret,
            array(
                'formAction' => $formAction,
                'booking'    => $this->getPlugin()->getBookingBuilder()->getLastBooking(),
                'laterUrl'   => add_query_arg(
                    array('mode' => 'later', 'submit_' . $this->getStep() => 1),
                    $formAction
                ),
                'payUrl'  => add_query_arg(
                    array('mode' => $this->getPlugin()->getSettings()->getPaymentMethod(), 'submit_' . $this->getStep() => 1),
                    $formAction
                ),
                'payOp'   => $this->op,
            )
        );
    }

    public function redirect($url)
    {
        if ($this->isAjax()) {
            throw new SLN_Action_Ajax_RedirectException($url);
        } else {
            wp_redirect($url);
        }
    }

    public function isAjax()
    {
        return defined( 'DOING_AJAX' ) && DOING_AJAX;
    }
}
