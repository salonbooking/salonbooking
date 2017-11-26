<?php

class SLN_Wrapper_Booking extends SLN_Wrapper_Abstract
{
    private $bookingServices;
    private $attendants;

    const _CLASS = 'SLN_Wrapper_Booking';

    public function getPostType()
    {
        return SLN_Plugin::POST_TYPE_BOOKING;
    }

    function getAmount()
    {
        $ret = $this->getMeta('amount');
        $ret = empty($ret) ? 0 : floatval($ret);

        return $ret;
    }

    function getDeposit()
    {
        $ret = $this->getMeta('deposit');
        $ret = empty($ret) ? 0 : floatval($ret);

        return $ret;
    }

    function getToPayAmount($format = true)
    {
        $ret = $this->getDeposit() > 0 ? $this->getDeposit() : $this->getAmount();

        return $format ? number_format($ret, 2) : $ret;
    }

    function getFirstname()
    {
        return $this->getMeta('firstname');
    }

    function getLastname()
    {
        return $this->getMeta('lastname');
    }

    function getDisplayName()
    {
        return $this->getFirstname().' '.$this->getLastname();
    }

    function getEmail()
    {
        return $this->getMeta('email');
    }

    function getPhone()
    {
        return $this->getMeta('phone');
    }

    function getAddress()
    {
        return $this->getMeta('address');
    }


    function getTime()
    {
        return new SLN_DateTime($this->getMeta('time'));
    }

    function getDate()
    {
        return new SLN_DateTime($this->getMeta('date'));
    }

    function getServicesMeta()
    {
        $data = $this->getMeta('services');
        $data = empty($data) ? array() : $data;

        return $data;
    }

    function getBookingServices()
    {
        if (!$this->bookingServices) {
            $this->maybeProcessBookingServices();
            $this->bookingServices = new SLN_Wrapper_Booking_Services($this->getServicesMeta());
        }

        return $this->bookingServices;
    }

    function maybeProcessBookingServices()
    {

        $servicesProcessed = $this->getMeta('services_processed');

        if (empty($servicesProcessed)) {
            $this->evalBookingServices();
        }
    }

    function evalBookingServices()
    {
        $data = $this->getServicesMeta();
        $this->bookingServices = SLN_Wrapper_Booking_Services::build($data, $this->getStartsAt());
        $ret = $this->bookingServices->toArrayRecursive();
        $this->setMeta('services', $ret);
        $this->setMeta('services_processed', 1);
    }

    function getDuration()
    {
        $ret = $this->getMeta('duration');
        if (empty($ret)) {
            $ret = '00:00';
        }
        $ret = SLN_Func::filter($ret, 'time');
        if ($ret == '00:00') {
            $ret = $this->evalDuration();
        }

        return new SLN_DateTime('1970-01-01 '.$ret);
    }

    function evalDuration()
    {
        $h = 0;
        $i = 0;
        SLN_Plugin::addLog(__CLASS__.' eval duration of'.$this->getId());
        foreach ($this->getBookingServices()->getItems() as $bookingService) {
            $d = $bookingService->getTotalDuration();
            $h = $h + intval($d->format('H'));
            $i = $i + intval($d->format('i'));
            SLN_Plugin::addLog(' - service '.$bookingService.' +'.$d->format('H:i'));
        }
        $i += $h * 60;
        if ($i == 0) {
            $i = 60;
        }
        $str = SLN_Func::convertToHoursMins($i);
        $this->setMeta('duration', $str);

        return $str;
    }

    function evalTotal()
    {
	    $settings = SLN_Plugin::getInstance()->getSettings();

        $amount = 0;
        SLN_Plugin::addLog(__CLASS__.' eval total of'.$this->getId());
        foreach ($this->getBookingServices()->getItems() as $bookingService) {
            $price   = $bookingService->getPrice();
            $amount += $price;
            SLN_Plugin::addLog(' - service '.$bookingService->getService().' +'.$price);
        }
        $this->setMeta('amount', $amount);

        $depositAmount = $settings->getPaymentDepositAmount();
        if ($settings->isPaymentDepositFixedAmount()) {
        	$deposit = min($amount, $depositAmount);
        }
        else {
	        $deposit = ($amount / 100) * $depositAmount;
        }

	    $this->setMeta('deposit', $deposit);

        return $amount;
    }


    function hasAttendant(SLN_Wrapper_Attendant $attendant)
    {
        return in_array($attendant->getId(), $this->getAttendantsIds());
    }

    function hasService(SLN_Wrapper_Service $service)
    {
        return in_array($service->getId(), $this->getServicesIds());
    }

    /**
     * @param bool|false $unique
     *
     * @return array
     */
    function getAttendantsIds($unique = false)
    {
        $post_id = $this->getId();
        $data = apply_filters('sln_booking_attendants', get_post_meta($post_id, '_sln_booking_services', true));
        $ret = array();
        if (is_array($data)) {
            foreach ($data as $item) {
                if($item['attendant'])
                    $ret[$item['service']] = $item['attendant'];
            }
        }

        return $unique ? array_unique($ret) : $ret;
    }

    /**
     * @return SLN_Wrapper_Attendant|false
     */
    public function getAttendant()
    {
        $ret = $this->getAttendants();
        return empty($ret) ? false : array_pop($ret);
    }

    /**
     * @param bool $unique
     *
     * @return SLN_Wrapper_Attendant[]
     */
    public function getAttendants($unique = false)
    {
        if (!$this->attendants) {
            $repo = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
            $this->attendants = array();
            $attIds = $this->getAttendantsIds($unique);
            foreach ($attIds as $service_id => $id) {
                if (!$id) {
                    continue;
                }
                /** @var SLN_Wrapper_Attendant $tmp */
                $tmp = $repo->create($id);
                if (!$tmp->isEmpty()) {
                    $this->attendants[$service_id] = $tmp;
                }
            }
        }

        return $this->attendants;
    }

    function getAttendantsString()
    {
        $attendants = $this->getAttendants(true);
        if (empty($attendants)) {
            return '';
        } else {
            $ret = array();
            foreach ($attendants as $attendant) {
                $ret[] = $attendant->getName();
            }

            return implode(', ', $ret);
        }
    }

    function getServicesIds()
    {
        $data = $this->getMeta('services');
        $ret = array();
        if (is_array($data)) {
            foreach ($data as $item) {
                $ret[] = $item['service'];
            }
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

    function getNote()
    {
        return $this->getMeta('note');
    }

    function getAdminNote()
    {
        return $this->getMeta('admin_note');
    }


    function getTransactionId()
    {
        return $this->getMeta('transaction_id');
    }

    function getStartsAt( $timezone='' )
    {
		if($timezone)
			return new SLN_DateTime($this->getDate()->format('Y-m-d').' '.$this->getTime()->format('H:i'), new DateTimeZone($timezone) );
		else	
			return new SLN_DateTime($this->getDate()->format('Y-m-d').' '.$this->getTime()->format('H:i'));
    }

    function getEndsAt( $timezone='' )
    {
        $start = $this->getStartsAt( $timezone );
        //SLN_Plugin::addLog($this->getId().' duration '.$this->getDuration()->format('H:i'));
        $minutes = SLN_Func::getMinutesFromDuration($this->getDuration());
        //SLN_Plugin::addLog($this->getId().' duration '.$minutes.' minutes');
        if ($minutes == 0) {
            $minutes = 60;
        }
        $start->modify('+'.$minutes.' minutes');

        return $start;
    }

    public function getUserId()
    {
        return $this->object->post_author;
    }

	/**
     * @return null|SLN_Wrapper_Customer
     */
    public function getCustomer()
    {
        $customer = new SLN_Wrapper_Customer($this->getUserId());
        if ($customer->isEmpty()) {
            $customer = null;
        }

        return $customer;
    }

    function isNew()
    {
        return strpos($this->object->post_status, 'sln-b-') !== 0;
    }

    public function markPaid($transactionId)
    {
        $this->setMeta('transaction_id', $transactionId);
        $this->setStatus(SLN_Enum_BookingStatus::PAID);
    }

    public function getPayUrl()
    {
        return add_query_arg(
            array(
                'sln_step_page' => 'thankyou',
                'submit_thankyou' => 1,
                'sln_booking_id' => $this->getUniqueId(),
            ),
            get_permalink(SLN_Plugin::getInstance()->getSettings()->getPayPageId() )
        );
    }

    public function getTimeStringToChangeStatusFromPending() {
        $plugin = SLN_Plugin::getInstance();
        $left   = '';

        if (in_array($this->getStatus(), array(SLN_Enum_BookingStatus::PENDING, SLN_Enum_BookingStatus::PENDING_PAYMENT)) && $plugin->getSettings()->get('pay_offset_enabled')) {
            $payOffset      = $plugin->getSettings()->get('pay_offset');
            $checkTimestamp = strtotime($this->object->post_date, current_time('timestamp', 1));
            $leftSeconds    = $checkTimestamp + $payOffset*MINUTE_IN_SECONDS - current_time('timestamp', 1);
            $leftMinutes    = $leftSeconds > 0 ? $leftSeconds/60 : 0;

            $left = sprintf(__("%d hours and %d minutes", 'salon-booking-system'), (int) $leftMinutes/60, (int) $leftMinutes%60);
        }

        return $left;
    }

    public function getUniqueId()
    {
        $id = $this->getMeta('uniqid');
        if (!$id) {
            $id = md5(uniqid().$this->getId());
            $this->setMeta('uniqid', $id);
        }

        return $this->getId().'-'.$id;
    }

    public function getRating()
    {
        return $this->getMeta('rating');
    }

    public function setRating($rating)
    {
        $this->setMeta('rating', $rating);
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $oldStatus = $this->getStatus();
        $ret       = parent::setStatus($status);
        do_action('sln.booking.setStatus', $this, $oldStatus, $this->getStatus());

        $this->evalCustomerDetails();

        return $ret;
    }

    public function evalCustomerDetails()
    {
        if (SLN_Wrapper_Customer::isCustomer($this->getUserId())) {
            $customer = new SLN_Wrapper_Customer($this->getUserId());

            $thisBookingTime = $this->getStartsAt()->format('Y-m-d H:i');
            $lastBookingTime = $customer->getLastBookingTime();

            $allStatuses          = array_keys(SLN_Enum_BookingStatus::toArray());
            $completedStatuses    = array(SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::CONFIRMED);
            $nonCompletedStatuses = array_diff($allStatuses, $completedStatuses);


            if (in_array($this->getStatus(), $nonCompletedStatuses) && $thisBookingTime === $lastBookingTime) {
                $customer->setLastBookingTime($customer->calcLastBookingTime());
            }
            elseif (in_array($this->getStatus(), $completedStatuses) && ($thisBookingTime > $lastBookingTime || !$lastBookingTime)) {
                $customer->setLastBookingTime($thisBookingTime);
            }

            $customer->setNextBookingTime($customer->calcNextBookingTime());
        }
    }

    public function getEmailCancellationDetails(&$cancellationText, &$bookingMyAccountUrl)
    {
		$cancellationText = $bookingMyAccountUrl = '';
		$plugin = SLN_Plugin::getInstance();
		
		$cancellationEnabled = $plugin->getSettings()->get('cancellation_enabled');
		if( !$cancellationEnabled )
			return false;
		
		$cancellationHours = $plugin->getSettings()->get('hours_before_cancellation');
		$outOfTime = (strtotime($this->getStartsAt())-current_time('timestamp')) < $cancellationHours * 3600;
		if( $outOfTime )
			return false;
			
		$bookingMyAccountPageId = $plugin->getSettings()->getBookingmyaccountPageId();
		if( !$bookingMyAccountPageId )
			return false;
		
		// have time and know page ?
		$cancellationText = $cancellationHours<24 ? $cancellationHours . __(" hours", 'salon-booking-system') : 
							$cancellationHours==24? __("1 day", 'salon-booking-system') : round($cancellationHours/24) . __("days", 'salon-booking-system');
		$bookingMyAccountUrl = get_permalink($bookingMyAccountPageId);
		return true;
	}	
}
