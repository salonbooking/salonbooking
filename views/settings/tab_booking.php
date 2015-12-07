<?php
/**
 * @param $this SLN_Admin_Settings
 */
function sln_availability_row($prefix, $row, $rulenumber)
{
    ?>
    <div class="col-xs-12 sln-booking-rule">
    <h2 class="sln-box-title">Rule <strong><?php echo $rulenumber; ?></strong></h2>
    <h6 class="sln-fake-label">Available days</h6>
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
             <h6 class="sln-fake-label">First shift</h6>
            <div class="sln-slider">
            <div class="sliders_step1 col col-slider"><div class="slider-range"></div></div>
            <div class="col col-time"><span class="slider-time">9:00</span> to <span class="slider-time2">16:00</span>
            <input type="text" name="salon_settings[gen_name]" id="" value="11:45" class="slider-time-hidden-input">
            <input type="text" name="salon_settings[gen_name]" id="" value="20:30" class="slider-time-hidden-input2">
            </div>
            <div class="clearfix"></div>
            </div>
             <h6 class="sln-fake-label">Second shift</h6>
            <div class="sln-slider">
            <div class="sliders_step1 col col-slider"><div class="slider-range"></div></div>
            <div class="col col-time">
            <span class="slider-time">9:00</span> to <span class="slider-time2">16:00</span>
            <input type="text" name="salon_settings[gen_name]" id="" value="8:45" class="slider-time-hidden-input">
            <input type="text" name="salon_settings[gen_name]" id="" value="16:00" class="slider-time-hidden-input2">
            </div>
            <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
        <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
            <button class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash" data-collection="remove"><?php echo __('Remove', 'sln')?></button>
        </div>

    </div>
        <!--<div class="second-row">
            <?php foreach (array(0, 1) as $i) : ?>
                <?php foreach (array('from' => __('From', 'sln'), 'to' => __('To', 'sln')) as $k => $v) : ?>
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

?>


<div class="sln-tab" id="sln-tab-booking">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Availability mode <span><?php _e('Select your availability mode', 'sln') ?></span></h2>
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
            <p class="sln-input-help">Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
    </div>
    <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-md-4 col-sm-8 col-xs-12">
       <h5>Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</h5>
        </div>
        </div>
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
    </div>
    </div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title">Customers per session <span class="block"><?php _e('How many people you can attend during a single time/session?', 'sln') ?></span></h2>
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
                    'sln'
                ) ?></p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title">Session average duration <span class="block"><?php _e('Define your time-session average duration', 'sln') ?></span></h2>
    <div class="row">
            <div class="col-xs-12 form-group sln-select  sln-select--info-label">
            <!--<label for="salon_settings_parallels_hour">Customers per time/session</label>-->
            <div class="row">
            <div class="col-xs-4">
                <?php $field = "salon_settings[interval]"; ?>
                <?php echo SLN_Form::fieldSelect(
                    $field,
                    array('5', '10', '15', '30', '60'),
                    $this->getOpt('interval') ? $this->getOpt('interval') : 15
                ) ?>
            </div>
            <div class="col-xs-8 sln-label--big"><label for="<?php echo SLN_Form::makeID($field) ?>">Minutes per session</label></div>
            <div class="col-xs-12">
            <p class="help-block"><?php _e(
                    'Set these options carefully because it will affect the number of bookings you can accept for the same <strong>time/session</strong>.',
                    'sln'
                ) ?></p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Online bookings timing <span>Donec vestibulum sagittis lorem, ut maximus ex consequat non.</span></h2>
    <div class="sln-box--sub row">
    <div class="col-xs-12"><h2 class="sln-box-title">Booking time range <span>Define the time range in wich customers may book an appointment</span></h2></div>
            <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select  sln-select--info-label">
            <label for="salon_settings_hours_before_from">Range starts</label>
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
            <div class="col-xs-5 sln-label--big"><label for="salon_settings_hours_before_from">Minimum</label></div>
            </div>
        </div>
            <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select   sln-select--boxedoptions sln-select--info-label">
            <label for="salon_settings_hours_before_to">Range ends</label>
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
            <div class="col-xs-5 sln-label--big"><label for="salon_settings_hours_before_to">Maximum</label></div>
            </div>
        </div>
            <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
            </div>
    </div>
    <div class="sln-box--sub sln-booking-rules row">
    <?php
        $key            = 'available';
        $label          = __('On-line booking available days', 'sln');
        $availabilities = $this->getOpt('availabilities');
        ?>
    <div class="col-xs-12">
    <h2 class="sln-box-title"><?php echo $label ?> <span class="block"><?php _e('The following rules, should represent your real timetable. <br />Leave blank if you want bookings available everydays at every hour', 'sln') ?></span></h2>
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
                        'Add new','sln'
                    ) ?>
                </button>
    </div>
    <div data-collection="prototype" data-count="<?php echo count($availabilities) ?>">
        <?php sln_availability_row("salon_settings[availabilities][__new__]", $row); ?>
    </div>
    </div>


    <div class="clearfix"></div>
</div>
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Booking manual confirmation</h2>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 form-group sln-checkbox">
            <?php $this->row_input_checkbox(
                    'confirmation',
                    __('Booking confirmation', 'sln'),
                    array('help' => __('Select this option to manually confirm each booking.','sln'))
                ); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
            <?php $this->row_input_page('thankyou', __('Thank you page', 'sln')); ?>
            <p class="help-block"><?php _e('Select a page where to redirect your users after booking completition.', 'sln') ?></p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
            <?php $this->row_input_page('pay', __('Pay page', 'sln')); ?>
            <p class="help-block"><?php _e('Select a page where to redirect your users for payment.', 'sln') ?></p>
        </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Pause booking service <span class="block">If checked the online booking form will be disabled and your users will see a message.</span></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-switch">
            <h6 class="sln-fake-label">Online Booking status</h6>
            <!--<input type="checkbox" name="salon_settings[disabled]" id="salon_settings_disabled" value="1">
                <label class="sln-switch-btn" for="salon_settings_disabled"  data-on="On" data-off="Off"></label>
                <label class="sln-switch-text"  for="salon_settings_disabled" data-on="Online Booking is active" 
                data-off="Online Booking is paused"></label>-->
                            <?php SLN_Form::fieldCheckboxSwitch(
                                "salon_settings[disabled]",
                                $this->getOpt('disabled'),
                                $labelOn = "Online Booking is active",
                                $labelOff = "Online Booking is paused"
                            ) ?>
            </div>
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-input--simple">
        <label for="salon_settings_disabled_message">Message on disabled booking</label>
               <textarea name="salon_settings[disabled_message]" id="salon_settings_disabled_message" placeholder="Write a message" rows="5">Booking is not available at the moment, please contact us at me@nicovece.com</textarea>
        </div>
    </div>
</div>


</div>


