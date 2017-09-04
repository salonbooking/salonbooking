<?php

class SLB_Discount_Wrapper_Discount extends SLN_Wrapper_Abstract
{
    const _CLASS = 'SLB_Discount_Wrapper_Discount';

    public function getPostType()
    {
        return SLB_Discount_Plugin::POST_TYPE_DISCOUNT;
    }

    function getAmount()
    {
        $ret = $this->getMeta('amount');
        $ret = empty($ret) ? 0 : floatval($ret);

        return $ret;
    }

    function getAmountType()
    {
        $ret = $this->getMeta('amount_type');
        $ret = empty($ret) ? 'fixed' : $ret;

        return $ret;
    }

    /**
     * @return string
     */
    public function getAmountString()
    {
        $amount     = $this->getAmount();
        $amountType = $this->getAmountType();
        if ($amountType === 'fixed') {
            $amount = SLN_Plugin::getInstance()->format()->money($amount, false, true, true);
        }
        else {
            $amount = "{$amount}%";
        }

        return $amount;
    }

    function getUsagesLimit()
    {
        $ret = $this->getMeta('usages_limit');

        return $ret;
    }

	function isUnlimitedUsages() {
		$limit = $this->getUsagesLimit();

		return empty($limit);
	}

    function getTotalUsagesLimit()
    {
        $ret = $this->getMeta('usages_limit_total');

        return $ret;
    }

	function isUnlimitedTotalUsages() {
		$limit = $this->getTotalUsagesLimit();

		return empty($limit);
	}

    function getTotalUsagesNumber()
    {
        $ret = $this->getMeta('usages_total');
        $ret = empty($ret) ? 0 : intval($ret);

        return $ret;
    }

    function incrementTotalUsagesNumber()
    {
        $this->setMeta('usages_total', 1 + $this->getTotalUsagesNumber());
    }

    function decrementTotalUsagesNumber()
    {
        $count = (int) $this->getTotalUsagesNumber();
        $this->setMeta('usages_total', $count > 0 ? $count - 1 : 0);
    }

    /**
     * @param WP_User|int $customer
     *
     * @return int
     */
    function getUsagesNumber($customer)
    {
        $customer = new SLN_Wrapper_Customer($customer);

        $ret = $customer->getMeta("discount_{$this->getId()}");
        $ret = empty($ret) ? 0 : $ret;

        return $ret;
    }

    function incrementUsagesNumber($customer)
    {
        $customer = new SLN_Wrapper_Customer($customer);

        if (!$customer->isEmpty()) {
            $customer->setMeta("discount_{$this->getId()}", 1 + (int)$customer->getMeta("discount_{$this->getId()}"));

            return true;
        }

        return false;
    }

    function decrementUsagesNumber($customer)
    {
        $customer = new SLN_Wrapper_Customer($customer);

        if (!$customer->isEmpty()) {
            $count = (int)$customer->getMeta("discount_{$this->getId()}");
            $customer->setMeta("discount_{$this->getId()}", $count > 0 ? $count - 1 : 0);

            return true;
        }

        return false;
    }

	/**
     * @param string $timezone
     *
     * @return null|SLN_DateTime
     */
    function getStartsAt($timezone='')
    {
        $date = $this->getMeta('from');
        if (!empty($date)) {
            if($timezone)
                $date = new SLN_DateTime($date, new DateTimeZone($timezone) );
            else
                $date = new SLN_DateTime($date);
        }
        else {
            $date = null;
        }

        return $date;
    }

    /**
     * @param string $timezone
     *
     * @return null|SLN_DateTime
     */
    function getEndsAt($timezone='')
    {
        $date = $this->getMeta('to');
        if (!empty($date)) {
            if($timezone)
                $date = new SLN_DateTime($date, new DateTimeZone($timezone) );
            else
                $date = new SLN_DateTime($date);
        }
        else {
            $date = null;
        }

        return $date;
    }

	/**
     * @return array
     */
    function getServicesIds()
    {
        $ret = $this->getMeta('services');
        if (!is_array($ret)) {
            $ret = array();
        }

        return $ret;
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    function getServices()
    {
        $ret = array();
        foreach ($this->getServicesIds() as $id) {
            $tmp = new SLN_Wrapper_Service($id);
            if (!$tmp->isEmpty()) {
                $ret[] = $tmp;
            }
        }

        return $ret;
    }

    function getDiscountType()
    {
        $ret = $this->getMeta('type');
        $ret = empty($ret) ? SLB_Discount_Enum_DiscountType::getDefaultType() : $ret;

        return $ret;
    }

    function getCouponCode()
    {
        $ret = (string) $this->getMeta('code');

        return $ret;
    }

	/**
     * @return array
     */
    function getDiscountRules()
    {
        $ret = (array) $this->getMeta('rules');
        $ret = array_filter($ret);

        return $ret;
    }

    public function getName()
    {
        if ($this->object) {
            return $this->object->post_title;
        } else {
            return 'n.d.';
        }
    }

    public function __toString()
    {
        return $this->getName();
    }

    public static function generateCouponCode() {
        $code = random_int(1000, 9999);

        return $code;
    }

	/**
     * @param float $value
     *
     * @return float
     */
    private function calculateDiscount($value) {
        $amount     = $this->getAmount();
        $amountType = $this->getAmountType();

        if ($amountType === 'fixed') {
            $ret = $amount;
        }
        else {
            $ret = ($value/100)*$amount;
        }

        if ($ret > $value) {
            $ret = $value;
        }

        return $ret;
    }

    /**
     * @param SLN_Wrapper_Booking_Services $bookingServices
     *
     * @param bool $split Split discount sum by services
     *
     * @return array|float
     */
    public function applyDiscountToBookingServices($bookingServices, $split = false) {
        $ret = array(
            'total'    => 0.0,
            'services' => array()
        );

        $discountServices = $this->getServicesIds();
        if (empty($discountServices)) {
            $total = 0;
            $items = array();
            foreach ($bookingServices->getItems() as $bookingService) {
                $price = SLN_Func::filter($bookingService->getPrice(), 'float');
                $total = $total + $price;
                $items[$bookingService->getService()->getId()] = $price;
            }
            $dTotal       = $this->calculateDiscount($total);
            $ret['total'] = $dTotal;
            asort($items);

            $i = 1;
            foreach($items as $sId => $price) {
                if ($i !== $bookingServices->getCount()) { // not last
                    $dValue = $this->calculateDiscount($price);
                    $ret['services'][$sId] = $dValue;
                    $dTotal -= $dValue;
                }
                else {
                    $dValue = $dTotal;
                    $ret['services'][$sId] = $dValue;
                }
                $i++;
            }
        }
        else {
            foreach ($bookingServices->getItems() as $bookingService) {
                if (in_array($bookingService->getService()->getId(), $discountServices)) {
                    $ret['services'][$bookingService->getService()->getId()] = $this->calculateDiscount(SLN_Func::filter($bookingService->getPrice(), 'float'));
                }
                else {
                    $ret['services'][$bookingService->getService()->getId()] = 0.0;
                }
            }
            $ret['total'] = array_sum($ret['services']);
        }

        if ($split) {
            return $ret['services'];
        }
        else {
            return $ret['total'];
        }
    }

    /**
     * @param SLN_Wrapper_Booking_Builder $bb
     *
     * @return bool
     */
    public function isValidDiscountFullForBB($bb) {
        $errors = $this->validateDiscountFullForBB($bb);

        return empty($errors);
    }

    /**
     * @param SLN_Wrapper_Booking_Builder $bb
     *
     * @return array
     */
    public function validateDiscountFullForBB($bb) {
        $customer = new SLN_Wrapper_Customer(get_current_user_id());

        $bookingServices = $bb->getBookingServices();

        $first = $bookingServices->getFirstItem();
        $date  = $first->getStartsAt()->format('Y-m-d H:i:s');

        $errors = $this->validateDiscountFull($date, $bookingServices, $customer);

        return $errors;
    }

    /**
     * @param string $date
     * @param SLN_Wrapper_Booking_Services $bookingServices
     * @param SLN_Wrapper_Customer $customer
     *
     * @return bool
     */
    public function isValidDiscountFull($date, $bookingServices, $customer) {
        $errors = $this->validateDiscountFull($date, $bookingServices, $customer);

        return empty($errors);
    }

    /**
     * @param string $date
     * @param SLN_Wrapper_Booking_Services $bookingServices
     * @param SLN_Wrapper_Customer $customer
     *
     * @return array
     */
    public function validateDiscountFull($date, $bookingServices, $customer) {
        $errors = $this->validateDiscount($date);
        if (!empty($errors)) {
            return $errors;
        }

        $errors = $this->validateDiscountForBookingServices($bookingServices);
        if (!empty($errors)) {
            return $errors;
        }

        $errors = $this->validateDiscountForCustomer($customer);
        if (!empty($errors)) {
            return $errors;
        }

        $errors = $this->validateDiscountRules($date, $customer);
        if (!empty($errors)) {
            return $errors;
        }

        return $errors;
    }

	/**
     * @param string $date
     *
     * @return array
     */
    private function validateDiscount($date) {
        $dateT = strtotime($date);
        $ret   = array();

        if (!($dateT >= strtotime($this->getStartsAt()->format('Y-m-d').' 00:00:00') && $dateT <= strtotime($this->getEndsAt()->format('Y-m-d').' 23:59:59'))) {
            $ret[] = __('Coupon expired', 'salon-booking-system');
        }
        elseif(!$this->isUnlimitedTotalUsages() && $this->getTotalUsagesNumber() >= $this->getTotalUsagesLimit()) {
            $ret[] = __('This coupon was applied maximum number of times', 'salon-booking-system');
        }

        return $ret;
    }

    /**
     * @param SLN_Wrapper_Customer $customer
     *
     * @return array
     */
    private function validateDiscountForCustomer($customer) {
        $ret = array();
        if (!$this->isUnlimitedUsages() && $this->getUsagesNumber($customer->getId()) >= $this->getUsagesLimit()) {
            $ret[] = __('You applied this coupon maximum number of times', 'salon-booking-system');
        }
        return $ret;
    }

    /**
     * @param SLN_Wrapper_Booking_Services $bookingServices
     *
     * @return array
     */
    private function validateDiscountForBookingServices($bookingServices) {
        $ret = array();

        $discountServices = $this->getServicesIds();
        if (empty($discountServices)) {
            return $ret;
        }

        $services = array();
        foreach($bookingServices->getItems() as $bookingService) {
            $services[] = $bookingService->getService()->getId();
        }

        $intersect = array_intersect($services, $discountServices);
        if (empty($intersect)) {
            $ret[] = __('This coupon is not valid for selected services', 'salon-booking-system');
        }
        return $ret;
    }

    /**
     * @param string $date
     * @param SLN_Wrapper_Customer $customer
     *
     * @return array
     */
    private function validateDiscountRules($date, $customer) {
        $ret = array();

        if ( $this->getDiscountType() === SLB_Discount_Enum_DiscountType::DISCOUNT_CODE) {
            return $ret;
        }
        $dateT = strtotime($date);

        $rules = $this->getDiscountRules();
        if (!empty($rules)) {
            foreach($rules as $rule) {
                if ($rule['mode'] === 'daterange') {
                    if (!($dateT >= strtotime($rule['daterange_from'].' 00:00:00') && $dateT <= strtotime($rule['daterange_to'].' 23:59:59'))) {
                        $ret[] = __('Coupon expired', 'salon-booking-system');
                        break;
                    }
                }
                elseif ($rule['mode'] === 'weekdays') {
                    if (!in_array((int) date('w', $dateT), $rule['weekdays'])) {
                        $ret[] = sprintf(__('This coupon is not valid on %s', 'salon-booking-system'), SLN_Enum_DaysOfWeek::getLabel((int) date('w', $dateT)));
                        break;
                    }
                }
                elseif ($rule['mode'] === 'bookings' || $rule['mode'] === 'amount') {
                    $criteria = array(
                        '@wp_query' => array(
                            'post_author' => $customer->getId()
                        )
                    );
                    $bookings = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_BOOKING)->get($criteria);

                    if ($rule['mode'] === 'bookings') {
                        if (count($bookings) < ((int) $rule['bookings_number'])) {
                            $ret[] = __('Make more bookings to be able to use this coupon', 'salon-booking-system');
                            break;
                        }
                    }
                    else {
                        $total = 0.0;
                        /** @var SLN_Wrapper_Booking $booking */
                        foreach($bookings as $booking) {
                            $total += $booking->getAmount();
                        }

                        if ($total < ((float) $rule['amount_number'])) {
                            $ret[] = __('Make more bookings to be able to use this coupon', 'salon-booking-system');
                            break;
                        }
                    }
                }
            }
        }

        return $ret;
    }
}