<?php

class SLN_Action_CancelBookings {
	/** @var SLN_Plugin */
	private $plugin;
	private $type = 'Cancel bookings';

	public function __construct(SLN_Plugin $plugin)
	{
		$this->plugin = $plugin;
	}

	public function execute() {
		$p = $this->plugin;
		$type = $this->type;

		$payOffsetEnabled = $this->plugin->getSettings()->get('pay_offset_enabled');

		if ($payOffsetEnabled) {
			$p->addLog($type.' execution started');
			$bookings = $this->getBookings();
			foreach ( $bookings as $booking ) {
				$booking->setStatus(SLN_Enum_BookingStatus::CANCELED);
			}
			$p->addLog($type.' execution ended');
		}
	}

	private function getBookings() {
		$payOffset = $this->plugin->getSettings()->get('pay_offset');
		/** @var SLN_Repository_BookingRepository $repo */
		$repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_BOOKING);
		$ret = $repo->get(
			array(
				'post_status' => array(SLN_Enum_BookingStatus::PENDING, SLN_Enum_BookingStatus::PENDING_PAYMENT),
				'date_query' => array(
					array(
						'column' => 'post_date',
						'before' => "-$payOffset minutes",
					),
				)
			)
		);

		return $ret;
	}
}