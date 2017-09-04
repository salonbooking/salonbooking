<?php

class SLN_Action_Ajax_ApplyDiscountCode extends SLN_Action_Ajax_Abstract
{
	protected $date;
	protected $time;
	protected $errors = array();

	public function execute()
	{
		$plugin = $this->plugin;
		$code   = $_POST['sln']['discount'];

		$criteria = array(
			'@wp_query' => array(
				'meta_query' => array(
					array(
						'key'   => '_' . SLB_Discount_Plugin::POST_TYPE_DISCOUNT . '_code',
						'value' => $code,
					),
					array(
						'key'   => '_' . SLB_Discount_Plugin::POST_TYPE_DISCOUNT . '_type',
						'value' => SLB_Discount_Enum_DiscountType::DISCOUNT_CODE,
					),
				),
			)
		);
		$discounts = $plugin->getRepository(SLB_Discount_Plugin::POST_TYPE_DISCOUNT)->get($criteria);

		if (!empty($discounts)) {
			/** @var SLB_Discount_Wrapper_Discount $discount */
			$discount = reset($discounts);
			$bb       = $plugin->getBookingBuilder();

			$errors   = $discount->validateDiscountFullForBB($bb);
			if (empty($errors)) {
				$discountValue = $discount->applyDiscountToBookingServices($bb->getBookingServices());
				$bb->set('discount', array('id' => $discount->getId(), 'amount' => $discountValue));
				$bb->save();
			}
			else {
				$this->addError(reset($errors));
			}
		}
		else {
			$this->addError(__('Coupon is not valid', 'salon-booking-system'));
		}

		if ($errors = $this->getErrors()) {
			$ret = compact('errors');
		} else {
			$ret = array(
				'success'  => 1,
				'discount' => $plugin->format()->money($discountValue, false, false, true),
				'total'    => $plugin->format()->money($bb->getTotal(), false, false, true),
				'errors'   => array(
					__('Coupon was applied', 'salon-booking-system')
				)
			);
		}

		return $ret;
	}

	protected function addError($err)
	{
		$this->errors[] = $err;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}