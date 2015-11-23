<div class="sln-tab" id="sln-tab-general">
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Salon informations <span>Leaving this field empty will cause the default site name</span></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 form-group sln-input--simple">
            <label for="salon_settings_gen_name">Your salon name</label>
                <input type="text" name="salon_settings[gen_name]" id="salon_settings_gen_name" placeholder="Prova nome salon bis">
            <p class="sln-input-help">Leaving this field empty will cause the default site name <strong>(saloon-booking)</strong> to be used</p>
        </div>
        <div class="col-md-4 col-sm-4 form-group sln-input sln-input--simple">
                <label for="salon_settings_gen_email">Salon contact e-mail</label>
                <input type="text" name="salon_settings[gen_email]" id="salon_settings_gen_email" value="dimitri@studiograssi.eu">
                <p class="sln-input-help">Leaving this field empty will cause the default site email  <strong>(me@nicovece.com)</strong> to be used</p>        
        </div>
        <div class="col-md-4 col-sm-4 form-group sln-input--simple">
            <label for="salon_settings_gen_name">Your salon name</label>
                <input type="text" name="salon_settings[gen_name]" id="salon_settings_gen_name" value="Prova nome salon bis">
            <p class="sln-input-help">Leaving this field empty will cause the default site name <strong>(saloon-booking)</strong> to be used</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 form-group sln-input--simple">
        <label for="salon_settings_gen_address">Salon address</label>
                <textarea name="salon_settings[gen_address]" id="salon_settings_gen_address" rows="5" placeholder="At least street, door number, city and post code"></textarea>
        </div>
        <div class="col-md-4 col-sm-4 form-group sln-box-maininfo align-top">
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
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Bookings notes</h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 form-group sln-input--simple">
            <label for="salon_settings_gen_timetable">Type in your message</label>
                <textarea name="salon_settings[gen_timetable]" id="salon_settings_gen_timetable" rows="5" placeholder="e.g. In case of delay we will take your seat for 15 minutes, then your booking priority will be lost"></textarea>
                <p class="sln-input-help">Use this field to provide your customers important infos about terms and conditions of their reservation.</p>
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
    </div>
    -->
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Assistant selection <span>Let your customers choose their favourite staff member</span></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 form-group">
        <div class="sln-checkbox">
        <input type="checkbox" name="salon_settings[attendant_enabled]" id="salon_settings_attendant_enabled" value="1" checked="checked">
            <label for="salon_settings_attendant_enabled">Enable assistant selection</label>
            <p class="sln-input-help">Use this field to provide your customers important infos about terms and conditions of their reservation.</p>
        </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 form-group">
        <div class="sln-checkbox">
            <input type="checkbox" name="salon_settings[attendant_email]" id="salon_settings_attendant_email" value="1">
            <label for="salon_settings_attendant_email">Enable assistant email on new bookings</label>
            <p class="sln-input-help">Assistants will receive an e-mail when selected for a new booking.</p>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 form-group">
            <a href="http://localhost/saloon/wp-admin/edit.php?post_type=sln_attendant" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--assistants">Manage staff</a>
            <p>You need to add your members staff.</p>
        </div>
        <div class="col-md-4 col-sm-4 form-group sln-box-maininfo">
            <p class="sln-input-help">Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
    </div>
</div>
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">SMS Verification service <span>Ask users to verify identity with an SMS verification code</span></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 sln-checkbox">
            <input type="checkbox" name="salon_settings[attendant_enabled]" id="salon_settings_sms_enabled" value="1">
            <label for="salon_settings_sms_enabled">Enable SMS verification</label>
        </div>
    </div>
    <div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 form-group">
        <div class="row">
            <div class="col-xs-12 sln-select">
                <label for="salon_settings_sms_provider">Select your service provider</label>
                <select name="salon_settings[sms_provider]" id="salon_settings_sms_provider">
                    <option value="fake" selected="selected">test (sms code is sent by mail to the admin)</option>
                    <option value="ip1smswebservice">ip1sms</option>
                    <option value="twilio">Twilio</option>
                    <option value="plivo">Plivo</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 sln-input--simple">
                <label for="salon_settings_sms_account">Account</label>
                <input type="text" name="salon_settings[sms_account]" id="salon_settings_sms_account" value="">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 sln-input--simple">
                <label for="salon_settings_sms_password">Password</label>
                <input type="password" name="salon_settings[sms_password]" id="salon_settings_sms_password" value="">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-input--simple">
                <label for="salon_settings_sms_prefix">Number Prefix</label>
                <input type="text" name="salon_settings[sms_prefix]" id="salon_settings_sms_prefix" value="">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-input--simple">
                <label for="salon_settings_sms_from">Sender's number</label>
                <input type="text" name="salon_settings[sms_from]" id="salon_settings_sms_from" value="">
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 form-group sln-box-maininfo align-top">
        <p class="sln-input-help">Avoid spam asking your users to verify their identity with an SMS verification code during the first registration. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
    </div>
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">SMS Notifications service</h2>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-checkbox">
            <input type="checkbox" name="salon_settings[sms_new]" id="salon_settings_sms_new" value="1">
            <label for="salon_settings_sms_new">Send SMS on new bookings</label>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-input--simple">
            <label for="salon_settings_sms_new_number">Recipient number</label>
            <input type="text" name="salon_settings[sms_new_number]" id="salon_settings_sms_new_number" value="" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-checkbox">
            <input type="checkbox" name="salon_settings[sms_remind]" id="salon_settings_sms_remind" value="1">
            <label for="salon_settings_sms_remind">Remind the appointment via SMS</label>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-select  sln-select--info-label">
            <label for="salon_settings_sms_remind_interval">SMS Timing</label>
            <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
            <select name="salon_settings[sms_remind_interval]" id="salon_settings_sms_remind_interval" class="form-control">
                   <option value="+1 hour" selected="selected">1 hour</option>
                   <option value="+2 hours">2 hours</option>
                   <option value="+3 hours">3 hours</option>
                   <option value="+4 hours">4 hours</option>
                   <option value="+6 hours">6 hours</option>
                   <option value="+12 hours">12 hours</option>
                   <option value="+24 hours">24 hours</option>
                   <option value="+48 hours">48 hours</option>
            </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 sln-label--big"><label for="salon_settings_sms_remind_interval">Before</label></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-checkbox">
            <input type="checkbox" name="salon_settings[sms_new_attendant]" id="salon_settings_sms_new_attendant" value="1">
            <label for="salon_settings_sms_new_attendant">Send SMS to attendant on new bookings</label>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 sln-box-maininfo  align-top">
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
    </div>
</div>

<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Date and Time settings</h2>
    <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_date_format">Date Format</label>
                <select name="salon_settings[date_format]" id="salon_settings_date_format">
                    <option value="default" selected="selected">18 nov 2015</option>
                    <option value="short">18/11/2015</option>
                    <option value="short_comma">18-11-2015</option>
                </select>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-4 form-group sln-select ">
                <label for="salon_settings_time_format">Time Format</label>
                <select name="salon_settings[time_format]" id="salon_settings_time_format" class="form-control">
                    <option value="default">15:53</option>
                    <option value="short" selected="selected">3:53pm</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
                <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
            </div>
            </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Ajax steps <span>This allows loading steps via ajax</span></h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
                <input type="checkbox" name="salon_settings[ajax_enabled]" id="salon_settings_ajax_enabled" value="1" checked="checked">
                <label for="salon_settings_ajax_enabled">Enable ajax steps</label>
                <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus.</p>
            </div>
        </div>
    </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Bootstrap CSS</h2>
    <div class="row">
            <div class="col-xs-12 form-group  sln-checkbox">
                <input type="checkbox" name="salon_settings[no_bootstrap]" id="salon_settings_no_bootstrap" value="1">
                <label for="salon_settings_no_bootstrap">Hide Bootstrap CSS</label>
                <p class="sln-input-help">Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus.</p>
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