<?php

define('PPL_URL','https://sandbox.paypal.com/cgi-bin/webscr');

function ppl_reverseCheckIpn()
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

function ppl_addUrlParam($url, $k, $v)
{
    return $url . (strpos($url, '?')===false ? '?' : '&') . http_build_query(array($k => $v));
}

function ppl_paypalUrl()
{
    echo PPL_URL."?"
        . http_build_query(
            array(
                'notify_url'    => ppl_addUrlParam(ppl_curPageUrl(), 'op', 'notify'),
                'return' => ppl_addUrlParam(ppl_curPageUrl(), 'op', 'success'),
                'cancel_return' => ppl_addUrlParam(ppl_curPageUrl(), 'op', 'cancel'),
                'cmd'           => '_xclick',
                'business'      => 'orders@plugins.wordpresschef.it',
                'currency_code' => 'EUR',
                'amount'        => types_render_field("net-price", array()),
                'item_name'     => get_the_title()
            )
        );
}

function ppl_curPageUrl()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }

    return $pageURL;
}

function ppl_isPaymentCompleted(){
    return floatval($_POST['mc_gross']) == floatval(types_render_field("net-price", array())) && $_POST['payment_status'] == 'Completed';
}
