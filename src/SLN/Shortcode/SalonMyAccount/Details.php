<?php // algolplus

class SLN_Shortcode_SalonMyAccount_Details
{
	const NAME = 'salon_booking_my_account_details';

	private $plugin;
	private $attrs;
	private $perPage = 5;

	function __construct(SLN_Plugin $plugin, $attrs)
	{
		$this->plugin = $plugin;
		$this->attrs = $attrs;
	}

	public static function init(SLN_Plugin $plugin)
	{
		add_shortcode(self::NAME, array(__CLASS__, 'create'));
	}

	public static function create($attrs)
	{
            SLN_TimeFunc::startRealTimezone();

		$obj = new self(SLN_Plugin::getInstance(), $attrs);

		$ret = $obj->execute();
            SLN_TimeFunc::endRealTimezone();
		return $ret;
	}

	public function execute()
	{
		if (!is_user_logged_in()) {
			return false;
		}
		$accountBookings = new SLN_Helper_Availability_MyAccountBookings();

		if (isset($this->attrs['part']) && $this->attrs['part'] === 'history') { // history 'load more'
			$page = $this->attrs['page'];

			$historyItems = $this->prepareBookings($accountBookings->getBookings(get_current_user_id(), 'history'));
			$historyEnds  = count($historyItems) <= ($this->perPage*$page);
			$historyItems = array_slice($historyItems, 0, $this->perPage*$page);

			return $this->render('shortcode/salon_my_account/_salon_my_account_details_history_table',
					array(
						'history' => array(
							'page'  => $page,
							'items' => $historyItems,
							'end'   => $historyEnds,
						),
						'hide_prices' => $this->plugin->getSettings()->get('hide_prices'),
						'attendant_enabled' => $this->plugin->getSettings()->get('attendant_enabled'),
					)
			);
		}

// FULL MY ACCOUNT PAGE
		$args=array(
				'name'           => 'booking',
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
		);
		$query = new WP_Query($args);
		$post  = $query->get_queried_object();
		wp_reset_query();

		$historyItems = $this->prepareBookings($accountBookings->getBookings(get_current_user_id(), 'history'));
		$historyEnds  = count($historyItems) <= $this->perPage;
		$historyItems = array_slice($historyItems, 0, $this->perPage);

		return $this->render('shortcode/salon_my_account/salon_my_account_details',
				array(
					'new' => array(
						'items' => $this->prepareBookings($accountBookings->getBookings(get_current_user_id(), 'new'))
					),
					'history' => array(
						'page'  => 1,
						'items' => $historyItems,
						'end'   => $historyEnds,
					),
					'cancellation_enabled' => $this->plugin->getSettings()->get('cancellation_enabled'),
					'seconds_before_cancellation' => $this->plugin->getSettings()->get('hours_before_cancellation') * 3600,
					'gen_phone' => $this->plugin->getSettings()->get('gen_phone'),
					'cancelled' => !empty($_POST['option']) && $_POST['option'] = 'cancelled' ? true : false,
					'user_name' => wp_get_current_user()->user_firstname,
					'gen_name' => $this->plugin->getSettings()->get('gen_name'),
					'hide_prices' => $this->plugin->getSettings()->get('hide_prices'),
					'attendant_enabled' => $this->plugin->getSettings()->get('attendant_enabled'),
					'pay_enabled' => $this->plugin->getSettings()->get('pay_enabled'),
					'booking_url' => get_post_permalink($post->ID),
				)
		);
	}

	private function prepareBookings($bookings)
	{
		$result = array();
		foreach ( $bookings as $booking ) {
			$result[] = $this->prepareBooking($booking);
		}

		return $result;
	}

	private function prepareBooking($booking) {
        $format = $this->plugin->format();
        $serviceNames = array();
        foreach($booking->getServices() as $s){
            $serviceNames[] = $s->getName();
        }

		$total = $format->moneyFormatted($booking->getAmount());
        if (SLN_Enum_BookingStatus::PAID == $booking->getStatus() && $deposit = $booking->getDeposit()) {
	        $total .= ' (' . $format->moneyFormatted($deposit) . ' ' .
	                  __('already paid as deposit','salon-booking-system') . ')';
        }
		return array(
			'id' => $booking->getId(),
			'date' => $format->date($booking->getStartsAt()),
			'time' => $format->time($booking->getStartsAt()),
			'timestamp' => strtotime($booking->getStartsAt()),
			'services' => implode("<br>", $serviceNames),
			'assistant' => $booking->getAttendantsString(),
			'total' => $total,
			'status' => SLN_Enum_BookingStatus::getLabel($booking->getStatus()),
			'status_code' => $booking->getStatus(),
			'rating' => $booking->getRating(),
		);
	}

	protected function render($view, $data)
	{
		return $this->plugin->loadView($view, compact('data'));
	}

}
