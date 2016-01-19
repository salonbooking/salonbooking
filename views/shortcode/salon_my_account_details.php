<!-- algolplus -->
	<div>
		<h1><?php _e('Upcoming booking','salon-booking-system');?><svg class="icocal" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"
                 preserveAspectRatio="xMinYMin meet" width="100%" height="100%"
                 style="width: 24px; height: 24px;">
                <path
                    d="M0 916.021l0 -738.234q0 -35.154 24.413 -59.567t59.567 -24.413l134.757 0l0 -62.496q0 -13.671 8.789 -22.46t22.46 -8.789 22.46 8.789 8.789 22.46l0 62.496l187.488 0l0 -62.496q0 -13.671 8.789 -22.46t22.46 -8.789 22.46 8.789 8.789 22.46l0 62.496l187.488 0l0 -62.496q0 -13.671 8.789 -22.46t22.46 -8.789 22.46 8.789 8.789 22.46l0 62.496l134.757 0q35.154 0 59.567 24.413t24.413 59.567l0 738.234q0 35.154 -24.413 59.567t-59.567 24.413l-831.978 0q-35.154 0 -59.567 -24.413t-24.413 -59.567zm62.496 0q0 9.765 5.859 15.624t15.624 5.859l831.978 0q9.765 0 15.624 -5.859t5.859 -15.624l0 -738.234q0 -9.765 -5.859 -15.624t-15.624 -5.859l-134.757 0l0 62.496q0 13.671 -8.789 22.46t-22.46 8.789 -22.46 -8.789 -8.789 -22.46l0 -62.496l-187.488 0l0 62.496q0 13.671 -8.789 22.46t-22.46 8.789 -22.46 -8.789 -8.789 -22.46l0 -62.496l-187.488 0l0 62.496q0 13.671 -8.789 22.46t-22.46 8.789 -22.46 -8.789 -8.789 -22.46l0 -62.496l-134.757 0q-9.765 0 -15.624 5.859t-5.859 15.624l0 738.234zm156.24 -134.757l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm218.736 312.48l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm218.736 312.48l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0z"></path>
            </svg></h1>
		<?php if($data['cancelled']): ?>
			<p><?php _e('The booking has been cancelled', 'salon-booking-system'); ?></p>
		<?php endif ?>
		<?php if (!empty($data['upcoming'])):?>
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th><?php _e('Booking','salon-booking-system');?></th>
					<th><?php _e('Date','salon-booking-system');?></th>
					<th><?php _e('Services','salon-booking-system');?></th>
					<th><?php _e('Assistant','salon-booking-system');?></th>
					<th><?php _e('Total','salon-booking-system');?></th>
					<th><?php _e('Status','salon-booking-system');?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $data['upcoming'] as $item ): ?>
					<tr>
						<td data-th="<?php _e('Booking','salon-booking-system');?>"><?php echo $item['id'] ?></td>
						<td data-th="<?php _e('Date','salon-booking-system');?>"><strong><?php echo $item['date'] ?></strong></td>
						<td data-th="<?php _e('Services','salon-booking-system');?>"><?php echo $item['services']; ?></td>
						<td data-th="<?php _e('Assistant','salon-booking-system');?>"><?php echo $item['assistant'] ?></td>
						<td data-th="<?php _e('Total','salon-booking-system');?>"><nobr><strong><?php echo $item['total'] ?></strong></nobr></td>
						<td data-th="<?php _e('Status','salon-booking-system');?>">
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
											<?php _e('Cancel booking','salon-booking-system');?>
										</button>
									<?php else: ?>
									<button class="btn" data-toggle="tooltip" data-placement="top" style="cursor: not-allowed;"
									        title="<?php _e('Sorry, you cannot cancel this booking online. Please call ' . $data['gen_phone'], 'salon-booking-system'); ?>">
										<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> 
										<?php _e('Cancel booking','salon-booking-system');?>
									</button>
									<?php endif ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p class="sln-my-account-message"><strong><?php _e('No upcoming bookings', 'salon-booking-system'); ?></strong></p>
		<?php endif; ?>
	</div>

	<div>
		<h1><?php _e('Bookings history','salon-booking-system');?><svg class="icocal" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"
                 preserveAspectRatio="xMinYMin meet" width="100%" height="100%"
                 style="width: 24px; height: 24px;">
                <path
                    d="M0 916.021l0 -738.234q0 -35.154 24.413 -59.567t59.567 -24.413l134.757 0l0 -62.496q0 -13.671 8.789 -22.46t22.46 -8.789 22.46 8.789 8.789 22.46l0 62.496l187.488 0l0 -62.496q0 -13.671 8.789 -22.46t22.46 -8.789 22.46 8.789 8.789 22.46l0 62.496l187.488 0l0 -62.496q0 -13.671 8.789 -22.46t22.46 -8.789 22.46 8.789 8.789 22.46l0 62.496l134.757 0q35.154 0 59.567 24.413t24.413 59.567l0 738.234q0 35.154 -24.413 59.567t-59.567 24.413l-831.978 0q-35.154 0 -59.567 -24.413t-24.413 -59.567zm62.496 0q0 9.765 5.859 15.624t15.624 5.859l831.978 0q9.765 0 15.624 -5.859t5.859 -15.624l0 -738.234q0 -9.765 -5.859 -15.624t-15.624 -5.859l-134.757 0l0 62.496q0 13.671 -8.789 22.46t-22.46 8.789 -22.46 -8.789 -8.789 -22.46l0 -62.496l-187.488 0l0 62.496q0 13.671 -8.789 22.46t-22.46 8.789 -22.46 -8.789 -8.789 -22.46l0 -62.496l-187.488 0l0 62.496q0 13.671 -8.789 22.46t-22.46 8.789 -22.46 -8.789 -8.789 -22.46l0 -62.496l-134.757 0q-9.765 0 -15.624 5.859t-5.859 15.624l0 738.234zm156.24 -134.757l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm218.736 312.48l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm218.736 312.48l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0zm0 -156.24l0 -93.744l124.992 0l0 93.744l-124.992 0z"></path>
            </svg></h1>
		<?php if (!empty($data['history'])):?>
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th><?php _e('Booking','salon-booking-system');?></th>
					<th><?php _e('Date','salon-booking-system');?></th>
					<th><?php _e('Services','salon-booking-system');?></th>
					<th><?php _e('Assistant','salon-booking-system');?></th>
					<th><?php _e('Total','salon-booking-system');?></th>
					<th><?php _e('Status','salon-booking-system');?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $data['history'] as $item ): ?>
					<tr>
						<td data-th="<?php _e('Booking','salon-booking-system');?>"><?php echo $item['id'] ?></td>
						<td data-th="<?php _e('Date','salon-booking-system');?>"><strong><?php echo $item['date'] ?></strong></td>
						<td data-th="<?php _e('Services','salon-booking-system');?>"><?php echo $item['services'] ?></td>
						<td data-th="<?php _e('Assistant','salon-booking-system');?>"><?php echo $item['assistant'] ?></td>
						<td data-th="<?php _e('Total','salon-booking-system');?>"><nobr><strong><?php echo $item['total'] ?></strong></nobr></td>
						<td data-th="<?php _e('Status','salon-booking-system');?>">
							<div class="status <?php echo SLN_Enum_BookingStatus::getColor($item['status_code']); ?>">
								<nobr>
									<span class="glyphicon <?php echo SLN_Enum_BookingStatus::getIcon($item['status_code']); ?>" aria-hidden="true"></span>
									<span class="glyphicon-class"><?php echo $item['status']; ?></span>
								</nobr>
							</div>

							<div>
								<?php if($item['status_code'] == SLN_Enum_BookingStatus::PAY_LATER OR $item['status_code'] == SLN_Enum_BookingStatus::PAID   OR $item['status_code'] == SLN_Enum_BookingStatus::CONFIRMED): ?>
										<?php if(empty($item['rating'])): ?>
										<button class="btn btn-default sln-rate-service" onclick="slnMyAccount.showRateForm(<?php echo $item['id']; ?>);">
											<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> 
											<?php _e('Rate our service','salon-booking-system');?>
										</button>
										<?php endif; ?>
<!--										<span>--><?php //_e('Your rating ','salon-booking-system');?><!--</span>-->
									<input type="hidden" name="sln-rating" value="<?php echo $item['rating']; ?>">
									<div class="rating" id="<?php echo $item['id']; ?>" style="display: none;"></div>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<div id="ratingModal" class="modal fade" role="dialog" tabindex="-1">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"></h4>
						</div>
						<div class="modal-body">
							<div id="step1">
								<p><?php _e('Hi','salon-booking-system');?> <?php echo $data['user_name'] ?>!</p>
								<p><?php _e('How was your expirience with us this time? (required)','salon-booking-system');?></p>
								<p><textarea id="" placeholder="<?php _e('please, drop us some lines to understand if your expirience has been  in line  with your expectrations','salon-booking-system');?>"></textarea></p>
								<p>
									<div class="rating" id="<?php echo $item['id']; ?>"></div>
									<span><?php _e('Rate our service (required)','salon-booking-system');?></span>
								</p>
								<p>
									<button type="button" class="btn btn-primary" onclick="slnMyAccount.sendRate();"><?php _e('Send your review','salon-booking-system');?></button>
									<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Cancel','salon-booking-system');?></button>
								</p>
							</div>
							<div id="step2">
								<p><?php _e('Thank you for your review. It will help us improving our services.','salon-booking-system');?></p>
								<p><?php _e('We hope to see you again at','salon-booking-system');?> <?php echo $data['gen_name']; ?></p>
							</div>
						</div>
						<div class="modal-footer">

						</div>
					</div>

				</div>
			</div>

		<?php else: ?>
			<p><?php _e('No bookings', 'salon-booking-system'); ?></p>
		<?php endif; ?>
	</div>
