<?php
$plugin = SLN_Plugin::getInstance();
?>

    <div class="sln-box sln-box--main">
        <?php echo $plugin->loadView('settings/_tab_booking_availability'); ?>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="sln-box sln-box--main sln-box--main--small">
                <h2 class="sln-box-title"><?php _e('Customers per session', 'salon-booking-system'); ?> <span
                        class="block"><?php _e(
                            'How many people you can attend during a single time/session?',
                            'salon-booking-system'
                        ) ?></span></h2>
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
                            <div class="col-xs-8 sln-label--big"><label for="salon_settings_sms_remind_interval">Customers
                                    per session</label></div>
                            <div class="col-xs-12">
                                <p class="help-block sln-input-help"><?php _e(
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
                <h2 class="sln-box-title"><?php _e('Session average duration', 'salon-booking-system'); ?> <span
                        class="block"><?php _e(
                            'This option will affect the minimum hour fraction of the Time picker.',
                            'salon-booking-system'
                        ) ?></span></h2>
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
                            <div class="col-xs-8 sln-label--big"><label
                                    for="<?php echo SLN_Form::makeID($field) ?>"><?php _e(
                                        'Minutes per session',
                                        'salon-booking-system'
                                    ); ?></label></div>
                            <div class="col-xs-12">
                                <p class="help-block sln-input-help"><?php _e(
                                        'We suggest you to set this option accordingly with the duration of your shortest service.',
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
        <?php echo $plugin->loadView('settings/_tab_booking_form_steps_alt_order', array('helper' => $this)); ?>
    </div>
    
    <div class="row">
        <?php echo $plugin->loadView('settings/_tab_booking_offset', array('helper' => $this)); ?>
    </div>

    <div class="sln-box sln-box--main">
        <?php echo $plugin->loadView('settings/_tab_booking_timing'); ?>
        <?php echo $plugin->loadView(
            'settings/_tab_booking_rules',
            array(
                'availabilities' => $plugin->getSettings()->get('availabilities'),
                'base' => 'salon_settings[availabilities]'
            )
        ); ?>
        <?php echo $plugin->loadView(
            'settings/_tab_booking_holiday_rules',
            array(
                'holidays' => $plugin->getSettings()->get('holidays'),
                'base' => 'salon_settings[holidays]'
            )
        ); ?>
        <div class="clearfix"></div>
    </div>

    <div class="sln-box sln-box--main">
        <?php echo $plugin->loadView('settings/_tab_booking_confirmation', array('helper' => $this)); ?>
    </div>

    <div class="sln-box sln-box--main">
        <?php echo $plugin->loadView('settings/_tab_booking_cancellation', array('helper' => $this)); ?>
    </div>

    <div class="sln-box sln-box--main">
        <?php echo $plugin->loadView('settings/_tab_booking_status'); ?>
    </div>

