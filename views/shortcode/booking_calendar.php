<div id="sln-salon" class="sln-bootstrap">
	<div id="sln-salon-my-account">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th><?php _e('Date','salon-booking-system'); ?></th>
					<?php foreach($data['attendants'] as $att): ?>
						<th><?php echo $att['name'] ?></th>
					<?php endforeach ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data['dates'] as $k => $date): ?>
					<tr>
						<td data-th="<?php _e('Date','salon-booking-system'); ?>"><?php echo $date ?></td>
						<?php foreach($data['attendants'] as $att): ?>
							<td data-th="<?php echo $att['name'];?>" class="<?php echo $att['color'] ?>">
								<?php if(isset($att['events'][$k])): ?>
									<?php foreach($att['events'][$k] as $event): ?>
										<div style="text-transform: none;"><span data-toggle="tooltip" data-placement="right" data-html="true" title="<?php echo $event['desc'] ?>"><?php echo $event['title'] ?></span></div>
									<?php endforeach ?>
								<?php endif ?>
							</td>
						<?php endforeach ?>
					</tr>
				<?php endforeach ?>
				<?php ?>
			</tbody>
		</table>
	</div>
	<script>
		jQuery(function () {
			jQuery('[data-toggle="tooltip"]').tooltip()
		})
	</script>
</div>
