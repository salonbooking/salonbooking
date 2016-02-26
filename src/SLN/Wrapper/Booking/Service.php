<?php


final class SLN_Wrapper_Booking_Service {
	private $service;
	private $attendant;
	private $startsAt;
	private $duration;
	private $price = 0.0;
	private $execOrder = 0;


	/**
	 * @param SLN_Wrapper_Service $service
	 * @param bool|false $update
	 *
	 * @return $this
	 */
	public function setService(SLN_Wrapper_Service $service, $update = false)
	{
		$this->service = $service;
		if ($update) {
			$this->setPrice($service->getPrice());
			$this->setDuration($service->getDuration());
			$this->setExecOrder($service->getExecOrder());
		}

		return $this;
	}

	/**
	 * @param SLN_Wrapper_Attendant $attendant
	 *
	 * @return $this
	 */
	public function setAttendant(SLN_Wrapper_Attendant $attendant)
	{
		$this->attendant = $attendant;

		return $this;
	}

	/**
	 * @param float $price
	 *
	 * @return $this
	 */
	public function setPrice($price)
	{
		$this->price = $price;

		return $this;
	}

	/**
	 * @param SLN_DateTime $duration
	 *
	 * @return $this
	 */
	public function setDuration($duration)
	{
		$this->duration = $duration;

		return $this;
	}

	/**
	 * @param DateTime $startsAt
	 *
	 * @return $this
	 */
	public function setStartsAt($startsAt)
	{
		$this->startsAt = $startsAt;

		return $this;
	}

	/**
	 * @param int $execOrder
	 *
	 * @return $this
	 */
	public function setExecOrder($execOrder)
	{
		$this->execOrder = $execOrder;

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return array(
			'attendant'  => $this->attendant->getId(),
			'service'    => $this->service->getId(),
			'duration'   => $this->duration->format('Y-m-d H:i'),
			'starts_at'  => $this->startsAt->format('Y-m-d H:i'),
			'price'      => floatval($this->price),
			'exec_order' => intval($this->execOrder),
		);
	}

	/**
	 * @return SLN_DateTime
	 */
	public function getDuration() {
		return $this->duration;
	}
}