<!-- algolplus -->
<div id="sln-salon" class="sln-bootstrap">
	<div>
		<h3><?php _e('UPCOMING BOOKING','sln');?></h3>
		<?php if($data['cancelled']): ?>
			<p><?php _e('The booking has been cancelled', 'sln'); ?></p>
		<?php endif ?>
		<?php if (!empty($data['upcoming'])):?>
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th><?php _e('Booking','sln');?></th>
					<th><?php _e('Date','sln');?></th>
					<th><?php _e('Services','sln');?></th>
					<th><?php _e('Assistant','sln');?></th>
					<th><?php _e('Total','sln');?></th>
					<th><?php _e('Status','sln');?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $data['upcoming'] as $item ): ?>
					<tr>
						<td data-th="<?php _e('Booking','sln');?>"><?php echo $item['id'] ?></td>
						<td data-th="<?php _e('Date','sln');?>"><strong><?php echo $item['date'] ?></strong></td>
						<td data-th="<?php _e('Services','sln');?>"><?php echo $item['services']; ?></td>
						<td data-th="<?php _e('Assistant','sln');?>"><?php echo $item['assistant'] ?></td>
						<td data-th="<?php _e('Total','sln');?>"><nobr><strong><?php echo $item['total'] ?></strong></nobr></td>
						<td data-th="<?php _e('Status','sln');?>">
							<div class="status <?php echo SLN_Enum_BookingStatus::getColor($item['status_code']); ?>">
								<nobr>
									<span class="glyphicon <?php echo SLN_Enum_BookingStatus::getIcon($item['status_code']); ?>" aria-hidden="true"></span>
									<span class="glyphicon-class"><?php echo $item['status']; ?></span>
								</nobr>
							</div>

							<?php if ($item['status_code'] != SLN_Enum_BookingStatus::CANCELED
							    && $data['cancellation_enabled']): ?>
									<?php if ($item['timestamp']-current_time('timestamp') > $data['seconds_before_cancellation']): ?>
										<button class="btn btn-danger btn-confirm" onclick="slnMyAccount.cancelBooking(<?php echo $item['id']; ?>);">
											<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
											<?php _e('Cancel booking','sln');?>
										</button>
									<?php else: ?>
									<button class="btn" data-toggle="tooltip" data-placement="top" style="cursor: not-allowed;"
									        title="<?php _e('Sorry, you cannot cancel this booking online. Please call ' . $data['gen_phone'], 'sln'); ?>">
										<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> 
										<?php _e('Cancel booking','sln');?>
									</button>
									<?php endif ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p><?php _e('No bookings', 'sln'); ?></p>
		<?php endif; ?>
	</div>

	<div>
		<h3><?php _e('BOOKINGS HISTORY','sln');?></h3>
		<?php if (!empty($data['history'])):?>
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th><?php _e('Booking','sln');?></th>
					<th><?php _e('Date','sln');?></th>
					<th><?php _e('Services','sln');?></th>
					<th><?php _e('Assistant','sln');?></th>
					<th><?php _e('Total','sln');?></th>
					<th><?php _e('Status','sln');?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $data['history'] as $item ): ?>
					<tr>
						<td data-th="<?php _e('Booking','sln');?>"><?php echo $item['id'] ?></td>
						<td data-th="<?php _e('Date','sln');?>"><strong><?php echo $item['date'] ?></strong></td>
						<td data-th="<?php _e('Services','sln');?>"><?php echo $item['services'] ?></td>
						<td data-th="<?php _e('Assistant','sln');?>"><?php echo $item['assistant'] ?></td>
						<td data-th="<?php _e('Total','sln');?>"><nobr><strong><?php echo $item['total'] ?></strong></nobr></td>
						<td data-th="<?php _e('Status','sln');?>">
							<div class="status <?php echo SLN_Enum_BookingStatus::getColor($item['status_code']); ?>">
								<nobr>
									<span class="glyphicon <?php echo SLN_Enum_BookingStatus::getIcon($item['status_code']); ?>" aria-hidden="true"></span>
									<span class="glyphicon-class"><?php echo $item['status']; ?></span>
								</nobr>
							</div>

							<div>
								<?php if($item['status_code'] == SLN_Enum_BookingStatus::CONFIRMED): ?>
										<?php if(empty($item['rating'])): ?>
										<button class="btn btn-default sln-rate-service" onclick="slnMyAccount.rate(this);">
											<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> 
											<?php _e('Rate our service','sln');?>
										</button>
										<?php endif; ?>
									<input type="hidden" name="sln-rating" value="<?php echo $item['rating']; ?>">
									<div class="rating" id="<?php echo $item['id']; ?>" style="display: none;"><?php _e('Your rating ','sln');?></div>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p><?php _e('No bookings', 'sln'); ?></p>
		<?php endif; ?>
	</div>
</div>
