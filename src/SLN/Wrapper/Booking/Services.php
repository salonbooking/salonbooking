<?php

final class SLN_Wrapper_Booking_Services {

	private $items = array();

	/**
	 * SLN_Wrapper_Booking_Services constructor.
	 *
	 * @param $data
	 */
	public function __construct( $data ) {
		if(!empty($data)){
			foreach ($data as $item) {
				$this->items[] = new SLN_Wrapper_Booking_Service($item);
			}
		}
	}

	/**
	 * @return SLN_Wrapper_Booking_Service[]
	 */
	public function getItems() {
		return empty($this->items) ? array() : $this->items;
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return count($this->getItems());
	}

	/**
	 * @return null|SLN_Wrapper_Booking_Service
	 */
	public function getFirstItem() {
		$items = $this->getItems();
		return empty($items) ? null : reset($items);
	}

	/**
	 * @param int $serviceId
	 *
	 * @return false|SLN_Wrapper_Booking_Service
	 */
	public function findByService($serviceId) {
		foreach($this->getItems() as $bookingService) {
			if ($serviceId == $bookingService->getService()->getId()) {
				return $bookingService;
			}
		}
		return false;
	}

	/**
	 * @param SLN_Wrapper_Booking_Service $bookingService
	 *
	 * @return bool|int
	 */
	public function getPosInQueue(SLN_Wrapper_Booking_Service $bookingService) {
		$pos = array_search($bookingService, $this->items);

		return ($pos === false ? $pos : $pos + 1);
	}

	public function isLast(SLN_Wrapper_Booking_Service $bookingService) {
		return count($this->items) && $this->items[count($this->items) - 1] === $bookingService;
	}

	public function toArrayRecursive() {
		$ret = array();
		if(!empty($this->items)){
			foreach ($this->items as $item) {
				/** @var SLN_Wrapper_Booking_Service $item */
				$ret[] = $item->toArray();
			}
		}

		return $ret;
	}

	/**
	 * @param array $data   array($service_id => $attendant_id) or array($service_id => array('attendant' => $attendant_id, 'price' => float, 'duration' => 'H:i' ))
	 * @param SLN_DateTime $startsAt
	 * @param int $offset   minutes
	 *
	 * @return SLN_Wrapper_Booking_Services
	 */
	public static function build($data, SLN_DateTime $startsAt, $offset = 0) {
		$startsAt = clone $startsAt;
		uksort($data, array('SLN_Repository_ServiceRepository', 'serviceCmp'));
		$services = array();
		foreach($data as $i => $item) {

            $sId      = null;
			$atId     = null;
			$price    = null;
			$duration = null;
			$break    = null;

			if (is_array($item)) {
                if (isset($item['service'])) {
                    $sId = intval($item['service']);
                }
				if (isset($item['attendant'])) {
					$atId = intval($item['attendant']);
				}
				if (isset($item['price'])) {
					$price = floatval($item['price']);
				}
				if (isset($item['duration'])) {
					$duration = $item['duration'];
				}
				if (isset($item['break_duration'])) {
					$break = $item['break_duration'];
				}
			} else {
                $sId  = intval($i);
				$atId = intval($item);
			}

            $service = SLN_Plugin::getInstance()->createService($sId);
            $service = apply_filters('sln.booking_services.buildService', $service);

			if (is_null($price)) {
				$price = $service->getPrice();
			}

			if (empty($duration)) {
				$duration = $service->getDuration()->format('H:i');
			}

			if (empty($break)) {
				$break = $service->getBreakDuration()->format('H:i');
			}

			$services[] = array(
				'service'    => $sId,
				'attendant'  => $atId,
				'start_date' => $startsAt->format('Y-m-d'),
				'start_time' => $startsAt->format('H:i'),
				'duration'   => $duration,
				'break_duration'   => $break,
				'price'      => $price,
				'exec_order' => $service->getExecOrder(),
			);

			$minutes = SLN_Func::getMinutesFromDuration($duration) + SLN_Func::getMinutesFromDuration($break) + $offset;
			$startsAt->modify('+'.$minutes.' minutes');
		}
		$ret = new SLN_Wrapper_Booking_Services($services);

		return $ret;
	}

}
