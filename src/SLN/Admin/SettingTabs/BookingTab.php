<?php 	
class SLN_Admin_SettingTabs_BookingTab extends SLN_Admin_SettingTabs_AbstractTab
{
    protected $fields = array(
        'confirmation',
        'thankyou',
        'bookingmyaccount',
        'pay',
        'reservation_interval_enabled', // algolplus
        'minutes_between_reservation',  // algolplus
        'availabilities',
        'holidays',                     // algolplus
        'availability_mode',
        'cancellation_enabled',         // algolplus
        'hours_before_cancellation',    // algolplus
        'disabled',
        'disabled_message',
        'confirmation',
        'parallels_day',
        'parallels_hour',
        'hours_before_from',
        'hours_before_to',
        'interval',
        'form_steps_alt_order',
    );

	protected function validate(){
            if(!isset($this->submitted['disabled']))
                $this->submitted['disabled'] = 0;
		if (isset($this->submitted['availabilities'])) {
            $this->submitted['availabilities'] = SLN_Helper_AvailabilityItems::processSubmission(
                $this->submitted['availabilities']
            );
        }

        if (isset($this->submitted['holidays'])) {
            $this->submitted['holidays'] = SLN_Helper_HolidayItems::processSubmission($this->submitted['holidays']);
        }
	}

	protected function postProcess(){
		$this->plugin->getBookingCache()->refreshAll();
        if ($this->settings->getAvailabilityMode() != 'highend') {
            $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
            foreach ($repo->getAll() as $service) {
                $service->setMeta('break_duration', SLN_Func::convertToHoursMins(0));
            }
        }
	}
}
 ?>
