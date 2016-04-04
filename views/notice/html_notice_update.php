<div id="sln-setting-success" class="updated settings-success success">
	<p>
		<strong><?php _e('Salon Data Update Required','salon-booking-system') ?></strong> -
		<?php echo __('An update is required for this version. In case of problems you can use a “Rollback” option','salon-booking-system') ?>
	</p>

	<p>
		<a href="<?php echo esc_url( add_query_arg( 'do_update_sln', 'true', admin_url( 'admin.php?page=salon-settings' ) ) ); ?>"
		   class="button button-default"><?php _e( 'Run the updater', 'salon-booking-system' ); ?></a>
	</p>
</div>
