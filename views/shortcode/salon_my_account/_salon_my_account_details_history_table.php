<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th><?php _e('Booking','salon-booking-system');?></th>
		<th><?php _e('Date','salon-booking-system');?></th>
		<th><?php _e('Services','salon-booking-system');?></th>
		<?php if($data['attendant_enabled']): ?>
			<th><?php _e('Assistant','salon-booking-system');?></th>
		<?php endif; ?>
		<?php if(!$data['hide_prices']): ?>
			<th><?php _e('Total','salon-booking-system');?></th>
		<?php endif; ?>
		<th><?php _e('Status','salon-booking-system');?></th>
		<th><?php _e('Action','salon-booking-system');?></th>
	</tr>
	</thead>
	<tbody>
	<?php include '_salon_my_account_details_history_table_rows.php' ?>
	</tbody>
</table>
