<?php // algolplus

class SLN_Helper_Availability_MyAccountBookings
{
	private $date;
	private $bookings;

	public function __construct()
	{
		$this->date = new DateTime();
	}

	private function buildBookings($user, $mode)
	{
		$args = array(
			'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
			'nopaging'   => true,
			'order'      => 'DESC',
			'orderby'    => 'meta_value',
			'meta_key'   => '_sln_booking_date',
			'meta_query' => array(
				array(
					'key'     => '_sln_booking_date',
					'value'   => $this->date->format('Y-m-d'),
					'compare' => $mode == 'past' ? '<' : '>=',
				)
			),
			'author' => $user
		);
		$query = new WP_Query($args);
		$ret = array();
		foreach ($query->get_posts() as $p) {
			$ret[] = SLN_Plugin::getInstance()->createBooking($p);
		}
		wp_reset_query();
		wp_reset_postdata();

		SLN_Plugin::addLog(__CLASS__.' - buildBookings('.$this->date->format('Y-m-d').', ' . $mode . ')');
		foreach($ret as $b)
			SLN_Plugin::addLog(' - '.$b->getId());
		return $ret;
	}

	public function getBookings($user, $mode = 'past')
	{
		$this->bookings = $this->buildBookings($user, $mode);
		return $this->bookings;
	}
}