<?php // algolplus

class SLN_Shortcode_SalonCalendar {
	const NAME = 'salon_booking_calendar';

	private $plugin;
	private $attrs;

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
			return wp_login_form();
		}

		return $this->getContentFull();
	}

	public function getContentFull() {
        $data = $this->prepareContentData();

        return $this->renderContentFull($data);
    }

	public function getContent() {
        $data = $this->prepareContentData();

        return $this->renderContent($data);
    }

    private function prepareContentData() {
        $plugin    = $this->plugin;
        $formatter = $plugin->format();

        $ret = array();

        $colors   = array();
        $statuses = SLN_Enum_BookingStatus::toArray();
        foreach($statuses as $k => $status) {
            $colors[] = SLN_Enum_BookingStatus::getColor($k);
        }
        $colors = array_values(array_unique($colors));

        $timestamp = current_time('timestamp');
        for ($i = 1; $i <= 7; $i++) {
            $key = date('Y-m-d', $timestamp);
            $ret['dates'][$key] = date('l', $timestamp) . ' / ' . $formatter->date($key);
            $timestamp = strtotime('+1 day', $timestamp);
        }
        unset($timestamp);


        /** @var SLN_Wrapper_Attendant[] $assistants */
        $assistants = $plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT)->get();
        $attData    = array();
        foreach($assistants as $k => $assistant) {
            $attData[$assistant->getId()] = array(
                'name'   => $assistant->getTitle(),
                'color'  => $colors[$k % count($colors)],
                'events' => array(),
            );
        }

        $bookings = $this->buildBookings();
        /** @var SLN_Wrapper_Booking $b */
        foreach($bookings as $b) {
            if ($plugin->getSettings()->isMultipleAttendantsEnabled() && $plugin->getSettings()->getAvailabilityMode() === 'highend') {
                foreach($b->getBookingServices()->getItems() as $bookingService) {
                    if ($bookingService->getAttendant()) {
                        $date = $bookingService->getStartsAt()->format('Y-m-d');
                        $attData[$bookingService->getAttendant()->getId()]['events'][$date][] = array(
                            'title' => $plugin->format()->time($bookingService->getStartsAt()) . ' - ' . $b->getDisplayName(),
                            'desc'  => $bookingService->getService()->getName() . '<br/><br/><strong>' . SLN_Enum_BookingStatus::getLabel($b->getStatus()) . '</strong>',
                        );
                    }
                }
            }
            else {
                $rows = array();
                foreach($b->getBookingServices()->getItems() as $bookingService) {
                    if ($bookingService->getAttendant()) {
                        $rows[$bookingService->getAttendant()->getId()][] = $bookingService->getService()->getName();
                    }
                }

                foreach($rows as $attId => $services) {
                    $date = $b->getStartsAt()->format('Y-m-d');
                    $attData[$attId]['events'][$date][] = array(
                        'title' => $plugin->format()->time($b->getStartsAt()) . ' - ' . $b->getDisplayName(),
                        'desc'  => implode('<br/>', $services) . '<br/><br/><strong>' . SLN_Enum_BookingStatus::getLabel($b->getStatus()) . '</strong>',
                    );
                }
            }
        }

        $ret['attendants'] = $attData;

        return $ret;
    }

	private function buildBookings()
	{
		$statuses = SLN_Enum_BookingStatus::toArray();
		unset($statuses[SLN_Enum_BookingStatus::CANCELED], $statuses[SLN_Enum_BookingStatus::ERROR]);
		$statuses = array_keys($statuses);

		/** @var SLN_Repository_BookingRepository $repo */
		$repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_BOOKING);

		$ret  = $repo->get(
			array(
				'@wp_query'   => array(
					'post_status' => $statuses,
					'meta_query'  => array(
						'relation' => 'AND',
						array(
							'key'     => '_sln_booking_date',
							'value'   => current_time('Y-m-d'),
							'type'    => 'DATE',
							'compare' => '>=',
						),
						array(
							'key'     => '_sln_booking_date',
							'value'   => date('Y-m-d', strtotime('+7 days', current_time('timestamp'))),
							'type'    => 'DATE',
							'compare' => '<',
						)
					),
				),
				'@query' => array(),
			)
		);

		usort($ret, array($this, 'orderBy'));

		return $ret;
	}

	/**
	 * @param SLN_Wrapper_Booking $a
	 * @param SLN_Wrapper_Booking $b
	 */
	private function orderBy($a, $b) {
		if ($a->getStartsAt() <= $b->getStartsAt()) {
			return -1;
		}
		else {
			return 1;
		}
	}

	protected function renderContentFull($data)
	{
		return $this->plugin->loadView('shortcode/salon_booking_calendar/calendar_full', compact('data'));
	}

    protected function renderContent($data)
    {
        return $this->plugin->loadView('shortcode/salon_booking_calendar/calendar_content', compact('data'));
    }
}