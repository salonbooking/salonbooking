<?php

class SLN_Shortcode_Salon_ThankyouStep extends SLN_Shortcode_Salon_Step
{
    private $op;

    protected function dispatchForm()
    {
        $bb      = $this->getPlugin()->getBookingBuilder();
        $booking = $bb->getLastBooking();
        if ($_GET['op']) {
            $op       = explode('-', $_GET['op']);
            $this->op = $op[0];
            if ($this->op == 'success') {
                $this->goToThankyou();
            } elseif ($this->op == 'ipn') {
                $booking = $this->getPlugin()->createBooking($op[1]);
                update_post_meta($booking->getId(), '_sln_paypal_ipn_' . uniqid(), $_POST);
                $ppl = new SLN_Payment_Paypal($this->getPlugin());
                if ($ppl->reverseCheckIpn() && $ppl->isCompleted($booking->getAmount())) {
                    $booking->setStatus(SLN_Enum_BookingStatus::PAY_LATER);
                    $this->getPlugin()->sendMail('mail/payment_confirmed',compact('booking'));
                }
            }
        } elseif ($_GET['mode'] == 'paypal') {
            $ppl = new SLN_Payment_Paypal($this->getPlugin());
            $url = $ppl->getUrl($booking->getId(), $booking->getAmount(), $booking->getTitle());
            wp_redirect($url);
        } elseif ($_GET['mode'] == 'later') {
            $bb->getLastBooking()->setStatus(SLN_Enum_BookingStatus::PAY_LATER);
            $this->goToThankyou();
        }


        return false;
    }

    public function goToThankyou()
    {
        $id = $this->getPlugin()->getSettings()->getThankyouPageId();
        if ($id) {
            wp_redirect(get_permalink($id));
        }
    }

    public function getViewData()
    {
        $ret        = parent::getViewData();
        $formAction = $ret['formAction'];
        $formAction = remove_query_arg('op', $formAction);

        return array_merge(
            $ret,
            array(
                'formAction' => $formAction,
                'booking'    => $this->getPlugin()->getBookingBuilder()->getLastBooking(),
                'laterUrl'   => add_query_arg(
                    array('mode' => 'later', 'submit_' . $this->getStep() => 1),
                    $formAction
                ),
                'paypalUrl'  => add_query_arg(
                    array('mode' => 'paypal', 'submit_' . $this->getStep() => 1),
                    $formAction
                ),
                'paypalOp'   => $this->op
            )
        );
    }
}
