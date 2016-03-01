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
	public static function build($data, $startsAt, $offset = 0) {
		$startsAt = clone $startsAt;
		uksort($data, function($a, $b) {
			$aExecOrder = SLN_Plugin::getInstance()->createService($a)->getExecOrder();
			$bExecOrder = SLN_Plugin::getInstance()->createService($b)->getExecOrder();
			if ($aExecOrder > $bExecOrder)
				return 1;
			else
				return -1;
		});

		$services = array();
		foreach($data as $sId => $item) {

			$service = SLN_Plugin::getInstance()->createService($sId);

			if (is_array($item)) {
				if (isset($item['attendant'])) {
					$atId = $item['attendant'];
				}
				if (isset($item['price'])) {
					$price = SLN_Func::filter($item['price'], 'money');
				}
				if (isset($item['duration'])) {
					$duration = $item['duration'];
				}
			} else {
				$atId = $item;
			}

			if (!isset($atId)) {
				$atId = 0;
			}
			if (!isset($price)) {
				$price = $service->getPrice();
			}

			if (!isset($duration)) {
				$duration = $service->getDuration()->format('H:i');
			}

			$services[] = array(
				'service'    => $sId,
				'attendant'  => $atId,
				'start_date' => $startsAt->format('Y-m-d'),
				'start_time' => $startsAt->format('H:i'),
				'duration'   => $duration,
				'price'      => $price,
				'exec_order' => $service->getExecOrder(),
			);

			$durationParts = explode(':', $duration);
			$h = intval($durationParts[0]);
			$i = intval($durationParts[1]);
			$minutes = $h*60 + $i + $offset;
			$startsAt = $startsAt->modify('+'.$minutes.' minutes');
		}
		$ret = new SLN_Wrapper_Booking_Services($services);

		return $ret;
	}
}