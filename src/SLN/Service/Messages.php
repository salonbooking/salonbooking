<?php

class SLN_Service_Messages
{
    private $plugin;
    private $disabled = false;

    private static $statusForSummary = array(
        SLN_Enum_BookingStatus::PAID,
        SLN_Enum_BookingStatus::PAY_LATER,
    );

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function setDisabled($bool)
    {
        $this->disabled = $bool;
    }

    public function sendByStatus(SLN_Wrapper_Booking $booking, $status)
    {
        if ($this->disabled) {
            return;
        }
        $p = $this->plugin;
        if ($status == SLN_Enum_BookingStatus::CONFIRMED) {
            if ($this->plugin->getSettings()->get('confirmation')) {
                $p->sendMail('mail/status_confirmed', compact('booking'));
            } else {
                $this->sendSummaryMail($booking);
            }
            $this->sendSmsBooking($booking);
        } elseif ($status == SLN_Enum_BookingStatus::CANCELED) {
            $p->sendMail('mail/status_canceled', compact('booking'));
        } elseif ($status == SLN_Enum_BookingStatus::PENDING_PAYMENT) {
            $p->sendMail('mail/status_pending_payment', compact('booking'));
        } elseif (in_array($status, self::$statusForSummary)) {
            $this->sendSummaryMail($booking);
            $this->sendSmsBooking($booking);
        }
    }

    public function sendSmsBooking($booking)
    {
        $p = $this->plugin;
        $sms = $p->sms();
        $s = $p->getSettings();
        if ($s->get('sms_new')) {
            $phone = $s->get('sms_new_number');
            if ($phone) {
                $sms->send($phone, $p->loadView('sms/summary', compact('booking')));
            }
            $phone = $booking->getPhone();
            if ($phone) {
                $sms->send($phone, $p->loadView('sms/summary', compact('booking')));
            }
        }
        if ($s->get('sms_new_attendant') && $booking->getAttendant()) {
            $phone = $booking->getAttendant()->getPhone();
            if ($phone) {
                $sms->send($phone, $p->loadView('sms/summary', compact('booking')));
            }
        }
    }


    public function sendSummaryMail($booking)
    {
        $p = $this->plugin;
        $p->sendMail('mail/summary', compact('booking'));
        $p->sendMail('mail/summary_admin', compact('booking'));
    }

    public function sendUpdatedMail($booking)
    {
        $p = $this->plugin;
        $args = compact('booking');
        $args['updated'] = true;
        $p->sendMail('mail/summary', $args);
        
		// send email only if we must notify attendants
		if( $p->getSettings()->get('attendant_email') )
			$p->sendMail('mail/summary_admin', $args);
    }
}