<?php

class SLN_Shortcode_Saloon_ThankyouStep extends SLN_Shortcode_Saloon_Step
{
    protected function dispatchForm()
    {
        $bb      = $this->getPlugin()->getBookingBuilder();
        $booking = $bb->getLastBooking();
        if ($_GET['mode'] == 'paypal') {
            $ppl = new SLN_Payment_Paypal($this->getPlugin());
            $url = $ppl->getUrl($booking->getId(), $booking->getAmount(), $booking->getTitle());
            wp_redirect($url);
        } elseif ($_GET['mode'] == 'later') {
            $bb->getLastBooking()->setStatus(SLN_Enum_BookingStatus::PAY_LATER);
            $this->goToThankyou();
        }

        return true;
    }

    public function goToThankyou(){
        $id = $this->getPlugin()->getSettings()->getThankyouPageId();
        if ($id) {
            wp_redirect(get_permalink($id));
        }
    }

    public function getViewData()
    {
        $ret        = parent::getViewData();
        $formAction = $ret['formAction'];

        return array_merge(
            $ret,
            array(
                'booking'   => $this->getPlugin()->getBookingBuilder()->getLastBooking(),
                'laterUrl'  => add_query_arg(array('mode' => 'later', 'submit_' . $this->getStep() => 1), $formAction),
                'paypalUrl' => add_query_arg(array('mode' => 'paypal', 'submit_' . $this->getStep() => 1), $formAction)
            )
        );
    }
}