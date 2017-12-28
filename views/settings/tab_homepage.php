
   <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Reset Settings</h2>
    <div class="row">
        <div class="col-sm-4 form-group sln-input--simple">
        <h6 class="sln-fake-label">Reset</h6>
        <button type="submit" class="sln-btn sln-btn--warning sln-btn--big sln-btn--icon sln-icon--warning" name="reset-settings" value="reset"
        onClick="return confirm('Do you really want to reset?');"
        ><?php echo __('Reset settings', 'salon-booking-system')?></button>
        </div>
        <div class="col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-input-help"><?php _e('Use this option to restore all the original settings. All your actual settings will be lost. <br />This operation can\'t be undone.', 'salon-booking-system'); ?></p>
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

