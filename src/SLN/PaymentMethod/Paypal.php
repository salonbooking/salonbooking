<?php

class SLN_PaymentMethod_Paypal extends SLN_PaymentMethod_Abstract
{
   
    public function getFields(){
        return array(
            'pay_paypal_email',
            'pay_paypal_test'
        );
    }

    public function dispatchThankYou(SLN_Shortcode_Salon_ThankyouStep $shortcode, SLN_Wrapper_Booking $booking = null){
        if (isset($_GET['op'])) {
            $op = explode('-', $_GET['op']);
            $action = $op[0];
            if ($action == 'success') {
                if ($this->isTest()) {
                    $booking->markPaid('test');
                }
                $shortcode->goToThankyou();
            } elseif ($action == 'notify') {
                $this->processIpn($op[1]);
            } elseif ($action == 'cancel') {
                return __('Your payment has not been completed', 'salon-booking-system');
            } else {
                throw new Exception('payment method operation not managed');
            }
        } elseif ($_GET['mode'] == 'paypal') {
            if ($shortcode->isAjax()) {
                    $bookUrl = str_replace(site_url(), '',get_permalink($this->plugin->getSettings()->get('pay')));
                    $_SERVER['REQUEST_URI'] = $bookUrl.'?sln_step_page=thankyou&submit_thankyou=1&mode=paypal';
            }
            $ppl = new SLN_Payment_Paypal($this->plugin);
            $url = $ppl->getUrl($booking->getId(), $booking->getToPayAmount(false), $booking->getTitle());
            $shortcode->redirect($url);
        } else {
            throw new Exception('payment method mode not managed');
        }
    }

    private function processIpn($id){
        $booking = $this->plugin->createBooking($id);
        $ppl = new SLN_Payment_Paypal($this->plugin);
        update_post_meta($booking->getId(), '_sln_paypal_ipn_' . uniqid(), $_POST);
        ob_end_clean();
        if ($ppl->reverseCheckIpn() && $ppl->isCompleted($booking->getToPayAmount(false))) {
            $booking->markPaid($ppl->getTransactionId());
            echo('ipn success');
        } else {
            echo('ipn_failed');
        }
    }

    private function isTest(){
        return $this->plugin->getSettings()->isPaypalTest();
    }
}
