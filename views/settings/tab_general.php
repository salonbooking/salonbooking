
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Salon information','salon-booking-system'); ?></h2>
    <div class="row">
        <div class="col-sm-4 form-group sln-input--simple">
            <?php
            $this->row_input_text(
                'gen_name',
                __('Your salon name', 'salon-booking-system'),
                array(
                    'help' => sprintf(
                        __('Leaving this field empty will cause the default site name <strong>(%s)</strong> to be used', 'salon-booking-system'),
                        get_bloginfo('name')
                    )
                )
            );
            ?>
        </div>
        <div class="col-sm-4 form-group sln-input sln-input--simple">
        <?php
            $this->row_input_email(
                'gen_email',
                __('Salon contact e-mail', 'salon-booking-system'),
                array(
                    'help' => sprintf(
                        __('Leaving this field empty will cause the default site email  <strong>(%s)</strong> to be used', 'salon-booking-system'),
                        get_bloginfo('admin_email')
                    )
                )
            );
            ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
            <?php $this->row_input_text('gen_phone', __('Salon telephone number', 'salon-booking-system')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-8 form-group sln-input--simple">
            <?php $this->row_input_textarea(
                'gen_address',
                __('Salon address', 'salon-booking-system'),
                array(
                    'textarea' => array(
                        'attrs' => array(
                            'rows' => 5,
                            'placeholder' => 'write your address'
                        )
                    ),
                    'help' => __('Provide the full address of your Salon','salon-booking-system')
                )
            ); ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple sln-logo-box">
            <label for="gen_logo"><?php _e('Upload your logo','salon-booking-system') ?></label>
            <?php if ($this->getOpt('gen_logo')): ?>
                <div id="logo" class="preview-logo">
                    <div class="preview-logo-img">
                        <img src="<?php echo wp_get_attachment_image_url($this->getOpt('gen_logo'), 'sln_gen_logo'); ?>">
                    </div>
                    <button type="button" class="btn btn-link" data-action="delete-logo" data-target-remove="logo"
                            data-target-reset="salon_settings_gen_logo" data-target-show="select_logo"><?php _e('remove this image', 'salon-booking-system'); ?></button>
                </div>
            <?php endif ?>

            <div id="select_logo" class="select-logo <?php echo $this->getOpt('gen_logo') ? 'hide' : '' ?>" data-action="select-logo" data-target="gen_logo">
                <span class="dashicons dashicons-upload"></span>
            </div>

            <div class="hide">
                <input type="file" name="gen_logo" id="gen_logo" data-action="select-file-logo" accept="image/png">
                <input type="hidden" name="salon_settings[gen_logo]" id="salon_settings_gen_logo" value="<?php echo $this->getOpt('gen_logo'); ?>">
            </div>

            <p class="help-block"><?php _e('Use a transparent png file','salon-booking-system') ?></p>
        </div>
    </div>
    <!--
THIS BOX MUST BE HIDDEN IF NOT IN USE
<div class="sln-box-info">
    <div class="sln-box-info-trigger">
        <button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button>
    </div>
    <div class="sln-box-info-content row">
        <div class="col-md-4 col-sm-8 col-xs-12">
            <h5><?php _e('-', 'salon-booking-system') ?></h5>
        </div>
    </div>
    <div class="sln-box-info-trigger">
        <button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button>
    </div>
</div>
-->
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Booking notes','salon-booking-system') ?></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8 form-group sln-input--simple">
            <?php $this->row_input_textarea(
                'gen_timetable',
                __('Use this field to provide your customers important infos about terms and conditions of their reservation.', 'salon-booking-system'),
                array(
                    'help' => 'Will be displayed on checkout page before booking completition.',
                    'textarea' => array(
                        'attrs' => array(
                            'rows' => 5,
                            'placeholder' => "e.g. In case of delay we will take your seat for 15 minutes, then your booking priority will be lost"
                        )
                    )
                )
            ); ?>
        </div>
        <div class="col-md-4 col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-input-help"><?php __('-','salon-booking-system') ?></p>
        </div>
    </div>
    <!-- SE SERVONO MAGGIORI INFO
    <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-md-4 col-sm-8 col-xs-12">
       <h5>Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</h5>
        </div>
        </div>
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
    </div>
    -->
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Assistant selection','salon-booking-system') ?></h2>
    <div class="row">
        <div class="col-sm-8 col-md-6 form-group">
        <div class="sln-checkbox">
            <?php $this->row_input_checkbox('attendant_enabled', __('Enable assistant selection', 'salon-booking-system')); ?>
            <p class="sln-input-help"></p>
        </div>
        </div>
        <div class="col-sm-4 form-group sln-box-maininfo align-top">
                <p class="sln-box-info"><?php _e('Let your customers choose their favourite staff member.', 'salon-booking-system') ?></p>
            </div>
    <!-- .row // END -->
    </div>
    <div class="row">
    <div class="col-sm-10 col-md-6 form-group">
            <div class="sln-checkbox">
                <?php $this->row_input_checkbox('m_attendant_enabled', __('Enable multiple assistants selection', 'salon-booking-system')); ?>
                <p class="sln-input-help"><?php _e('Users can select more than one assistants for their booked services. Please set with care the "execution order" inside your services section.', 'salon-booking-system') ?></p>
            </div>
        </div>
    <div class="col-sm-10 col-md-6 form-group">
        <div class="sln-checkbox">
            <?php $this->row_input_checkbox('attendant_email', __('Enable assistant email on new bookings', 'salon-booking-system')); ?>
            <p><?php _e('Assistants will receive an e-mail when selected for a new booking.', 'salon-booking-system') ?></p>
        </div>
        </div>
        <!-- .row // END -->
        </div>

        <div class="row">
        <div class="col-sm-6 col-md-6 form-group">
            <a href="<?php echo get_admin_url() . 'edit.php?post_type=sln_attendant'; ?> "
            class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--assistants"><?php _e('Manage staff','salon-booking-system') ?></a>
            <p><?php _e('If you need to add or manage your staff members.','salon-booking-system'); ?></p>
        </div>
        <div class="col-sm-4 form-group sln-box-maininfo">
            <p class="sln-input-help"><?php __('-','salon-booking-system') ?></p>
        </div>
    </div>
</div>
<div class="sln-box sln-box--main">
    <?php echo SLN_Plugin::getInstance()->loadView('settings/_tab_general_sms', array('helper' => $this)); ?>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Email services','salon-booking-system') ?></h2>
    <div class="sln-box--sub row">
        <div class="col-xs-12">
            <h2 class="sln-box-title"><?php _e('Email Notifications service','salon-booking-system') ?></h2>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-sm-8 col-md-6 form-group sln-checkbox">
                    <?php $this->row_input_checkbox('email_remind', __('Remind the appointment to the client with an Email', 'salon-booking-system')); ?>
                </div>
                <div class="col-sm-4 col-md-6 form-group sln-select  sln-select--info-label">
                    <label for="salon_settings_email_remind_interval"><?php _e('Email Timing','salon-booking-system') ?></label>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6">
                            <?php $field = "salon_settings[email_remind_interval]"; ?>
                            <?php echo SLN_Form::fieldSelect(
                                $field,
                                SLN_Func::getIntervalItemsShort(),
                                $this->getOpt('email_remind_interval'),
                                array(),
                                true
                            ) ?>
                        </div>
                        <div class="col-xs-6 col-sm-6 sln-label--big">

                            <label for="salon_settings_email_remind_interval"><?php _e('Before the appointment','salon-booking-system') ?></label></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 form-group sln-input--simple">
                    <?php $this->row_input_text('email_subject', __('Email Subject', 'salon-booking-system')); ?>
                    <p class="sln-input-help"><?php _e('You can use [DATE], [TIME], [SALON NAME]','salon-booking-system') ?></p>
                </div>
            </div>
        
    
<!--
THIS BOX MUST BE HIDDEN IF NOT IN USE
<div class="sln-box-info">
    <div class="sln-box-info-trigger">
        <button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button>
    </div>
    <div class="sln-box-info-content row">
        <div class="col-md-4 col-sm-8 col-xs-12">
            <h5><?php _e('-', 'salon-booking-system') ?></h5>
        </div>
    </div>
    <div class="sln-box-info-trigger">
        <button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button>
    </div>
</div>
-->

            <div class="row">
                <div class="col-sm-8 form-group sln-input--simple">
                    <?php $this->row_input_textarea('booking_update_message', __('Customize the booking reminder message', 'salon-booking-system')); ?>
                    <p class="sln-input-help"><?php _e('You can use [NAME], [SALON NAME]','salon-booking-system') ?></p>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Automatic follow-up','salon-booking-system') ?></h2>
    <div class="sln-box--sub row">
        <div class="col-xs-12">
            <h2 class="sln-box-title"><?php _e('Enable reservation follow-up','salon-booking-system') ?></h2>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-6 col-sm-3 col-md-3 form-group sln-checkbox">
                    <?php $this->row_input_checkbox('follow_up_email', __('by Email', 'salon-booking-system')); ?>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3 form-group sln-checkbox">
                    <?php $this->row_input_checkbox('follow_up_sms', __('by SMS', 'salon-booking-system')); ?>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 form-group sln-select  sln-select--info-label">
                    <label for="salon_settings_follow_up_interval"><?php _e('Timing','salon-booking-system') ?></label>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-8 col-lg-6">
                            <?php SLN_Form::fieldSelect(
                                'salon_settings[follow_up_interval]',
                                array(
                                    '+1 days'   => '1 ' . __('day', 'salon-booking-system'),
                                    '+2 days'   => '2 ' . __('days', 'salon-booking-system'),
                                    '+3 days'   => '3 ' . __('days', 'salon-booking-system'),
                                    '+4 days'   => '4 ' . __('days', 'salon-booking-system'),
                                    '+5 days'   => '5 ' . __('days', 'salon-booking-system'),
                                    '+1 weeks'  => '1 ' . __('week', 'salon-booking-system'),
                                    '+2 weeks'  => '2 ' . __('weeks', 'salon-booking-system'),
                                    '+3 weeks'  => '3 ' . __('weeks', 'salon-booking-system'),
                                    '+1 months' => '1 ' . __('month', 'salon-booking-system'),
                                    'custom'    => __('Customer habit', 'salon-booking-system'),
                                ),
                                $this->getOpt('follow_up_interval'),
                                array(),
                                true
                            ) ?>
                            <div class="row">
                                <div class="col-xs-offset-1 col-md-offset-1">
                                    <p id="salon_settings_follow_up_interval_custom_hint" class="help-block"><?php _e('We\'ll send a message two days before the <strong>next estimated booking</strong>','salon-booking-system') ?></p>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-6 sln-label--big">
                            <label id="salon_settings_follow_up_interval_hint" for="salon_settings_follow_up_interval"><?php _e('After the last appointment','salon-booking-system') ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 form-group sln-input--simple">
                    <?php $this->row_input_textarea(
                        'follow_up_message',
                        __('Custom message (max 150 characters)', 'salon-booking-system'),
                        array(
                            'textarea' => array(
                                'attrs' => array(
                                    'style'       => 'height: 140px;',
                                    'maxlength'   => 150,
                                    'placeholder' => 'write message'
                                )
                            )
                        )
                    ); ?>
                </div>
                <div class="col-sm-4 col-md-4 sln-box-maininfo  align-top">
                    <p class="sln-input-help"><?php _e('You can use this dynamic tags: [NAME], [SALON NAME]','salon-booking-system') ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="sln-box-info">
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
        <div class="sln-box-info-content row">
            <div class="col-md-4 col-sm-8 col-xs-12">
                <h5><?php _e('-','salon-booking-system') ?></h5>
            </div>
        </div>
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Automatic feedback reminder','salon-booking-system') ?></h2>
    <div class="row">
        <div class="col-xs-12 form-group  sln-checkbox">
            <?php $this->row_input_checkbox('feedback_reminder', __('Enable feedback submission request', 'salon-booking-system')); ?>
            <p><?php _e('If enabled an email will be sent automatically to the customer one day after the last visit to the salon.', 'salon-booking-system') ?></p>
        </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Date and Time settings','salon-booking-system') ?></h2>
    <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_date_format"><?php _e('Date Format', 'salon-booking-system') ?></label>
                <?php $field = "salon_settings[date_format]"; ?>
                <?php echo SLN_Form::fieldSelect(
                    $field,
                    SLN_Enum_DateFormat::toArray(),
                    $this->getOpt('date_format'),
                    array(),
                    true
                ) ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_time_format"><?php _e('Time Format', 'salon-booking-system') ?></label>
                <?php $field = "salon_settings[time_format]"; ?>
                    <?php echo SLN_Form::fieldSelect(
                        $field,
                        SLN_Enum_TimeFormat::toArray(),
                        $this->getOpt('time_format'),
                        array(),
                        true
                    ) ?>
            </div>
            <div class="col-sm-6 col-md-4 sln-box-maininfo align-top">
                <p class="sln-box-info"><?php _e('Select your favourite date and time format. Do you need another format? Send an email to support@wpchef.it','salon-booking-system') ?></p>
            </div>
            </div>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-4 form-group sln-select ">
            <label for="salon_settings_week_start"><?php _e('Start week on', 'salon-booking-system') ?></label>
            <?php $field = "salon_settings[week_start]"; ?>
            <?php echo SLN_Form::fieldSelect(
                $field,
                SLN_Enum_DaysOfWeek::toArray(),
                $this->getOpt('week_start'),
                array(),
                true
            ) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-md-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title"><?php _e('Ajax steps <span>This allows loading steps via ajax</span>','salon-booking-system') ?></h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
            <?php $this->row_input_checkbox('ajax_enabled', __('Enable ajax steps', 'salon-booking-system')); ?>
            <p><?php _e('This allows loading steps via ajax for a more smooth booking form transition.', 'salon-booking-system') ?></p>
            </div>
        </div>
    </div>
    </div>
    <div class="col-sm-8 col-md-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title"><?php _e('Disable bootstrap assets','salon-booking-system') ?></h2>
    <div class="row">
            <div class="col-md-6 form-group  sln-checkbox">
                <?php $this->row_input_checkbox('no_bootstrap', __('CSS on front-end', 'salon-booking-system')); ?>
                <p class="sln-input-help"><?php _e('Use it in case of conflicts with your theme','salon-booking-system') ?></p>
            </div>
            <div class="col-md-6 form-group  sln-checkbox">
                <?php $this->row_input_checkbox('no_bootstrap_js', __('JS on front-end', 'salon-booking-system')); ?>
                <p class="sln-input-help"><?php _e('Use it in case of conflicts with your theme','salon-booking-system') ?></p>
            </div>
        </div>
    </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-md-6">
        <div class="sln-box sln-box--main sln-box--main--small">
            <h2 class="sln-box-title"><?php _e('Administration rule','salon-booking-system') ?></h2>
            <div class="row">
                <div class="col-xs-12 form-group  sln-checkbox">
                    <?php $this->row_input_checkbox('editors_manage_cap', __('Enable Editors as administrator', 'salon-booking-system')); ?>
                    <p><?php _e('This allows Wordpress users with Editor role to manage the Salon Booking section.', 'salon-booking-system') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>


