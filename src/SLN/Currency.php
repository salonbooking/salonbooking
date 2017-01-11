<?php

class SLN_Currency
{
    private static $currencies = array(
        'AUD' => array('name' => 'Australian Dollar', 'symbol' => 'A$', 'ASCII' => 'A&#36;'),
        'UAH' => array('name' => 'Ukrainian Hryvnia', 'symbol' => '₴', 'ASCII' => '&#8372;'),
        'BRL' => array('name' => 'Brazilian Real', 'symbol' => 'R$', 'ASCII' => 'B&#36;'),
        'CAD' => array('name' => 'Canadian Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'CZK' => array('name' => 'Czech Koruna', 'symbol' => 'Kč', 'ASCII' => ''),
        'DKK' => array('name' => 'Danish Krone', 'symbol' => 'Kr', 'ASCII' => ''),
        'CNY' => array('name' => 'Chinese Yuan Renminbi', 'symbol'=>'¥', 'hex'=>'&#xa5;'),
        'EUR' => array('name' => 'Euro', 'symbol' => '€', 'ASCII' => '&#8364;'),
        'HKD' => array('name' => 'Hong Kong Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'HUF' => array('name' => 'Hungarian Forint', 'symbol' => 'Ft', 'ASCII' => ''),
        'ILS' => array('name' => 'Israeli New Sheqel', 'symbol' => '₪', 'ASCII' => '&#8361;'),
        'INR' => array('name' => 'Indian Rupee', 'symbol' => '₹', 'ASCII' => '&#8377;'),
        'IDR' => array('name' => 'Indonesian Rupee', 'symbol' => 'Rp', 'ASCII' => ''),
        'JPY' => array('name' => 'Japanese Yen', 'symbol' => '¥', 'ASCII' => '&#165;'),
        'KES' => array('name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'ASCII' => ''),
        'MXN' => array('name' => 'Mexican Peso', 'symbol' => '$', 'ASCII' => '&#36;'),
        'MYR' => array('name' => 'Malaysian Ringgit', 'symbol' => 'RM', 'ASCII' => ''),
        'MAD' => array('name' => 'Moroccan dirham', 'symbol' => '.د.م', 'ASCII' => ''),
        'NOK' => array('name' => 'Norwegian Krone', 'symbol' => 'Kr', 'ASCII' => ''),
        'NZD' => array('name' => 'New Zealand Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'NGN' => array('name' => 'Nigerian Naira', 'symbol' => '₦', 'ASCII' => '&#8358;'),
        'PEN' => array('name' => 'Peruvian Nuevo Sol', 'symbol'=>'S/.', 'hex'=>''),
        'PKR' => array('name' => 'Pakistan Rupee', 'symbol'=>'₨', 'hex'=>'&#x20a8;'),
        'PHP' => array('name' => 'Philippine Peso', 'symbol' => '₱', 'ASCII' => ''),
        'PLN' => array('name' => 'Polish Zloty', 'symbol' => 'zł', 'ASCII' => ''),
        'RON'=>array('name' => 'Romanian New Lei', 'symbol'=>'lei', 'hex'=>'&#x6c;&#x65;&#x69;'),
        'GBP' => array('name' => 'Pound Sterling', 'symbol' => '£', 'ASCII' => '&#163;'),
        'SGD' => array('name' => 'Singapore Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'SEK' => array('name' => 'Swedish Krona', 'symbol' => 'kr', 'ASCII' => ''),
        'CHF' => array('name' => 'Swiss Franc', 'symbol' => 'CHF', 'ASCII' => ''),
        'ZAR' => array('name' => 'South African rand', 'symbol' => 'R', 'ASCII' => '&#x52;'),
        'KRW' => array('name' => 'South Korea Won', 'symbol' => '￦', 'ASCII' => '&#8361;'),
        'KWD' => array('name' => 'Kuwaiti dinar', 'symbol' => 'KD', 'ASCII' => ''),
        'TWD' => array('name' => 'Taiwan New Dollar', 'symbol' => 'NT$', 'ASCII' => 'NT&#36;'),
        'TRY' => array('name' => 'Turkish Lira', 'symbol' => 'TL', 'ASCII' => '&#8378;'),
        'THB' => array('name' => 'Thai Baht', 'symbol' => '฿', 'ASCII' => '&#3647;'),
        'TND' => array('name' => 'Tunisinian dinar', 'symbol' => 'DT', 'ASCII' => ''),
        'USD' => array('name' => 'U.S. Dollar', 'symbol' => '$', 'ASCII' => '&#36;'),
        'LKR' => array('name' => 'Sri Lankan Rupee', 'symbol' => '₹', 'ASCII' => '&#8377;'),
        'RUB' => array('name' => 'Russian Ruble', 'symbol' => '₽', 'ASCII' => '&#8381;'),
    );

    public static function getSymbol($code = 'USD')
    {
        if (!empty(self::$currencies[$code]['ASCII'])) {
            return (string)self::$currencies[$code]['ASCII'];
        }

        return (string)self::$currencies[$code]['symbol'];
    }

    public static function getSymbolAsIs($code = 'USD')
    {
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
