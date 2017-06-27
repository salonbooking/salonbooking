<?php

class SLN_PaymentMethod_Stripe extends SLN_PaymentMethod_Paypal//Abstract
{
    private static $zeroDecimal = array(
        'JPY','VND','XOF','VUV','GNF','KRV','DJF','RWF','KMF','CLP','XPF','XAF','BIF','MGA'
    );

    public static function isZeroDecimal($currency){
        return in_array($currency, self::$zeroDecimal);
    }

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
        $currency = $this->plugin->getSettings()->getCurrency();
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => intval($booking->getToPayAmount(false)*(self::isZeroDecimal($currency) ? 1 : 100)),
                "currency" => $currency,
                "card" => $token
            ));

            return $charge['id'];
        } catch (Exception $e) {
            SLN_Plugin::addLog('stripe error: '.$e->getMessage()); 
            $this->setError(__('Payment failed, please try again', 'salon-booking-system'));            

            return false;
        }
    }


    public function dispatchThankYou(SLN_Shortcode_Salon_ThankyouStep $shortcode, SLN_Wrapper_Booking $booking = null){
        if ($_GET['mode'] == $this->getMethodKey()) {
            if(!isset($_POST['stripeToken'])){
                return;
            }
            if($id = $this->charge($booking, $_POST['stripeToken'])){
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
