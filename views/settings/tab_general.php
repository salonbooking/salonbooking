<div class="sln-tab" id="sln-tab-general">
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Salon\'s informations','salon-booking-system'); ?> <span>-</span></h2>
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
            $this->row_input_text(
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
                    )
                )
            ); ?>
        </div>
        <div class="col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-input-help"><?php __('Provide the full address of your Salon','salon-booking-system') ?></p>
        </div>
    </div>
    <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-md-4 col-sm-8 col-xs-12">
       <h5><?php __('-','salon-booking-system') ?></h5>
        </div>
        </div>
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
    </div>
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
    <h2 class="sln-box-title"><?php _e('Assistant selection <span> - </span>','salon-booking-system') ?></h2>
    <div class="row">
        <div class="col-sm-6 form-group">
        <div class="sln-checkbox">
            <?php $this->row_input_checkbox('attendant_enabled', __('Enable assistant selection', 'salon-booking-system')); ?>
            <p class="sln-input-help"><?php echo sprintf(__('Let your customers choose their favourite staff member.', 'salon-booking-system'),
                    get_admin_url().'edit.php?post_type=sln_attendant') ?></p>
        </div>
        </div>
        <div class="col-sm-6 form-group">
        <div class="sln-checkbox">
            <?php $this->row_input_checkbox('attendant_email', __('Enable assistant email on new bookings', 'salon-booking-system')); ?>
            <p><?php _e('Assistants will receive an e-mail when selected for a new booking.', 'salon-booking-system') ?></p>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 form-group">
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
<h2 class="sln-box-title"><?php _e('SMS services','salon-booking-system') ?></h2>
<div class="sln-box--sub row">
   <div class="col-xs-12">
       <h2 class="sln-box-title"><?php _e('SMS Verification service <span>Ask users to verify their identity with an SMS verification code</span>','salon-booking-system') ?></h2>
   </div>
   <div class="col-sm-8 sln-checkbox">
       <?php $this->row_input_checkbox('sms_enabled', __('Enable SMS verification', 'salon-booking-system')); ?>
       <p><?php _e('Avoid spam asking your users to verify their identity with an SMS verification code during the first registration.', 'salon-booking-system') ?></p>
   </div>
   <div class="col-xs-12">
<div class="row">
    <div class="col-sm-8 form-group">
        <div class="row">
            <div class="col-xs-12 sln-select">
                <?php $field = "salon_settings[sms_provider]"; ?>
                <label for="salon_settings_sms_provider"><?php _e('Select your service provider', 'salon-booking-system') ?></label>
                <?php echo SLN_Form::fieldSelect(
                $field,
                SLN_Enum_SmsProvider::toArray(),
                $this->getOpt('sms_provider'),
                array(),
                true
            ) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 sln-input--simple">
                <?php $this->row_input_text('sms_account', __('Account ID', 'salon-booking-system')); ?>
            </div>
            <div class="col-sm-6 sln-input--simple">
                <?php $this->row_input_text('sms_password', __('Auth Token', 'salon-booking-system')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group sln-input--simple">
                <?php $this->row_input_text('sms_prefix', __('Number Prefix', 'salon-booking-system')); ?>
            </div>
            <div class="col-sm-6 form-group sln-input--simple">
                <?php $this->row_input_text('sms_from', __('Sender\'s number', 'salon-booking-system')); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4 form-group sln-box-maininfo align-top">
        <p class="sln-input-help"><?php _e('To use all the SMS features you need an active account with Plivo o Twilio providers. <br /><br />Please read carefully their documentation about how to properly set the options.','salon-booking-system') ?></p>
    </div>
    </div>
   </div>
    
</div>

<div class="sln-box--sub row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php _e('SMS Notifications service','salon-booking-system') ?></h2>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-6 form-group sln-checkbox">
                <?php $this->row_input_checkbox('sms_new', __('Send SMS notification on new bookings', 'salon-booking-system')); ?>
            <p><?php _e('SMS will be sent to your customer and a staff member','salon-booking-system'); ?></p>
            </div>
            <div class="col-sm-4 form-group sln-input--simple">
                <?php $this->row_input_text('sms_new_number', __('Staff member notification number', 'salon-booking-system')); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-6 form-group sln-checkbox">
                <?php $this->row_input_checkbox('sms_remind', __('Remind the appointment to the client with an SMS', 'salon-booking-system')); ?>
            </div>
            <div class="col-sm-6 form-group sln-select  sln-select--info-label">
                <label for="salon_settings_sms_remind_interval"><?php __('SMS Timing','salon-booking-system') ?></label>
                <div class="row">
                <div class="col-xs-6 col-sm-6">
                <?php $field = "salon_settings[sms_remind_interval]"; ?>
                        <?php echo SLN_Form::fieldSelect(
                            $field,
                            SLN_Func::getIntervalItemsShort(),
                            $this->getOpt('sms_remind_interval'),
                            array(),
                            true
                        ) ?>
                </div>
                <div class="col-xs-6 col-sm-6 sln-label--big">

                    <label for="salon_settings_sms_remind_interval"><?php _e('Before the appointment','salon-booking-system') ?></label></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-6 form-group sln-checkbox">
                <?php $this->row_input_checkbox('sms_new_attendant', __('Send an SMS to selected attendant on new bookings', 'salon-booking-system')); ?>
            <p><?php _e('Remember to set the mobile number of your staff members','salon-booking-system');?></p>
            </div>
            <div class="col-xs-12 col-sm-6 sln-box-maininfo  align-top">
            <p class="sln-input-help"><?php _e('-','salon-booking-system') ?></p>
        </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="sln-box--sub row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php _e('SMS Test console','salon-booking-system') ?><span><?php _e('fill the fields and update settings','salon-booking-system') ?></span></h2>
    </div>
    <div class="col-sm-4 form-group sln-input--simple">
        <?php $this->row_input_text('sms_test_number', __('Number', 'salon-booking-system')); ?>
    </div>
    <div class="col-sm-4 form-group sln-input--simple">
        <?php $this->row_input_text('sms_test_message', __('Message', 'salon-booking-system')); ?>
    </div>
    <div class="col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help"><?php _e('Use this console just to test your SMS services. Fill the destination number without the counrty code, write a text message and click "Update settings" to send an SMS.','salon-booking-system');?></p>
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
            <div class="col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help"><?php _e('Select your favourite date and time format. Do you need another format? Send an email to support@wpchef.it','salon-booking-system') ?></p>
            </div>
            </div>
</div>
<div class="row">
    <div class="col-sm-6">
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
    <div class="col-sm-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title"><?php _e('Bootstrap CSS','salon-booking-system') ?></h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
                <?php $this->row_input_checkbox('no_bootstrap', __('Hide Bootstrap CSS', 'salon-booking-system')); ?>
                <p class="sln-input-help"><?php _e('Only for advanced users.','salon-booking-system') ?></p>
            </div>
        </div>
    </div>
    </div>
</div>
</div>
<div class="clearfix"></div>
