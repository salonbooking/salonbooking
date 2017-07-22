<?php

class SLN_Wrapper_Customer {

	private $bookings = array();
	private $countOfBookingsForEstimateNextBooking = 7;

	/**
	 * SLN_Wrapper_Customer constructor.
	 *
	 * @param WP_User|int $object
	 */
	public function __construct($object) {
		if (!is_object($object)) {
			$object = get_user_by('id', $object);
		}
		if (self::isCustomer($object) || self::isAdmin($object)) {
			$this->object = $object;
		}
		else {
			$this->object = new WP_User();
		}
	}

	public function isEmpty() {
		return empty($this->object->ID);
	}

	function getId() {
		if (!$this->isEmpty()) {
			return $this->object->ID;
		}
	}

	public function get($key) {
		return $this->object->get($key);
	}

	public function getMeta($key) {
		$key = "_sln_{$key}";
		return apply_filters("$key.get", get_user_meta($this->getId(), $key, true));
	}

	public function setMeta($key, $value) {
		$key = "_sln_{$key}";
		update_user_meta($this->getId(), $key, apply_filters("$key.set", $value));
	}

	public function deleteMeta($key) {
		$key = "_sln_{$key}";
		delete_user_meta($this->getId(), $key);
	}

	public function getName() {
		if (!$this->isEmpty()) {
			$name      = array();
			$firstname = $this->get('first_name');
			$lastname  = $this->get('last_name');
			if (!empty($firstname)) {
				$name[] = $firstname;
			}
			if (!empty($lastname)) {
				$name[] = $lastname;
			}

			return implode(' ', $name);
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

		return floatval($amount);
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
	 * @param array $args
	 *
	 * @return SLN_Wrapper_Booking[]
	 */
	public function getCompletedBookings($args = array()) {
		$args['post_status'] = array(SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::CONFIRMED);

		$bookings            = $this->getBookings($args);

		return $bookings;
	}

	/**
	 * @param array $args
	 *
	 * @return SLN_Wrapper_Booking[]
	 * @throws Exception
	 */
	public function getBookings($args = array()) {
		if (!$this->isEmpty() && empty($this->bookings)) {
			$args['author'] = $this->object->ID;

			$repo           = SLN_Plugin::getInstance()->getRepository(SLN_Plugin::POST_TYPE_BOOKING);
			$this->bookings = $repo->get(
				array(
					'@query' => '',
					'@wp_query' => $args,
				)
			);
		}

		return $this->bookings;
	}

	/**
	 * @return string|false 'Y-m-d H:i'
	 */
	public function getLastBookingTime() {
		$date = $this->getMeta('last_booking_time');

		if (!$date) {
			$date = $this->calcLastBookingTime();
		}

		return $date;
	}

	/**
	 * @return string|false 'Y-m-d H:i'
	 */
	public function calcLastBookingTime() {
		$date = false;
		$args = array(
			'meta_key' => '_sln_booking_date',
			'orderby'  => 'meta_value',
			'order'    => 'DESC'
		);
		$bookings = $this->getCompletedBookings($args);

		if (!empty($bookings)) {
			usort($bookings, array($this, 'sortDescByStartsAt'));
			/** @var SLN_Wrapper_Booking $last */
			$last = reset($bookings);
			$date = $last->getStartsAt()->format('Y-m-d H:i');
		}

		return $date;
	}

	/**
	 * @return string|false 'Y-m-d'
	 */
	public function getNextBookingTime() {
		$date = $this->getMeta('next_booking_time');

		if (!$date) {
			$date = $this->calcNextBookingTime();
		}

		return $date;
	}

	/**
	 * @return string|false 'Y-m-d'
	 */
	public function calcNextBookingTime() {
		$lastDate = $this->getLastBookingTime();
		if (!$lastDate) {
			return false;
		}

		$date = false;

		$args = array(
			'meta_key' => '_sln_booking_date',
			'orderby'  => 'meta_value',
			'order'    => 'DESC',
		);
		$bookings = $this->getCompletedBookings($args);

		if (!empty($bookings)) {
			usort($bookings, array($this, 'sortDescByStartsAt'));
			/** @var SLN_Wrapper_Booking[] $bookings */
			$bookings = array_slice($bookings, 0, $this->countOfBookingsForEstimateNextBooking);

			$lastId = count($bookings) - 1;
			$days = 0;
			foreach($bookings as $k => $b) {
				if ($k < $lastId) {
					// interval in days between bookings
					$interval = (strtotime($b->getStartsAt()->format('Y-m-d').' 00:00:00') - strtotime($bookings[$k+1]->getStartsAt()->format('Y-m-d').' 00:00:00'))/(60*60*24) - 1;
					$interval = $interval > 0 ? $interval : 0;
					$days    += $interval;
				}
			}

			$value = round($days / count($bookings)) + 1;

			$date  = date('Y-m-d', strtotime("+$value days", strtotime($lastDate)));
		}

		return $date;
	}

	/**
	 * @param SLN_Wrapper_Booking $a
	 * @param SLN_Wrapper_Booking $b
	 *
	 * @return int
	 */
	private function sortDescByStartsAt($a, $b) {
		return (strtotime($a->getStartsAt()->format('Y-m-d H:i:s')) >= strtotime($b->getStartsAt()->format('Y-m-d H:i:s')) ? -1 : 1 );
	}

	public function setLastBookingTime($date) {
		if ($date instanceof DateTime) {
			$date = $date->format('Y-m-d H:i');
		}
		$this->setMeta('last_booking_time', $date);
	}

	public function setNextBookingTime($date) {
		if ($date instanceof DateTime) {
			$date = $date->format('Y-m-d');
		}
		$this->setMeta('next_booking_time', $date);
	}

	public function getHash() {
	    $hash = $this->getMeta('hash');
	    if (empty($hash)) {
            $hash = $this->generateHash();
            $this->setMeta('hash', $hash);
        }

        return $hash;
    }

	private function generateHash() {
		do {
			$hash = substr(md5($this->getId().':'.current_time('timestamp')), 0, 8);
		} while(self::getCustomerIdByHash($hash));

		return $hash;
	}

	public static function getCustomerIdByHash($hash) {
		global $wpdb;

		$userid = $wpdb->get_var("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='_sln_hash' AND meta_value='{$hash}' ");

		return $userid;
	}

	public static function getCustomerIdByFacebookID($fbID) {
		global $wpdb;

		$userid = $wpdb->get_var("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='_sln_fb_id' AND meta_value='{$fbID}' ");

		return $userid;
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

	public static function isAdmin($object) {
		if (!is_object($object)) {
			$object = get_user_by('id', $object);
			if (!$object) {
				return false;
			}
		}

		if (in_array('administrator', $object->roles)) {
			return true;
		}
		else {
			return false;
		}
	}
}