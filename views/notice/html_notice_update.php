<div id="sln-setting-success" class="updated settings-success success">
	<p><?php _e('<strong>Salon Data Update Required</strong> - We just need to update your install to the latest version','salon-booking-system') ?></p>

	<p>
		<a href="<?php echo esc_url( add_query_arg( 'do_update_sln', 'true', admin_url( 'admin.php?page=salon-settings' ) ) ); ?>"
		   class="button button-default"><?php _e( 'Run the updater', 'salon-booking-system' ); ?></a>
	</p>
</div>
