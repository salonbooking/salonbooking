<div id="sln-setting-success" class="updated settings-success success">
	<p>
		<strong><?php _e('Salon Data Update Required','salon-booking-system') ?></strong> -
		<?php echo __('The database version does not match the version of the plugin. You just need to perform a database upgrade or install a plugin version ','salon-booking-system') . $version ?>
	</p>

	<p>
		<a href="<?php echo esc_url( add_query_arg( 'do_update_sln', 'true', admin_url( 'admin.php?page=salon-settings' ) ) ); ?>"
		   class="button button-default"><?php _e( 'Run the updater', 'salon-booking-system' ); ?></a>
	</p>
</div>
