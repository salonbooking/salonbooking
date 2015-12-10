<div class="sln-tab" id="sln-tab-google-calendar">
<div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Google Calendar</h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 form-group sln-switch">
            <?php $this->row_input_checkbox_switch(
                'google_calendar_enabled',
                'Google Calendar status',
                array(
                    'help' => 'Mauris semper hendrerit erat, in consectetur',
                    'bigLabelOn' => 'Google Calendar enabled',
                    'bigLabelOff' => 'Google Calendar disabled'
                    )
            ); ?>
        </div>
        <div class="hidden-xs col-md-4 col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-input-help">Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at.</p>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
            <?php $this->row_input_text('google_outh2_client_id', __('Google Client ID', 'sln')); ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
        <?php $this->row_input_text('google_outh2_client_secret', __('Google Client Secret', 'sln')); ?>
        </div>
        <div class="col-sm-4 form-group sln-input--simple">
            <?php $this->row_input_text('google_outh2_redirect_uri', __('Redirect URI', 'sln')); ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery("#salon_settings_google_outh2_redirect_uri").val('<?php echo admin_url('admin-ajax.php?action=googleoauth-callback'); ?>');
                    jQuery("#salon_settings_google_outh2_redirect_uri").prop('readonly', true);
                });
            </script>
        </div>
        <div class="col-xs-12 visible-xs-block form-group sln-box-maininfo align-top">
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

    <div class="sln-box--sub row">
    <div class="col-xs-12"><h2 class="sln-box-title">Your Google calendars</h2></div>
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
				<?php $this->select_text('google_client_calendar', __('Calendars', 'sln'), $_calendar_list); ?>
				</div>
                <div class="col-xs-12 col-sm-4">
                <div class="sln-btn sln-btn--main sln-btn--big">
                <input type="button" id="sln_synch" value="<?php echo __('Synchronize Bookings'); ?>">
                </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                <div class="sln-btn sln-btn--warning sln-btn--block sln-btn--big sln-btn--icon sln-icon--save">
                <input type="button" id="sln_del" value="<?php echo __('Delete all Google Calendar Events'); ?>">
                </div>
                </div>
                <?php
            } 
            elseif($api_error)// API failed!
                echo '<div class="col-xs-12 col-sm-8 sln-box-maininfo  align-top"><h5 class="sln-message sln-message--warning">' .__("Google API Error: ", 'sln') .$api_error . '</h5></div>';
            else// not assigned to API
                echo '<div class="col-xs-12 col-sm-8 sln-box-maininfo  align-top"><h5 class="sln-message sln-message--warning">' .__("Per ottenere la lista dei tuoi calendari è necessario effettuare login Google OAuth", 'sln') . '</h5></div>';
            ?>
    </div>
    <div class="clearfix"></div>
</div>
</div>
    <div class="clearfix"></div>
