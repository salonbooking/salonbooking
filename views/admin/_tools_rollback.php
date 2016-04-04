	<form>
		<div class="sln-tab" id="sln-tab-general">
			<div class="sln-box sln-box--main">
				<h2 class="sln-box-title"><?php echo sprintf( __('Rollback to %s version','salon-booking-system'), $versionToRollback) ?></h2>
				<div class="row">
					<div class="col-sm-4 form-group">
						<h6 class="sln-fake-label"><?php echo sprintf( __('Download Salon Booking %s','salon-booking-system'), $versionToRollback) ?></h6>
						<a class='sln-btn sln-btn--main sln-btn--big'><?php _e('Download','salon-booking-system'); ?></a>
					</div>
					<div class="col-sm-4 form-group">
						<h6 class="sln-fake-label"><?php echo __('Rollback plugin data to version ','salon-booking-system') . $versionToRollback ?></h6>
						<input type="hidden" name="page" value="salon-tools">
						<button id="tools-rollback-btn" class='sln-btn sln-btn--main sln-btn--big' name="do_rollback_sln" value="true"><?php _e('Rollback database','salon-booking-system'); ?></button>
					</div>
					<div class="col-sm-4 form-group">
						<h6 class="sln-fake-label"><?php echo __('Reinstall the plugin to version ','salon-booking-system') . $versionToRollback ?></h6>
						<a href="<?php echo admin_url('plugin-install.php?tab=upload') ?>" class='sln-btn sln-btn--main sln-btn--big'><?php _e('Upload','salon-booking-system'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</form>

