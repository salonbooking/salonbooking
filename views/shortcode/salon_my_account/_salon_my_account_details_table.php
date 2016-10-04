<table class="table">
	<thead>
	<tr>
		<td><?php _e('ID','salon-booking-system');?></td>
		<td><?php _e('When','salon-booking-system');?></td>
		<td><?php _e('Services','salon-booking-system');?></td>
		<?php if($data['attendant_enabled']): ?>
			<td><?php _e('Assistants','salon-booking-system');?></td>
		<?php endif; ?>
		<?php if(!$data['hide_prices']): ?>
			<td><?php _e('Price','salon-booking-system');?></td>
		<?php endif; ?>
		<td><?php _e('Status','salon-booking-system');?></td>
		<td><?php _e('Action','salon-booking-system');?></td>
	</tr>
	</thead>
	<tbody>
	<?php include '_salon_my_account_details_table_rows.php' ?>
	</tbody>
</table>
