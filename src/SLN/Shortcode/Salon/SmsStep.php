<?php

class SLN_Shortcode_Salon_SmsStep extends SLN_Shortcode_Salon_AbstractUserStep
{
    public function render()
    {
        if (!isset($_SESSION['sln_sms_tests'])) {
            $_SESSION['sln_sms_tests'] = 0;
        }
        $tests = intval($_SESSION['sln_sms_tests']);
        $valid = isset($_SESSION['sln_sms_valid']) ? $_SESSION['sln_sms_valid'] : false;

        if (!$valid) {
            if (!isset($_POST['sln_verification'])) {
                $values = isset($_SESSION['sln_detail_step']) ? $_SESSION['sln_detail_step'] : array();
                if (isset($values['phone'])) {
                    $_SESSION['sln_sms_tests']++;
                    $_SESSION['sln_sms_code'] = rand(0, 999999);
                    try {
                        $this->sendSms($values['phone'], $_SESSION['sln_sms_code']);
                    } catch (Exception $e) {
                        $this->addError($e->getMessage());
                    }
                } else {
                    $this->addError(
                        __(
                            'Phone number wrong or not defined, you need to define a valid phone number',
                            'salon-booking-system'
                        )
                    );
                }
            }
        }

        return parent::render();
    }

    private function sendSms($phone, $code)
    {
        $p = $this->getPlugin();
        $sms = $p->sms();
        $sms->send($phone, $p->loadView('sms/verify', compact('code')));
        if ($sms->hasError()) {
            $this->addError($sms->getError());
        }
    }

    protected function dispatchForm()
    {
        $values = isset($_SESSION['sln_detail_step']) ? $_SESSION['sln_detail_step'] : array();
        $valid = isset($_SESSION['sln_sms_valid']) ? $_SESSION['sln_sms_valid'] : false;

        if (!$valid) {
            if (isset($_POST['sln_verification'])) {
                if ($_POST['sln_verification'] == $_SESSION['sln_sms_code']) {
                    $_SESSION['sln_sms_valid'] = true;
                    $this->successRegistration($values);

                    return true;
                } else {
                    $_SESSION['sln_sms_valid'] = false;
                    $this->addError(__('Your verification code is not valid', 'salon-booking-system'));

                    return false;
                }
            }
        }
    }
    public function isValid() {
        $check = isset($_SESSION['sln_sms_dontcheck']) && $_SESSION['sln_sms_dontcheck'];
        unset($_SESSION['sln_sms_dontcheck']);

        return $check || parent::isValid();
    }
}
