<?php

class SLN_Payment_Paypal
{
    const TEST_URL = 'https://sandbox.paypal.com/cgi-bin/webscr';
    const PROD_URL = 'https://www.paypal.com/cgi-bin/webscr';
    protected $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    function reverseCheckIpn()
    {
        $raw_post_data  = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost         = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }


        $ch = curl_init(PPL_URL);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        if (!($res = curl_exec($ch))) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);


        return (strcmp($res, "VERIFIED") == 0);
    }


    public function getUrl($amount, $title)
    {
        $settings = $this->plugin->getSettings();
        echo $this->getBaseUrl($this->plugin->isPaypalTest()) . "?"
            . http_build_query(
                array(
                    'notify_url'    => SLN_Func::addUrlParam(SLN_Func::currPageUrl(), 'op', 'notify'),
                    'return'        => SLN_Func::addUrlParam(SLN_Func::currPageUrl(), 'op', 'success'),
                    'cancel_return' => SLN_Func::addUrlParam(SLN_Func::currPageUrl(), 'op', 'cancel'),
                    'cmd'           => '_xclick',
                    'business'      => $settings->getPaypalEmail(),
                    'currency_code' => $settings->getPaypalCurrency(),
                    'amount'        => $amount,
                    'item_name'     => $title
                )
            );
    }

    private function getBaseUrl($isTest)
    {
        return $isTest ?
            self::TEST_URL : self::PROD_URL;
    }

    function ppl_isPaymentCompleted()
    {
        return floatval($_POST['mc_gross']) == floatval(
            types_render_field("net-price", array())
        ) && $_POST['payment_status'] == 'Completed';
    }
}