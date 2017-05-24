<?php

class SLN_Enum_PaymentDepositType
{
    const _DEFAULT = '0';
    const FIXED    = 'fixed';
    private static $labels;

    public static function toArray()
    {
        return self::$labels;
    }

	public static function getLabel($key)
	{
		return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::_DEFAULT];
	}

    public static function init()
    {
        self::$labels = array(
	        self::_DEFAULT => __('entire amount (disabled)', 'salon-booking-system'),
	        self::FIXED    => __('fixed', 'salon-booking-system'),
	        '10'           => '10%',
	        '20'           => '20%',
	        '30'           => '30%',
	        '40'           => '40%',
	        '50'           => '50%',
	        '60'           => '60%',
	        '70'           => '70%',
	        '80'           => '80%',
	        '90'           => '90%',
        );
    }
}

SLN_Enum_PaymentDepositType::init();