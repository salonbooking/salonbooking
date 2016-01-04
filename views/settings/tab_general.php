<div class="sln-tab" id="sln-tab-general">
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Salon\'s informations','sln'); ?> <span>-</span></h2>
    <div class="row">
        <div class="col-sm-4 form-group sln-input--simple">
            <?php
            $this->row_input_text(
                'gen_name',
                __('Your salon name', 'sln'),
                array(
                    'help' => sprintf(
                        __('Leaving this field empty will cause the default site name <strong>(%s)</strong> to be used', 'sln'),
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
                __('Salon contact e-mail', 'sln'),
                array(
                    'help' => sprintf(
                        __('Leaving this field empty will cause the default site email  <strong>(%s)</strong> to be used', 'sln'),
                        get_bloginfo('admin_email')
                    )
                )
            );
            ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
            <?php $this->row_input_text('gen_phone', __('Salon telephone number', 'sln')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-8 form-group sln-input--simple">
            <?php $this->row_input_textarea(
                'gen_address',
                __('Salon address', 'sln'),
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
            <p class="sln-input-help"><?php __('Provide the full address of your Salon','sln') ?></p>
        </div>
    </div>
    <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-md-4 col-sm-8 col-xs-12">
       <h5><?php __('-','sln') ?></h5>
        </div>
        </div>
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Booking notes','sln') ?></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8 form-group sln-input--simple">
            <?php $this->row_input_textarea(
                'gen_timetable',
                __('Use this field to provide your customers important infos about terms and conditions of their reservation.', 'sln'),
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
            <p class="sln-input-help"><?php __('-','sln') ?></p>
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
    <h2 class="sln-box-title"><?php _e('Assistant selection <span> - </span>','sln') ?></h2>
    <div class="row">
        <div class="col-sm-6 form-group">
        <div class="sln-checkbox">
            <?php $this->row_input_checkbox('attendant_enabled', __('Enable assistant selection', 'sln')); ?>
            <p class="sln-input-help"><?php echo sprintf(__('Let your customers choose their favourite staff member.', 'sln'),
                    get_admin_url().'edit.php?post_type=sln_attendant') ?></p>
        </div>
        </div>
        <div class="col-sm-6 form-group">
        <div class="sln-checkbox">
            <?php $this->row_input_checkbox('attendant_email', __('Enable assistant email on new bookings', 'sln')); ?>
            <p><?php _e('Assistants will receive an e-mail when selected for a new booking.', 'sln') ?></p>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 form-group">
            <a href="<?php echo get_admin_url() . 'edit.php?post_type=sln_attendant'; ?> "
            class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--assistants"><?php _e('Manage staff','sln') ?></a>
            <p><?php _e('If you need to add or manage your staff members.','sln'); ?></p>
        </div>
        <div class="col-sm-4 form-group sln-box-maininfo">
            <p class="sln-input-help"><?php __('-','sln') ?></p>
        </div>
    </div>
</div>
<div class="sln-box sln-box--main">
<h2 class="sln-box-title"><?php _e('SMS services','sln') ?></h2>
<div class="sln-box--sub row">
   <div class="col-xs-12">
       <h2 class="sln-box-title"><?php _e('SMS Verification service <span>Ask users to verify their identity with an SMS verification code</span>','sln') ?></h2>
   </div>
   <div class="col-sm-8 sln-checkbox">
       <?php $this->row_input_checkbox('sms_enabled', __('Enable SMS verification', 'sln')); ?>
       <p><?php _e('Avoid spam asking your users to verify their identity with an SMS verification code during the first registration.', 'sln') ?></p>
   </div>
   <div class="col-xs-12">
<div class="row">
    <div class="col-sm-8 form-group">
        <div class="row">
            <div class="col-xs-12 sln-select">
                <?php $field = "salon_settings[sms_provider]"; ?>
                <label for="salon_settings_sms_provider"><?php _e('Select your service provider', 'sln') ?></label>
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
                <?php $this->row_input_text('sms_account', __('Account ID', 'sln')); ?>
            </div>
            <div class="col-sm-6 sln-input--simple">
                <?php $this->row_input_text('sms_password', __('Auth Token', 'sln')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group sln-input--simple">
                <?php $this->row_input_text('sms_prefix', __('Number Prefix', 'sln')); ?>
            </div>
            <div class="col-sm-6 form-group sln-input--simple">
                <?php $this->row_input_text('sms_from', __('Sender\'s number', 'sln')); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4 form-group sln-box-maininfo align-top">
        <p class="sln-input-help"><?php _e('To use all the SMS features you need an active account with Plivo o Twilio providers. <br /><br />Please read carefully their documentation about how to properly set the options.','sln') ?></p>
    </div>
    </div>
   </div>
    
</div>

<div class="sln-box--sub row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php _e('SMS Notifications service','sln') ?></h2>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-6 form-group sln-checkbox">
                <?php $this->row_input_checkbox('sms_new', __('Send SMS notification on new bookings', 'sln')); ?>
            <p><?php _e('SMS will be sent to your customer and a staff member','sln'); ?></p>
            </div>
            <div class="col-sm-4 form-group sln-input--simple">
                <?php $this->row_input_text('sms_new_number', __('Staff member notification number', 'sln')); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-6 form-group sln-checkbox">
                <?php $this->row_input_checkbox('sms_remind', __('Remind the appointment to the client with an SMS', 'sln')); ?>
            </div>
            <div class="col-sm-6 form-group sln-select  sln-select--info-label">
                <label for="salon_settings_sms_remind_interval"><?php __('SMS Timing','sln') ?></label>
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

                    <label for="salon_settings_sms_remind_interval"><?php _e('Before the appointment','sln') ?></label></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-sm-6 form-group sln-checkbox">
                <?php $this->row_input_checkbox('sms_new_attendant', __('Send an SMS to selected attendant on new bookings', 'sln')); ?>
            <p><?php _e('Remember to set the mobile number of your staff members','sln');?></p>
            </div>
            <div class="col-xs-12 col-sm-6 sln-box-maininfo  align-top">
            <p class="sln-input-help"><?php _e('-','sln') ?></p>
        </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="sln-box--sub row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php _e('SMS Test console','sln') ?><span><?php _e('fill the fields and update settings','sln') ?></span></h2>
    </div>
    <div class="col-sm-4 form-group sln-input--simple">
        <?php $this->row_input_text('sms_test_number', __('Number', 'sln')); ?>
    </div>
    <div class="col-sm-4 form-group sln-input--simple">
        <?php $this->row_input_text('sms_test_message', __('Message', 'sln')); ?>
    </div>
    <div class="col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help"><?php _e('Use this console just to test your SMS services. Fill the destination number without the counrty code, write a text message and click "Update settings" to send an SMS.','sln');?></p>
            </div>
</div>
<div class="sln-box-info">
   <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
   <div class="sln-box-info-content row">
   <div class="col-md-4 col-sm-8 col-xs-12">
   <h5><?php _e('-','sln') ?></h5>
    </div>
    </div>
    <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
</div>
</div>




<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Date and Time settings','sln') ?></h2>
    <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_date_format"><?php _e('Date Format', 'sln') ?></label>
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
                <label for="salon_settings_time_format"><?php _e('Time Format', 'sln') ?></label>
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
                <p class="sln-input-help"><?php _e('Select your favourite date and time format. Do you need another format? Send an email to support@wpchef.it','sln') ?></p>
            </div>
            </div>
</div>
<div class="row">
    <div class="col-sm-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title"><?php _e('Ajax steps <span>This allows loading steps via ajax</span>','sln') ?></h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
            <?php $this->row_input_checkbox('ajax_enabled', __('Enable ajax steps', 'sln')); ?>
            <p><?php _e('This allows loading steps via ajax for a more smooth booking form transition.', 'sln') ?></p>
            </div>
        </div>
    </div>
    </div>
    <div class="col-sm-6">
    <div class="sln-box sln-box--main sln-box--main--small">
    <h2 class="sln-box-title"><?php _e('Bootstrap CSS','sln') ?></h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
                <?php $this->row_input_checkbox('no_bootstrap', __('Hide Bootstrap CSS', 'sln')); ?>
                <p class="sln-input-help"><?php _e('Only for advanced users.','sln') ?></p>
            </div>
        </div>
    </div>
    </div>
</div>
</div>
<div class="clearfix"></div>
