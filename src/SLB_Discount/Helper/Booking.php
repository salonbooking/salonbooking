<?php

class SLB_Discount_Helper_Booking
{
	/**
	 * @param SLN_Wrapper_Booking $booking
	 *
	 * @return array
	 */
	public static function getBookingDiscountIds($booking)
	{
		$meta = $booking->getMeta('discounts');
		if (!is_array($meta)) {
			$meta = array();
		}

		return $meta;
	}

	/**
	 * @param SLN_Wrapper_Booking $booking
	 *
	 * @return SLB_Discount_Wrapper_Discount[]
	 */
	public static function getBookingDiscounts($booking) {
		$meta = self::getBookingDiscountIds($booking);

		$discounts = array();
		foreach ($meta as $discountId) {
			$discounts[$discountId] = new SLB_Discount_Wrapper_Discount($discountId);
		}

		return $discounts;
	}

	/**
	 * @param SLN_Wrapper_Booking $booking
	 *
	 * @return bool
	 */
	public static function hasAppliedDiscount($booking) {
		$meta = self::getBookingDiscountIds($booking);
		return !empty($meta);
	}
}