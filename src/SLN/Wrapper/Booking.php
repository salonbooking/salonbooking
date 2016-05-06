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

    function getToPayAmount()
    {
        $ret = $this->getDeposit() > 0 ? $this->getDeposit() : $this->getAmount();

        return number_format($ret, 2);
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
        $bookingServices = SLN_Wrapper_Booking_Services::build($data, $this->getStartsAt());
        $ret = $bookingServices->toArrayRecursive();
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
            $d = $bookingService->getDuration();
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
        $t = 0;
        SLN_Plugin::addLog(__CLASS__.' eval total of'.$this->getId());
        foreach ($this->getServices() as $s) {
            $d = $s->getPrice();
            $t += $d;
            SLN_Plugin::addLog(' - service '.$s.' +'.$d);
        }
        $this->setMeta('amount', $t);

        return $t;
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
        if (!empty($attendants)) {
            $ret = array();
            foreach ($attendants as $attendant) {
                $ret[] = $attendant->getName();
            }

            return implode(', ', $ret);
        } else {
            return SLN_Plugin::getInstance()->createAttendant(null)->getName();
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

    function getStartsAt()
    {
        return new SLN_DateTime($this->getDate()->format('Y-m-d').' '.$this->getTime()->format('H:i'));
    }

    function getEndsAt()
    {
        $start = $this->getStartsAt();
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
            get_permalink(SLN_Plugin::getInstance()->getSettings()->get('pay'))
        );
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
