<?php

class SLN_PaymentMethod_Stripe extends SLN_PaymentMethod_Paypal//Abstract
{

    public function getFields(){
        return array(
            'pay_stripe_apiKey',
            'pay_stripe_apiKeyPublic'
        );
    }

    public function charge($booking, $token){
        require_once __DIR__.'/_stripe/init.php'; 

        \Stripe\Stripe::setApiKey($this->plugin->getSettings()->get('pay_stripe_apiKey'));
        $error = '';
        $success = '';
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => intval($booking->getToPayAmount()*100),
                "currency" => $this->plugin->getSettings()->getCurrency(),
                "card" => $token
            ));

            return $charge['id'];
        } catch (Exception $e) {
            SLN_Plugin::addLog('stripe error: '.$e->getMessage()); 
            $this->setError(__('Payment failed, please try again', 'sln'));            

            return false;
        }
    }


    public function dispatchThankYou(SLN_Shortcode_Salon_ThankyouStep $shortcode, $booking = null){
        if ($_GET['mode'] == $this->getMethodKey()) {
            if(!isset($_GET['stripeToken'])){
                return;
            }
            if($id = $this->charge($booking, $_GET['stripeToken'])){
                $booking->markPaid($id);
                $shortcode->goToThankyou();
            }else{
                return $this->getError();
            }
        } else {
            throw new Exception('payment method mode not managed');
        }
    }

    public function getApiKeyPublic(){
        return $this->plugin->getSettings()->get('pay_stripe_apiKeyPublic');
    }
}
