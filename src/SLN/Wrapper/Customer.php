<?php

class SLN_Wrapper_Customer extends SLN_Wrapper_Abstract {

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
		if (in_array(SLN_Plugin::USER_ROLE_CUSTOMER, $object->roles)) {
			$this->object = $object;
		}
		else {
			$this->object = new WP_User();
		}
	}

	public function get($key) {
		return $this->object->get($key);
	}

	public function getCountOfReservations() {
		if (!$this->isEmpty()) {

			$query = new WP_Query(array(
				'post_type' => SLN_Plugin::POST_TYPE_BOOKING,
				'author'    => $this->object->ID,
			));
			$count = count($query->get_posts());

			wp_reset_query();
			wp_reset_postdata();

			return $count;
		}

		return 0;
	}

	public function getCustomerValue() {
		$ret = 0;

		if (!$this->isEmpty()) {

			$query = new WP_Query(array(
				'post_type' => SLN_Plugin::POST_TYPE_BOOKING,
				'author'    => $this->object->ID,
			));

			foreach ($query->get_posts() as $p) {
				$ret += SLN_Plugin::getInstance()->createBooking($p)->getAmount();
			}

			wp_reset_query();
			wp_reset_postdata();

		}

		return number_format(empty($ret) ? 0 : floatval($ret), 2);
	}

	public function isEmpty(){
		return empty($this->object->ID);
	}
}