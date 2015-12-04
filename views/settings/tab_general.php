<div class="sln-tab" id="sln-tab-general">
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Salon informations <span>Leaving this field empty will cause the default site name</span></h2>
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

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Bookings notes</h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8 form-group sln-input--simple">
            <?php $this->row_input_textarea(
                'gen_timetable',
                __('Bookings notes', 'sln'),
                array(
                    'help' => 'Use this field to provide your customers important infos about terms and conditions of their reservation.',
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
            <p class="sln-input-help">Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
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
    <h2 class="sln-box-title">Assistant selection <span>Let your customers choose their favourite staff member</span></h2>
    <div class="row">
        <div class="col-sm-6 form-group">
        <div class="sln-checkbox">
            <?php $this->row_input_checkbox('attendant_enabled', __('Enable assistant selection', 'sln')); ?>
            <p class="sln-input-help"><?php echo sprintf(__('You need to add your members staff <a href="%s">Here</a>.', 'sln'),
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
            class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--assistants">Manage staff</a>
            <p>You need to add your members staff.</p>
        </div>
        <div class="col-sm-4 form-group sln-box-maininfo">
            <p class="sln-input-help">Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
    </div>
</div>
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">SMS Verification service <span>Ask users to verify identity with an SMS verification code</span></h2>
    <div class="row">
        <div class="col-sm-8 sln-checkbox">
            <?php $this->row_input_checkbox('sms_enabled', __('Enable SMS verification', 'sln')); ?>
            <p><?php _e('Avoid spam asking your users to verify their identity with an SMS verification code during the first registration.', 'sln') ?></p>
        </div>
    </div>
    <div class="row">
    <div class="col-sm-8 form-group">
        <div class="row">
            <div class="col-xs-12 sln-select">
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
                <?php $this->row_input_text('sms_account', __('Account', 'sln')); ?>
            </div>
            <div class="col-sm-6 sln-input--simple">
                <?php $this->row_input_text('sms_password', __('Password', 'sln')); ?>
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
        <p class="sln-input-help">Avoid spam asking your users to verify their identity with an SMS verification code during the first registration. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
    </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">SMS Notifications service</h2>
    <div class="row">
        <div class="col-sm-6 form-group sln-checkbox">
            <?php $this->row_input_checkbox('sms_new', __('Send SMS on new bookings', 'sln')); ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
            <?php $this->row_input_text('sms_new_number', __('Recipient number', 'sln')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 form-group sln-checkbox">
            <?php $this->row_input_checkbox('sms_remind', __('Reminde the appointment via SMS', 'sln')); ?>
        </div>
        <div class="col-sm-6 form-group sln-select  sln-select--info-label">
            <label for="salon_settings_sms_remind_interval">SMS Timing</label>
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
            <div class="col-xs-6 col-sm-6 sln-label--big"><label for="salon_settings_sms_remind_interval">Before</label></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 form-group sln-checkbox">
            <?php $this->row_input_checkbox('sms_new_attendant', __('Send SMS to attendant on new bookings', 'sln')); ?>
        </div>
        <div class="col-xs-12 col-sm-6 sln-box-maininfo  align-top">
        <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
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

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Date and Time settings</h2>
    <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_date_format"><?php _e('Date Format', 'sln') ?></label>
                <?php $field = "salon_settings[time_format]"; ?>
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
                    <?php echo SLN_Form::fieldSelect(
                        $field,
                        SLN_Enum_TimeFormat::toArray(),
                        $this->getOpt('time_format'),
                        array(),
                        true
                    ) ?>
            </div>
            <div class="col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
            </div>
            </div>
</div>
<div class="row">
    <div class="col-sm-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Ajax steps <span>This allows loading steps via ajax</span></h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
            <?php $this->row_input_checkbox('ajax_enabled', __('Enable ajax steps', 'sln')); ?>
            <p><?php _e('This allows loading steps via ajax.', 'sln') ?></p>
            </div>
        </div>
    </div>
    </div>
    <div class="col-sm-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Bootstrap CSS</h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
                <?php $this->row_input_checkbox('no_bootstrap', __('Hide Bootstrap CSS', 'sln')); ?>
                <p class="sln-input-help">Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus.</p>
            </div>
        </div>
    </div>
    </div>
</div>
<!--
    <div class="row">
        <div class="col-md-3">
            <?php $this->row_input_checkbox('attendant_enabled', __('Enable assistant selection', 'sln')); ?>
            <p><?php _e('Let your customers choose their favourite staff member.', 'sln') ?></p>

            <p><?php echo sprintf(__('You need to add your members staff <a href="%s">Here</a>.', 'sln'),
                    get_admin_url().'edit.php?post_type=sln_attendant') ?></p>
            <?php $this->row_input_checkbox('attendant_email', __('Enable assistant email on new bookings', 'sln')); ?>
            <p><?php _e('Assistants will receive an e-mail when selected for a new booking.', 'sln') ?></p><br/>
            <?php $this->row_input_checkbox('ajax_enabled', __('Enable ajax steps', 'sln')); ?>
            <p><?php _e('This allows loading steps via ajax.', 'sln') ?></p>
			<?php $this->row_input_checkbox('hide_prices', __('Hide Prices', 'sln')); ?>
            <p><?php _e('Select this Option if you want to hide all prices from the front end.<br/>Note: Online Payment will be disabled.', 'sln') ?></p>

        </div>
        <div class="col-md-6">
            <?php $this->row_input_checkbox('sms_enabled', __('Enable SMS verification', 'sln')); ?>
            <p><?php _e('Avoid spam asking your users to verify their identity with an SMS verification code during the first registration.', 'sln') ?></p>
            <label>        <?php _e('Select your service provider', 'sln') ?></label>
            <?php $field = "salon_settings[sms_provider]"; ?>
            <div class="sln-select-wrapper">
            <?php echo SLN_Form::fieldSelect(
                $field,
                SLN_Enum_SmsProvider::toArray(),
                $this->getOpt('sms_provider'),
                array(),
                true
            ) ?>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <?php $this->row_input_text('sms_account', __('Account', 'sln')); ?>
                </div>
                <div class="col-md-6 col-sm-6">
                    <?php $this->row_input_text('sms_password', __('Password', 'sln')); ?>
                </div>
                <div class="col-md-6 col-sm-6">
                    <?php $this->row_input_text('sms_prefix', __('Number Prefix', 'sln')); ?>
                </div>
                <div class="col-md-6 col-sm-6">
                    <?php $this->row_input_text('sms_from', __('Sender\'s number', 'sln')); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sln-separator"></div>
            <div class="row">
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <?php $this->row_input_checkbox('sms_new', __('Send SMS on new bookings', 'sln')); ?>
                    <?php $this->row_input_text('sms_new_number', __('Number', 'sln')); ?>
                    <?php $this->row_input_checkbox('sms_new_attendant', __('Send SMS to attendant on new bookings', 'sln')); ?>
                </div>
                <div class="col-md-6">
                    <?php $this->row_input_checkbox('sms_remind', __('Reminde the appointment via SMS', 'sln')); ?>
                    <?php _e('From', 'sln') ?>
                    <div class="sln-select-wrapper">
                    <?php $field = "salon_settings[sms_remind_interval]"; ?>
                    <?php echo SLN_Form::fieldSelect(
                        $field,
                        SLN_Func::getIntervalItemsShort(),
                        $this->getOpt('sms_remind_interval'),
                        array(),
                        true
                    ) ?>
                    </div>

                </div>
            </div>
    <div class="sln-separator"></div>
            <h3>Send a test sms</h3>
            <p>Just write here and save settings</p>
            <div class="row">
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <?php $this->row_input_text('sms_test_number', __('Number', 'sln')); ?>
                </div>
                <div class="col-md-6">
                    <?php $this->row_input_text('sms_test_message', __('Message', 'sln')); ?>
                </div>
            </div>
    <div class="sln-separator"></div>

    <div class="row">
        <div class="col-md-10"><h3>Social</h3></div>
        <div class="col-md-3 col-sm-4">
            <?php $this->row_input_text('soc_facebook', __('Facebook', 'sln')); ?>
        </div>
        <div class="col-md-3 col-sm-4">
            <?php $this->row_input_text('soc_twitter', __('Twitter', 'sln')); ?>
        </div>
        <div class="col-md-3 col-sm-4">
            <?php $this->row_input_text('soc_google', __('Google+', 'sln')); ?>
        </div>
    </div>
<br/>
    <div class="row">
        <div class="col-md-3 col-sm-4 sln-select-wrapper">
            <label>        <?php _e('Date Format', 'sln') ?></label>
            <?php $field = "salon_settings[date_format]"; ?>
            <?php echo SLN_Form::fieldSelect(
                $field,
                SLN_Enum_DateFormat::toArray(),
                $this->getOpt('date_format'),
                array(),
                true
            ) ?>
        </div>
        <div class="col-md-3 col-sm-4 sln-select-wrapper">
            <label>        <?php _e('Time Format', 'sln') ?></label>
            <?php $field = "salon_settings[time_format]"; ?>
            <?php echo SLN_Form::fieldSelect(
                $field,
                SLN_Enum_TimeFormat::toArray(),
                $this->getOpt('time_format'),
                array(),
                true
            ) ?>
        </div>
        <div class="col-md-3 col-sm-4">
            <?php $this->row_input_checkbox('no_bootstrap', __('Hide Bootstrap CSS', 'sln')); ?>
        </div>
    </div>
    -->
</div>
<div class="clearfix"></div>