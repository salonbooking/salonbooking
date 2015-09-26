<div class="sln-tab" id="sln-tab-general">
    <div class="row">
        <div class="col-md-10"><h3>Google Calendar</h3></div>
        <div class="col-md-3 col-sm-4">
            <?php $this->row_input_text('google_outh2_client_id', __('Google Client ID', 'sln')); ?>
        </div>
        <div class="col-md-3 col-sm-4">
            <?php $this->row_input_text('google_outh2_client_secret', __('Google Client Secret', 'sln')); ?>
        </div>
        <div class="col-md-3 col-sm-4">
            <?php $this->row_input_text('google_outh2_redirect_uri', __('Redirect URI', 'sln')); ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery("#salon_settings_google_outh2_redirect_uri").val('<?php echo admin_url('admin.php') . "?page=salon-settings&tab=gcalendar"; ?>');
                    jQuery("#salon_settings_google_outh2_redirect_uri").prop('readonly', true);
                });
            </script>
        </div>
    </div>

</div>
