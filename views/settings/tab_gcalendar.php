
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title"><?php _e('Google Calendar','salon-booking-system');?></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 form-group sln-switch">
            <?php $this->row_input_checkbox_switch(
                'google_calendar_enabled',
                'Google Calendar status',
                array(
                    'help' => 'Synchronize your reservation on your Google Calendar account.',
                    'bigLabelOn' => 'Google Calendar enabled',
                    'bigLabelOff' => 'Google Calendar disabled'
                    )
            ); ?>
        </div>
        <div class="hidden-xs col-md-4 col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-box-info"><?php _e('To use this feature you need to generate an OAuth Client ID on Google Developers Console. Click on "i" icon to get more information on this feature.','salon-booking-system');?></p>
        </div>
        </div>
          <div class="row">
        <div class="col-sm-4 form-group sln-input--simple">
            <?php $this->row_input_text('google_outh2_client_id', __('Google Client ID', 'salon-booking-system')); ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
        <?php $this->row_input_text('google_outh2_client_secret', __('Google Client Secret', 'salon-booking-system')); ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
            <?php $this->row_input_text('google_outh2_redirect_uri', __('Redirect URI', 'salon-booking-system')); ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery("#salon_settings_google_outh2_redirect_uri").val('<?php echo admin_url('admin-ajax.php?action=googleoauth-callback'); ?>');
                    jQuery("#salon_settings_google_outh2_redirect_uri").prop('readonly', true);
                });
            </script>
        </div>
        <div class="col-xs-12 visible-xs-block form-group sln-box-maininfo align-top">
            <p class="sln-input-help"><?php _e('-','salon-booking-system');?></p>
        </div>
    </div>
    <div class="sln-box-info">
       <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--info">info</button></div>
       <div class="sln-box-info-content row">
       <div class="col-md-4 col-sm-8 col-xs-12">
       <h5><?php _e('Follow these instructions to set-up your Google Calendar feature <br /><br />Whatch this video tutorial: https://screencast.com/t/X5UqZLUb<br /><br />1) Go to Google Developer Console<br />https://console.developers.google.com<br /><br />2 ) Click on Use Google APIs<br /><br />3 ) Click on Credentials link on left sidebar<br /><br />4 ) click on New Credential > OAuth ID<br /><br />5 ) Select Web application and click "Create"<br /><br />6 ) Set a name for your App ( your website name )<br /><br />7 ) Paste the URL of your website<br /><br />8 ) Copy the Redirect URI from Salon Booking settings > Google Calendar field and paste inside the Authorized redirect URIs field and click "Create". <br /><br />9 ) Copy and paste your Client ID inside Salon Booking settings > Google Calendar > Google Client Secret field and do the same thing with the Client Secret field. Then click on "Update settings". <br /><br />10) Enable Google Calendar and click Update settings - you will be redirected to a Google authorisation page where you need to click on "Allow" button. Then you\'ll be automatically redirected to the Salon Booking > Google Calendar page. <br /><br />11) Select which  Google Calendar you want to use to publish all the new bookings.<br /><br />Use the "Synchronise booking" button to populate your selected Google Calendar with all current reservations. <br /><br />In case of need you can reset your Google Calendar from all the reservations using the "Delete all Google Calendar Events" button.','salon-booking-system');?></h5>
        </div>
        </div>
        <div class="sln-box-info-trigger"><button class="sln-btn sln-btn--main sln-btn--small sln-btn--icon sln-icon--close">info</button></div>
    </div>
    <div class="sln-box--sub row">
    <div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Your Google calendars','salon-booking-system');?></h2></div>
            <?php
            $api_error = false;
            try {
                $_calendar_list = $GLOBALS['sln_googlescope']->get_calendar_list();
            } catch (Exception $e) {
                $err = $e->getErrors();
                if (isset($err)) {
                    $messages = $e->getErrors();
                    if (isset($messages[0]['message'])) 
						$api_error = $messages[0]['message'];
                }
            }
            // got calendars?
            if (isset($_calendar_list) && !empty($_calendar_list)) { ?>
                <div class="col-xs-12 col-sm-4 form-group sln-select  sln-select--info-label">
				<?php $this->select_text('google_client_calendar', __('Calendars', 'salon-booking-system'), $_calendar_list); ?>
				</div>
                <div class="col-xs-12 col-sm-4">
                <h6 class="sln-fake-label">&nbsp;</h6>
                <div class="sln-btn sln-btn--main sln-btn--big">
                <input type="button" id="sln_synch" value="<?php echo __('Synchronize Bookings'); ?>">
                </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                <h6 class="sln-fake-label">&nbsp;</h6>
                <div class="sln-btn sln-btn--warning sln-btn--block sln-btn--big sln-btn--icon sln-icon--save">
                <input type="button" id="sln_del" value="<?php echo __('Delete all Google Calendar Events'); ?>">
                </div>
                </div>
                <div class="col-xs-12">
                <a href="?<?php echo "page={$_GET['page']}&tab={$_GET['tab']}&force_revoke_token=1"; ?>" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--settings sln-btn--disabled"><?php echo __('Get authorization'); ?></a></div>
                <?php
            }
            elseif($api_error)// API failed!
               { echo '<div class="col-xs-12 col-sm-8 sln-box-maininfo  align-top"><h5 class="sln-message sln-message--warning">' .__("Google API Error: ", 'salon-booking-system') .$api_error . '</h5></div>';
                echo '<div class="col-xs-12"><a href="?';
                echo "page={$_GET['page']}&tab={$_GET['tab']}&force_revoke_token=1";
                echo '" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--settings">' . __('Get authorization') . '</a></div>';
            } else// not assigned to API
            {
                echo '<div class="col-xs-12 col-sm-8 sln-box-maininfo  align-top"><h5 class="sln-message sln-message--warning">' .__("To get the list of your Google Calendar you need to log-in with Google OAuth. At the moment you are not logged-in.", 'salon-booking-system') . '</h5></div>';
                echo '<div class="col-xs-12"><a href="?';
                echo "page={$_GET['page']}&tab={$_GET['tab']}&force_revoke_token=1";
                echo '" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--settings">' . __('Get authorization') . '</a></div>';
            }
            ?>
    </div>
    <div class="clearfix"></div>
</div>
