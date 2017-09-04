<?php

class SLB_Discount_Enum_DiscountType
{
	const DISCOUNT_CODE = 'sln-d-coupon';
	const DISCOUNT_AUTO = 'sln-d-auto';

	private static $labels;

	public static function toArray()
	{
		return self::$labels;
	}

	public static function getLabel($key)
	{
		return isset(self::$labels[$key]) ? self::$labels[$key] : self::$labels[self::getDefaultType()];
	}

	public static function getDefaultType() {
		return self::DISCOUNT_CODE;
	}

	public static function init()
	{
		self::$labels = array(
			self::DISCOUNT_CODE => __('Coupon code', 'salon-booking-system'),
			self::DISCOUNT_AUTO => __('Automatic discount', 'salon-booking-system'),
		);
	}
}

SLB_Discount_Enum_DiscountType::init();