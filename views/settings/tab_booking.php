<?php
/**
 * @param $this SLN_Admin_Settings
 */
function sln_availability_row($prefix, $row, $rulenumber = 'New')
{
    ?>
    <div class="col-xs-12 sln-booking-rule">
    <h2 class="sln-box-title"><?php _e('Rule','salon-booking-system');?> <strong><?php echo $rulenumber; ?></strong></h2>
    <h6 class="sln-fake-label"><?php _e('Available days checked and green.','salon-booking-system');?></h6>
        <div class="sln-checkbutton-group">
        <?php foreach (SLN_Func::getDays() as $k => $day) : ?>
            <div class="sln-checkbutton">
                    <?php SLN_Form::fieldCheckboxButton(
                        $prefix . "[days][{$k}]",
                        (isset($row['days'][$k]) ? 1 : null),
                        $label = substr($day, 0, 3)
                    ) ?>
            </div>
        <?php endforeach ?>
        <div class="clearfix"></div>
        </div>
    <div class="row">
        <div class="col-xs-12 col-md-8 sln-slider-wrapper">
             <h6 class="sln-fake-label"><?php _e('First shift','salon-booking-system');?></h6>
            <div class="sln-slider">
            <div class="sliders_step1 col col-slider"><div class="slider-range"></div></div>
            <div class="col col-time"><span class="slider-time-from">9:00</span> to <span class="slider-time-to">16:00</span>
            <input type="text" name="<?php echo $prefix ?>[from][0]" id="" value="<?php echo $row['from'][0] ? $row['from'][0]  : "9:00" ?>" class="slider-time-input-from hidden">
            <input type="text" name="<?php echo $prefix ?>[to][0]" id="" value="<?php echo $row['to'][0] ? $row['to'][0]  : "13:00" ?>" class="slider-time-input-to hidden">
            </div>
            <div class="clearfix"></div>
            </div>
             <h6 class="sln-fake-label"><?php _e('Second shift','salon-booking-system');?></h6>
            <div class="sln-slider">
            <div class="sliders_step1 col col-slider"><div class="slider-range"></div></div>
            <div class="col col-time">
            <span class="slider-time-from">9:00</span> to <span class="slider-time-to">16:00</span>
            <input type="text" name="<?php echo $prefix ?>[from][1]" id="" value="<?php echo $row['from'][1] ? $row['from'][1]  : "14:30" ?>" class="slider-time-input-from hidden">
            <input type="text" name="<?php echo $prefix ?>[to][1]" id="" value="<?php echo $row['to'][1] ? $row['to'][1]  : "19:00" ?>" class="slider-time-input-to hidden">
            </div>
            <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
        <p class="sln-input-help"><?php _e('This rule represents your open and close days, your open and close shift. Set carefully as it will affect your reservation system.','salon-booking-system');?></p>
            <button class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash" data-collection="remove"><?php echo __('Remove', 'salon-booking-system')?></button>
        </div>

    </div>
        <!--<div class="second-row">
            <?php foreach (array(0, 1) as $i) : ?>
                <?php foreach (array('from' => __('From', 'salon-booking-system'), 'to' => __('To', 'salon-booking-system')) as $k => $v) : ?>
                    <div class="form-group">
                        <label for="<?php echo SLN_Form::makeID($prefix . "[$k][$i]") ?>">
                            <?php echo $v ?>
                        </label>
                        <?php SLN_Form::fieldTime($prefix . "[$k][$i]", $row[$k][$i]) ?>
                    </div>
                <?php endforeach ?>
            <?php endforeach ?>
        </div>-->
    </div>
<?php
}

function sln_holiday_row($prefix, $row, $rulenumber = 'New') {
	?>
	<div class="col-xs-12 sln-booking-rule">
	<h2 class="sln-box-title"><?php _e('Rule','salon-booking-system');?> <strong><?php echo $rulenumber; ?></strong></h2>
	<div class="row">
		<div class="col-xs-12 col-md-4 sln-slider-wrapper">
			<h6 class="sln-fake-label"><?php _e('Start on', 'salon-booking-system') ?></h6>
			<?php $dateFormat = SLN_Enum_DateFormat::getPhpFormat(SLN_Plugin::getInstance()->getSettings()->get('date_format')); ?>
			<?php $dateFrom = isset($row['from']) ? $row['from'] : date($dateFormat); ?>
			<?php $dateFrom = DateTime::createFromFormat($dateFormat, $dateFrom); ?>

            <div class="sln_datepicker"><?php SLN_Form::fieldJSDate($prefix."[from]", $dateFrom) ?></div>
		</div>
		<div class="col-xs-12 col-md-4 sln-slider-wrapper">
			<h6 class="sln-fake-label"><?php _e('End on', 'salon-booking-system') ?></h6>
			<?php $dateTo = isset($row['to']) ? $row['to'] : date($dateFormat); ?>
			<?php $dateTo = DateTime::createFromFormat($dateFormat, $dateTo); ?>
            <div class="sln_datepicker"><?php SLN_Form::fieldJSDate($prefix."[to]", $dateTo) ?></div>
		</div>
        <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
            <button class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash" data-collection="remove"><?php echo __('Remove', 'salon-booking-system')?></button>
        </div>
	</div>
	</div>
	<?php
}
?>


<div class="sln-tab" id="sln-tab-booking">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Availability mode','salon-booking-system');?> <span><?php _e('Select your favourite booking system mode.', 'salon-booking-system') ?></span></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8">
            <div class="sln-radiobox">
            <?php $field = "salon_settings[availability_mode]"; ?>
            <?php echo SLN_Form::fieldRadioboxGroup(
                $field,
                SLN_Enum_AvailabilityModeProvider::toArray(),
                $this->getOpt('availability_mode'),
                array(),
                true
            ) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-input-help"><?php _e('You need to choose which kind of booking algorithm want to use for your salon. Click over "i" icon to get more information about this option.','salon-booking-system');?></p>
        </div>
    </div>
    <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-md-4 col-sm-8 col-xs-12">
       <h5><?php _e('The BASIC one sets a fixed duration for each booking and it doesn\'t care about the number and the duration of the booked services. <br />You are able to set a fixed duration using the "Session average duration" option.<br /><br />The ADVANCED mode is more complex and complete as it takes in count the duration of each services booked for every single reservation. <br />We suggest to use this one.','salon-booking-system');?></h5>
        </div>
        </div>
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
    </div>
    </div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title"><?php _e('Customers per session','salon-booking-system');?> <span class="block"><?php _e('How many people you can attend during a single time/session?', 'salon-booking-system') ?></span></h2>
    <div class="row">
            <div class="col-xs-12 form-group sln-select  sln-select--info-label">
            <!--<label for="salon_settings_parallels_hour">Customers per time/session</label>-->
            <div class="row">
            <div class="col-xs-4">
            <?php echo SLN_Form::fieldNumeric(
                    "salon_settings[parallels_hour]",
                    $this->getOpt('parallels_hour'),
                    array('min' => 1, 'max' => 20)
                ) ?>
            </div>
            <div class="col-xs-8 sln-label--big"><label for="salon_settings_sms_remind_interval">Customers per session</label></div>
            <div class="col-xs-12">
            <p class="help-block"><?php _e(
                    'Set these options carefully because it will affect the number of bookings you can accept for the same <strong>time/session</strong>.',
                    'salon-booking-system'
                ) ?></p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title"><?php _e('Session average duration','salon-booking-system');?> <span class="block"><?php _e('Define your time-session average duration', 'salon-booking-system') ?></span></h2>
    <div class="row">
            <div class="col-xs-12 form-group sln-select  sln-select--info-label">
            <!--<label for="salon_settings_parallels_hour">Customers per time/session</label>-->
            <div class="row">
            <div class="col-xs-4">
                <?php $field = "salon_settings[interval]"; ?>
                <?php echo SLN_Form::fieldSelect(
                    $field,
                    SLN_Enum_Interval::toArray(),
                    $this->getOpt('interval') ? $this->getOpt('interval') : 15
                ) ?>
            </div>
            <div class="col-xs-8 sln-label--big"><label for="<?php echo SLN_Form::makeID($field) ?>"><?php _e('Minutes per session','salon-booking-system');?></label></div>
            <div class="col-xs-12">
            <p class="help-block"><?php _e(
                    'Set these options carefully because it will affect the number of bookings you can accept for the same <strong>time/session</strong>.',
                    'salon-booking-system'
                ) ?></p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="sln-box sln-box--main sln-box--main--small">
        <h2 class="sln-box-title"><?php _e('Offset between reservations','salon-booking-system');?></h2>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 form-group sln-checkbox">
                <?php $this->row_input_checkbox(
                    'reservation_interval_enabled',
                    __('Enable offset', 'salon-booking-system'),
                    array('help' => __('Select this option to add an OFF interval between two sequencial reservations','salon-booking-system'))
                ); ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
                <label><?php _e('Offset duration','salon-booking-system');?></label>
                <?php $field = "salon_settings[minutes_between_reservation]"; ?>
                <?php echo SLN_Form::fieldSelect(
                    $field,
                    array(
                        '5'   => '5m',
                        '10'  => '10m',
                        '15'  => '15m',
                        '20'  => '20m',
                        '25'  => '25m',
                        '30'  => '30m',
                        '35'  => '35m',
                        '40'  => '40m',
                        '45'  => '45m',
                        '50'  => '50m',
                        '55'  => '55m',
                        '60'  => '1h',
                        '65'  => '1h 5m',
                        '70'  => '1h 10m',
                        '75'  => '1h 15m',
                        '80'  => '1h 20m',
                        '85'  => '1h 25m',
                        '90'  => '1h 30m',
                        '95'  => '1h 35m',
                        '100' => '1h 40m',
                        '105' => '1h 45m',
                        '110' => '1h 50m',
                        '115' => '1h 55h',
                        '120' => '2h',
                    ),
                    $this->getOpt('minutes_between_reservation'),
                    array(),
                    true
                ) ?>
                <p class="help-block"><?php _e('How many minutes lasts this Offset?', 'salon-booking-system') ?></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help"><?php _e('Note that during the Offset interval new reservations will not be available.','salon-booking-system');?></p>
            </div>
        </div>
    </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Online bookings timing','salon-booking-system');?> <span>-</span></h2>
    <div class="sln-box--sub row">
    <div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Booking time range <span>Define the time range in wich customers may book an appointment</span><','salon-booking-system');?>/h2></div>
            <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select  sln-select--info-label">
            <label for="salon_settings_hours_before_from"><?php _e('Range starts','salon-booking-system');?></label>
            <div class="row">
            <div class="col-xs-7">
            <?php $field = "salon_settings[hours_before_from]"; ?>
                    <?php echo SLN_Form::fieldSelect(
                        $field,
                        SLN_Func::getIntervalItems(),
                        $this->getOpt('hours_before_from'),
                        array(),
                        true
                    ) ?>
            </div>
            <div class="col-xs-5 sln-label--big"><label for="salon_settings_hours_before_from"><?php _e('Minimum','salon-booking-system');?></label></div>
            </div>
        </div>
            <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select   sln-select--boxedoptions sln-select--info-label">
            <label for="salon_settings_hours_before_to"><?php _e('Range ends','salon-booking-system');?></label>
            <div class="row">
            <div class="col-xs-7">
            <?php $field = "salon_settings[hours_before_to]"; ?>
                    <?php echo SLN_Form::fieldSelect(
                        $field,
                        SLN_Func::getIntervalItems(),
                        $this->getOpt('hours_before_to'),
                        array(),
                        true
                    ) ?>
            </div>
            <div class="col-xs-5 sln-label--big"><label for="salon_settings_hours_before_to"><?php _e('Maximum','salon-booking-system');?></label></div>
            </div>
        </div>
            <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help"><?php _e('If you want for example that your customer can make a reservation up to two days before the appointment date and from a maximum of one month before the appointment date use this range options to set your desired rule.','salon-booking-system');?></p>
            </div>
    </div>
    <div class="sln-box--sub sln-booking-rules row">
    <?php
        $key            = 'available';
        $label          = __('On-line booking available days', 'salon-booking-system');
        $availabilities = $this->getOpt('availabilities');
        ?>
    <div class="col-xs-12">
    <h2 class="sln-box-title"><?php echo $label ?> <span class="block"><?php _e('The following rules, should represent your real timetable. <br />Leave blank if you want bookings available everydays at every hour', 'salon-booking-system') ?></span></h2>
    </div>
    <div id="sln-booking-rules-wrapper">
        <?php
            $n = 0;
            foreach ($availabilities as $k => $row):
            $n++;
        ?>
        <?php sln_availability_row("salon_settings[availabilities][$k]", $row, $n); ?>
        <?php endforeach ?>
    </div>
    <div class="col-xs-12">
    <button data-collection="addnew" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--file"><?php _e(
                        'Add new','salon-booking-system'
                    ) ?>
                </button>
    </div>
    <div data-collection="prototype" data-count="<?php echo count($availabilities) ?>">
        <?php sln_availability_row("salon_settings[availabilities][__new__]", $row); ?>
    </div>
    </div>

    <div class="sln-box--sub sln-booking-holiday-rules row">
        <?php
        $row = null;
        $key            = 'holiday';
        $label          = __('Holidays days', 'salon-booking-system');
        $holidays = $this->getOpt('holidays');
        ?>
        <div class="col-xs-12">
            <h2 class="sln-box-title"><?php echo $label ?> <span class="block"><?php _e('Set one or more rules for your holidays.<br /> Users will not be able to make reservation during these periods', 'salon-booking-system') ?></span></h2>
        </div>
        <div id="sln-booking-holiday-rules-wrapper">
            <?php if(is_array($holidays)): ?>
		        <?php
	            $n = 0;
	            foreach ($holidays as $k => $row):
	                $n++;
	                ?>
	                <?php sln_holiday_row("salon_settings[holidays][$k]", $row, $n); ?>
	            <?php endforeach ?>
            <?php endif ?>
        </div>
        <div class="col-xs-12">
            <button data-collection="addnewholiday" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--file"><?php _e(
                    'Add new','salon-booking-system'
                ) ?>
            </button>
        </div>
        <div data-collection="prototype" data-count="<?php echo count($holidays) ?>">
            <?php sln_holiday_row("salon_settings[holidays][__new__]", $row); ?>
        </div>
    </div>

    <div class="clearfix"></div>
</div>
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Booking manual confirmation','salon-booking-system');?></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 form-group sln-checkbox">
            <?php $this->row_input_checkbox(
                    'confirmation',
                    __('Booking confirmation', 'salon-booking-system'),
                    array('help' => __('Select this option to manually confirm each booking.','salon-booking-system'))
                ); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
            <?php $this->row_input_page('pay', __('Booking page', 'salon-booking-system')); ?>
            <p class="help-block"><?php _e('Select a page with the booking form.', 'salon-booking-system') ?></p>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
            <?php $this->row_input_page('thankyou', __('Thank you page', 'salon-booking-system')); ?>
            <p class="help-block"><?php _e('Select a page where to redirect your users after booking completition.', 'salon-booking-system') ?></p>
        </div>

    </div>
</div>


<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('User booking cancellation','salon-booking-system');?></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 form-group sln-checkbox">
            <?php $this->row_input_checkbox(
                    'cancellation_enabled',
                    __('Booking cancellation', 'salon-booking-system'),
                    array('help' => __('Select this option if you want your users able to cancel a booking from the front-end.','salon-booking-system'))
                ); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
                <label><?php _e('Time in advance','salon-booking-system');?></label>
                <?php $field = "salon_settings[hours_before_cancellation]"; ?>
                <?php echo SLN_Form::fieldSelect(
                    $field,
                    array(
                        '1' => '1h',
                        '5' => '5h',
                        '12' => '12h',
                        '24' => '24h',
                        '48' => '48h',
                        '72' => '72h',
                    ),
                    $this->getOpt('hours_before_cancellation'),
                    array(),
                    true
                ) ?>
                <p class="help-block"><?php _e('How many hours before the appointment the cancellation is still allowed', 'salon-booking-system') ?></p>
            </div>
        <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
            <p class="sln-input-help"><?php _e('Users once logged in inside the MY ACCOUNT BOOKING page will be able to see the list of their upcoming reservations and eventually Cancel them. An email notification will be sent to you and to the customers.','salon-booking-system');?></p>
        </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Pause online booking service <span class="block">If ON the online booking form will be disabled and your users will see a message.</span>','salon-booking-system');?></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-switch">
            <h6 class="sln-fake-label"><?php _e('Online Booking status','salon-booking-system');?></h6>
            <!--<input type="checkbox" name="salon_settings[disabled]" id="salon_settings_disabled" value="1">
                <label class="sln-switch-btn" for="salon_settings_disabled"  data-on="On" data-off="Off"></label>
                <label class="sln-switch-text"  for="salon_settings_disabled" data-on="Online Booking is active" 
                data-off="Online Booking is paused"></label>-->
                            <?php SLN_Form::fieldCheckboxSwitch(
                                "salon_settings[disabled]",
                                $this->getOpt('disabled'),
                                $labelOn = "Online Booking is disabled",
                                $labelOff = "Online Booking is active"
                            ) ?>
            </div>
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-input--simple">
        <label for="<?php echo SLN_form::makeID("salon_settings[disabled_message]") ?>"><?php _e(
                                'Message on disabled booking',
                                'salon-booking-system'
                            ) ?></label>
                        <?php
                            $admin_email = get_bloginfo('admin_email');
                            SLN_Form::fieldTextarea(
                            "salon_settings[disabled_message]",
                            $this->getOpt('disabled_message'),
                                array(
                                'attrs' => array(
                                    'placeholder' => __('Booking is not available at the moment, please contact us at ') . $admin_email,
                                    'rows'        => 5,
                                    'class'       => 'form-control',
                                    'style'       => 'width: 100%;'
                                )
                            )
                        ) ?>
        </div>
    </div>
</div>


</div>


