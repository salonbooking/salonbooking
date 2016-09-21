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
		foreach($data as $sId => $item) {

			$atId     = null;
			$price    = null;
			$duration = null;
			$break    = null;

			$service = SLN_Plugin::getInstance()->createService($sId);

			if (is_array($item)) {
				if (isset($item['attendant'])) {
					$atId = intval($item['attendant']);
				}
				if (isset($item['price'])) {
					$price = SLN_Func::filter($item['price'], 'money');
				}
				if (isset($item['duration'])) {
					$duration = $item['duration'];
				}
				if (isset($item['break_duration'])) {
					$break = $item['break_duration'];
				}
			} else {
				$atId = intval($item);
			}

			if (empty($price)) {
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
