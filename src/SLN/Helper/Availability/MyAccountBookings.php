<?php // algolplus

class SLN_Helper_Availability_MyAccountBookings
{
	private $date;

	public function __construct()
	{
		$this->date = new DateTime();
	}

	private function buildBookings($user, $mode)
	{
		$timestamp = current_time('timestamp');

		$args = array(
			'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
			'nopaging'   => true,
			'meta_query' => array(
				array(
					'key'     => '_sln_booking_date',
					'value'   => $this->date->format('Y-m-d'),
					'compare' => $mode == 'history' ? '<=' : '>=',
				)
			),
			'author' => $user
		);
		if ($mode === 'new') {
			$statuses = SLN_Enum_BookingStatus::toArray();
			unset($statuses[SLN_Enum_BookingStatus::CANCELED], $statuses[SLN_Enum_BookingStatus::ERROR]);
			$statuses = array_keys($statuses);
			$args['post_status'] = $statuses;
		}
		$query = new WP_Query($args);
		$ret = array();
		foreach ($query->get_posts() as $p) {
			$b     = SLN_Plugin::getInstance()->createBooking($p);
			$bTime = strtotime($b->getStartsAt()->format('Y-m-d H:i'));
			if ($mode === 'new' && $bTime >= $timestamp || $mode === 'history' && $bTime < $timestamp ) {
				$ret[] = $b;
			}
		}
		wp_reset_query();
		wp_reset_postdata();
		usort(
				$ret,
				$mode == 'history' ? array($this, 'sortDescByStartsAt') : array($this, 'sortAscByStartsAt')
		);

		SLN_Plugin::addLog(__CLASS__.' - buildBookings('.$this->date->format('Y-m-d').', ' . $mode . ')');
		foreach($ret as $b)
			SLN_Plugin::addLog(' - '.$b->getId());
		return $ret;
	}

	/**
	 * @param SLN_Wrapper_Booking $a
	 * @param SLN_Wrapper_Booking $b
	 *
	 * @return int
	 */
	private function sortAscByStartsAt($a, $b) {
		return (strtotime($a->getStartsAt()->format('Y-m-d H:i:s')) > strtotime($b->getStartsAt()->format('Y-m-d H:i:s')) ? 1 : -1 );
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

	public function getBookings($user, $mode = 'history')
	{
		return $this->buildBookings($user, $mode);
	}
}