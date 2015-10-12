<div class="sln-tab" id="sln-tab-general">
    <div class="row">
        <div class="col-md-10"><h3>Google Calendar</h3></div>
        <div class="col-md-3 col-sm-4">           
            <?php $this->row_checkbox_text('google_calendar_enabled', __('Abilita', 'sln')); ?>
        </div>
        
        <div class="sln-separator"></div>
        
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
                    jQuery("#salon_settings_google_outh2_redirect_uri").val('<?php echo admin_url('admin-ajax.php?action=googleoauth-callback'); ?>');
                    jQuery("#salon_settings_google_outh2_redirect_uri").prop('readonly', true);
                });
            </script>
        </div>

        <div class="clearfix"></div>       

        <div class="col-md-3 col-sm-4">
            <?php            
            $_calendar_list = $GLOBALS['sln_googlescope']->get_calendar_list();
            if (isset($_calendar_list) && !empty($_calendar_list)) {
                $this->select_text('google_client_calendar', __('Calendario', 'sln'), $_calendar_list);
            } else
                echo __("Per ottenere la lista dei tuoi calendari è necessario effettuare login Google OAuth", 'sln');
            ?>
        </div>

    </div>

</div>
