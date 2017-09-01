<?php

class SLB_Discount_Helper_DiscountItems
{
    /**
     * @var SLB_Discount_Wrapper_Discount[]
     */
    private $items = array();

    public function __construct($items)
    {
        if (empty($items)) {
            return;
        }

        $this->items = $items;
    }

    /**
     * @param SLN_Wrapper_Booking_Builder $bb
     *
     * @return false|SLB_Discount_Wrapper_Discount
     */
    public function getDiscountForBB($bb) {
        $customer = new SLN_Wrapper_Customer(get_current_user_id());

        $bookingServices = $bb->getBookingServices();

        $first = $bookingServices->getFirstItem();
        $date  = $first->getStartsAt()->format('Y-m-d H:i:s');

        return $this->getDiscountForBooking($date, $bookingServices, $customer);
    }

    /**
     * @param string $date
     * @param SLN_Wrapper_Booking_Services $bookingServices
     * @param SLN_Wrapper_Customer $customer
     *
     * @return false|SLB_Discount_Wrapper_Discount
     */
    public function getDiscountForBooking($date, $bookingServices, $customer) {
        if (!$bookingServices->getCount()) {
            return false;
        }

        $items = $this->items;

        usort($items, array($this, 'cmpByDiscountPostDate'));

        foreach($items as $i => $item) {
            if ( $item->getDiscountType() === SLB_Discount_Enum_DiscountType::DISCOUNT_CODE) {
                $valid = false;
            }
            else {
                $valid = $item->isValidDiscountFull($date, $bookingServices, $customer);
            }

            if (!$valid) {
                unset($items[$i]);
            }
        }

        $ret = end($items);

        return $ret;
    }


    /**
     * @return SLB_Discount_Wrapper_Discount[]
     */
    public function toArray()
    {
        return $this->items;
    }

	/**
     * @param null|SLB_Discount_Wrapper_Discount[] $discounts
     *
     * @return SLB_Discount_Helper_DiscountItems
     */
    public static function buildDiscountItems($discounts = null) {
        if (is_null($discounts)) {
            $discounts = SLN_Plugin::getInstance()->getRepository(SLB_Discount_Plugin::POST_TYPE_DISCOUNT)->getAll();
        }
        return new self($discounts);
    }

    /**
     * @param SLB_Discount_Wrapper_Discount $a
     * @param SLB_Discount_Wrapper_Discount $b
     *
     * @return int
     */
    public static function cmpByDiscountPostDate($a, $b) {
        $timestampA = strtotime($a->getPostDate());
        $timestampB = strtotime($b->getPostDate());
        if ($timestampA > $timestampB) {
            return 1;
        }
        else {
            return -1;
        }
    }

    public static function processSubmission($data = null)
    {
        if(!$data) return $data;
        $data = array_values($data);
        foreach($data as &$rule) {
            $rule['daterange_from'] = SLN_TimeFunc::evalPickedDate($rule['daterange_from']);
            $rule['daterange_to']   = SLN_TimeFunc::evalPickedDate($rule['daterange_to']);
            if (!isset($rule['weekdays'])) {
                $rule['weekdays'] = array();
            }
        }

        return $data;
    }
}