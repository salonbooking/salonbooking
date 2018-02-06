<?php foreach ( $data['table_data']['items'] as $item ): ?>
	<tr
		<?php echo isset($data['table_data']['page']) ? 'data-page="'.$data['table_data']['page'].'"' : ''; ?>
		<?php echo isset($data['table_data']['end']) ? 'data-end="'.$data['table_data']['end'].'"' : ''; ?>
	>
		<td data-th="<?php _e('ID','salon-booking-system');?>"><?php echo $item['id'] ?></td>
		<td data-th="<?php _e('When','salon-booking-system');?>"><div><?php echo $item['date'] ?></div><div><?php echo $item['time'] ?></div></td>
		<td data-th="<?php _e('Services','salon-booking-system');?>"><?php echo $item['services'] ?></td>
		<?php if($data['attendant_enabled']): ?>
			<td data-th="<?php _e('Assistants','salon-booking-system');?>"><?php echo $item['assistant'] ?></td>
		<?php endif; ?>
		<?php if(!$data['hide_prices']): ?>
			<td data-th="<?php _e('Price','salon-booking-system');?>"><nobr><?php echo $item['total'] ?></nobr></td>
		<?php endif; ?>
		<td data-th="<?php _e('Status','salon-booking-system');?>">
			<div class="status">
				<nobr>
					<span class="glyphicon <?php echo SLN_Enum_BookingStatus::getIcon($item['status_code']); ?>" aria-hidden="true"></span>
					<span class="glyphicon-class"><strong><?php echo $item['status']; ?></strong></span>
				</nobr>
			</div>
			<div>
				<?php if($data['table_data']['mode'] === 'history' || $item['timestamp'] < current_time('timestamp')): ?>
					<?php if(in_array($item['status_code'], array(SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::CONFIRMED))): ?>
						<input type="hidden" name="sln-rating" value="<?php echo $item['rating']; ?>">
						<div class="rating" id="<?php echo $item['id']; ?>" style="display: none;"></div>
                        <div class="feedback"><?php echo $item['feedback'] ?></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</td>
		<td data-th="<?php _e('Action','salon-booking-system');?>" class="col-md-3">
			<div>
				<?php if($data['table_data']['mode'] === 'history'): ?>
					<!-- SECTION OLD START -->
					<?php if(in_array($item['status_code'], array(SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::CONFIRMED))): ?>
						<?php if(empty($item['rating'])): ?>
							<div class="col-xs-10 col-sm-6 col-md-12">
								<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth sln-rate-service">
									<button onclick="slnMyAccount.showRateForm(<?php echo $item['id']; ?>);">
										<?php _e('Leave a feedback','salon-booking-system');?>
									</button>
								</div>
							</div>
							<div style="clear: both"></div>
						<?php endif; ?>
					<?php endif; ?>
					<!-- SECTION OLD END -->
				<?php elseif($data['table_data']['mode'] === 'new'): ?>
					<!-- SECTION NEW START -->
					<?php if ($item['timestamp'] < current_time('timestamp')): ?>
						<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
							<a href="<?php echo $data['booking_url'] ?>"><?php _e('Book now', 'salon-booking-system') ?></a>
						</div>
					<?php
						continue;
						endif;
					?>

					<?php if (in_array($item['status_code'], array(SLN_Enum_BookingStatus::PENDING_PAYMENT)) && $data['pay_enabled']): ?>
						<?php
						$booking = SLN_Plugin::getInstance()->createBooking($item['id']); ?>
						<div class="col-xs-10 col-sm-6 col-md-12">
							<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
								<a href="<?php echo $booking->getPayUrl(); ?>">
									<?php _e('Pay Now','salon-booking-system');?>
								</a>
							</div>
						</div>
						<div style="clear: both"></div>

						<?php if (SLN_Plugin::getInstance()->getSettings()->get('pay_offset_enabled')) : ?>
							<div>
								<?php echo sprintf(__('You have <strong>%s</strong> to complete your payment before this reservation being canceled','salon-booking-system'), $booking->getTimeStringToChangeStatusFromPending()); ?>
							</div>
							<div style="clear: both"></div>
							<br>
						<?php endif ?>
					<?php endif; ?>
					<?php if ($data['cancellation_enabled']): ?>
						<div class="col-xs-10 col-sm-6 col-md-12">
							<?php 							
							if ($item['timestamp']-current_time('timestamp',1) > $data['seconds_before_cancellation']): ?>
								<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth danger">
									<button onclick="slnMyAccount.cancelBooking(<?php echo $item['id']; ?>);">
										<?php _e('Cancel booking','salon-booking-system');?>
									</button>
								</div>
							<?php else: ?>
								<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth disabled">
									<button data-toggle="tooltip" data-placement="top" style="cursor: not-allowed;"
									        title="<?php echo sprintf(__('Sorry, you cannot cancel this booking online. Please call %s', 'salon-booking-system'),$data['gen_phone']); ?>">
										<?php _e('Cancel booking','salon-booking-system');?>
									</button>
								</div>
							<?php endif ?>
						</div>
						<div style="clear: both"></div>
					<?php endif; ?>
					<!-- SECTION NEW END -->
				<?php endif; ?>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
