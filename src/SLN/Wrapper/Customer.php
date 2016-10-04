<?php

class SLN_Wrapper_Customer extends SLN_Wrapper_Abstract {

	private $bookings = array();

	public function getPostType()
	{
		return false;
	}

	/**
	 * SLN_Wrapper_Customer constructor.
	 *
	 * @param WP_User $object
	 */
	public function __construct($object) {
		if (!is_object($object)) {
			$object = get_user_by('id', $object);
		}
		if (self::isCustomer($object)) {
			$this->object = $object;
		}
		else {
			$this->object = new WP_User();
		}
	}

	public function get($key) {
		return $this->object->get($key);
	}

	public function getName()
	{
		if (!$this->isEmpty()) {
			return $this->get('first_name') . ' ' . $this->get('last_name');
		}
	}

	/**
	 * @param int|null $period In seconds
	 *
	 * @return int|float
	 */
	public function getCountOfReservations($period = null) {
		$count = 0;
		if (!$this->isEmpty()) {

			$bookings = $this->getCompletedBookings();

			$count    = count($bookings);

			if (!empty($period)) {
				$customer_time = strtotime($this->object->post_date);
				$current_time  = current_time('timestamp');

				$count /= ($current_time - $customer_time) / $period;
			}
		}

		return round($count);
	}

	/**
	 * @param int|null $period In seconds
	 *
	 * @return string
	 */
	public function getAmountOfReservations($period = null) {
		$amount = 0;
		if (!$this->isEmpty()) {

			$bookings = $this->getCompletedBookings();

			foreach ($bookings as $booking) {
				$amount += $booking->getAmount();
			}

			if (!empty($period)) {
				$customer_time = strtotime($this->object->post_date);
				$current_time  = current_time('timestamp');

				$amount /= ($current_time - $customer_time) / $period;
			}
		}

		return number_format(empty($amount) ? 0 : floatval($amount), 2);
	}

	public function getAverageCountOfServices() {
		$countServices = 0;
		if (!$this->isEmpty()) {

			$bookings      = $this->getCompletedBookings();

			$countBookings = count($bookings);

			if ($countBookings) {
				foreach ($bookings as $booking) {
					$countServices += count($booking->getServicesIds());
				}

				$countServices /= $countBookings;
			}
		}

		return empty($countServices) ? 0 : (floor($countServices) === floatval($countServices) ? $countServices : number_format(floatval($countServices), 2));
	}

	/**
	 * @return array|bool Array of int[0-6] or false
	 */
	public function getFavouriteWeekDays() {
		$favDays = false;
		if (!$this->isEmpty()) {
			$bookings = $this->getCompletedBookings();

			if (!empty($bookings)) {
				$daysOfWeek = SLN_Enum_DaysOfWeek::toArray();
				$daysOfWeek = array_fill_keys(array_keys($daysOfWeek), 0);

				foreach ($bookings as $booking) {
					$daysOfWeek[$booking->getStartsAt()->format('N') % 7]++;
				}

				$favDays = array_keys($daysOfWeek, max($daysOfWeek));
			}
		}

		return $favDays;
	}

	/**
	 * @return array|bool Array of times or false
	 */
	public function getFavouriteTimes() {
		$favTimes = false;
		if (!$this->isEmpty()) {
			$bookings = $this->getCompletedBookings();

			if (!empty($bookings)) {
				$times    = array();
				foreach ($bookings as $booking) {
					$time = SLN_Plugin::getInstance()->format()->time($booking->getStartsAt());
					if (!isset($times[$time])) {
						$times[$time] = 0;
					}
					$times[$time]++;
				}

				$favTimes = array_keys($times, max($times));
			}
		}

		return $favTimes;
	}

	/**
	 * @return SLN_Wrapper_Booking[]
	 */
	public function getCompletedBookings() {
		$bookings = $this->getBookings();

		foreach($bookings as $k => $booking) {
			if (!in_array($booking->getStatus(), array(SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::CONFIRMED))) {
				unset($bookings[$k]);
			}
		}

		return $bookings;
	}

	/**
	 * @return SLN_Wrapper_Booking[]
	 */
	public function getBookings() {
		if (!$this->isEmpty() && empty($this->bookings)) {
			$repo           = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_BOOKING);
			$this->bookings = $repo->get(
				array(
					'@query' => '',
					'@wp_query' => array(
							'author' => $this->object->ID,
					),
				)
			);
		}

		return $this->bookings;
	}

	public function isEmpty(){
		return empty($this->object->ID);
	}

	public static function isCustomer($object) {
		if (!is_object($object)) {
			$object = get_user_by('id', $object);
			if (!$object) {
				return false;
			}
		}

		if (in_array(SLN_Plugin::USER_ROLE_CUSTOMER, $object->roles)) {
			return true;
		}
		else {
			return false;
		}
	}
}