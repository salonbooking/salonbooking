<?php

class SLN_Currency
{
    private static $currencies = array(
        'AUD' => array('name' => 'Australian Dollar', 'symbol' => 'A$', 'ASCII' => 'A&#36;'),
        'BRL' => array('name' => 'Brazilian Real', 'symbol' => 'R$', 'ASCII' => 'B&#36;'),
        'CAD' => array('name' => 'Canadian Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'CZK' => array('name' => 'Czech Koruna', 'symbol' => 'Kč', 'ASCII' => ''),
        'DKK' => array('name' => 'Danish Krone', 'symbol' => 'Kr', 'ASCII' => ''),
        'CNY' => array('name' => 'Chinese Yuan Renminbi', 'symbol'=>'¥', 'hex'=>'&#xa5;'),
        'EUR' => array('name' => 'Euro', 'symbol' => '€', 'ASCII' => '&#8364;'),
        'HKD' => array('name' => 'Hong Kong Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'HUF' => array('name' => 'Hungarian Forint', 'symbol' => 'Ft', 'ASCII' => ''),
        'ILS' => array('name' => 'Israeli New Sheqel', 'symbol' => '₪', 'ASCII' => '&#8361;'),
        'JPY' => array('name' => 'Japanese Yen', 'symbol' => '¥', 'ASCII' => '&#165;'),
        'MXN' => array('name' => 'Mexican Peso', 'symbol' => '$', 'ASCII' => '&#36;'),
        'MAD' => array('name' => 'Moroccan dirham', 'symbol' => '.د.م', 'ASCII' => ''),
        'NOK' => array('name' => 'Norwegian Krone', 'symbol' => 'Kr', 'ASCII' => ''),
        'NZD' => array('name' => 'New Zealand Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'PHP' => array('name' => 'Philippine Peso', 'symbol' => '₱', 'ASCII' => ''),
        'PLN' => array('name' => 'Polish Zloty', 'symbol' => 'zł', 'ASCII' => ''),
        'GBP' => array('name' => 'Pound Sterling', 'symbol' => '£', 'ASCII' => '&#163;'),
        'SGD' => array('name' => 'Singapore Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'SEK' => array('name' => 'Swedish Krona', 'symbol' => 'kr', 'ASCII' => ''),
        'CHF' => array('name' => 'Swiss Franc', 'symbol' => 'CHF', 'ASCII' => ''),
        'ZAR' => array('name' => 'South African rand', 'symbol' => 'R', 'ASCII' => '&#x52;'),
        'TWD' => array('name' => 'Taiwan New Dollar', 'symbol' => 'NT$', 'ASCII' => 'NT&#36;'),
        'THB' => array('name' => 'Thai Baht', 'symbol' => '฿', 'ASCII' => '&#3647;'),
        'USD' => array('name' => 'U.S. Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'LKR' => array('name' => 'Sri Lankan Rupee', 'symbol' => '₹', 'ASCII' => '&#8377;'),
        'INR' => array('name' => 'Indian Rupee', 'symbol' => '₹', 'ASCII' => '&#8377;')
    );

    public static function getSymbol($code = 'USD')
    {
        if (!empty(self::$currencies[$code]['ASCII'])) {
            return (string)self::$currencies[$code]['ASCII'];
        }

        return (string)self::$currencies[$code]['symbol'];
    }

    public static function toArray()
    {
        $ret = array();
        foreach (array_keys(self::$currencies) as $k) {
            $ret[$k] = $k . ' (' . self::getSymbol($k) . ')';
        }

        return $ret;
    }
}