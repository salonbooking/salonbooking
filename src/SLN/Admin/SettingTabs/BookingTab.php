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

}
 ?>