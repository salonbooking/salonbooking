<!-- algolplus -->
<div>
	<div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li><a><?php echo sprintf(__('Welcome back %s!','salon-booking-system'), $data['user_name']); ?></a></li>
			<li role="presentation" class="active"><a href="#new" aria-controls="new" role="tab" data-toggle="tab"><?php _e('Next appointments', 'salon-booking-system') ?></a></li>
			<li role="presentation"><a href="#old" aria-controls="old" role="tab" data-toggle="tab"><?php _e('Reservations history', 'salon-booking-system') ?></a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="new">
				<?php if($data['cancelled']): ?>
					<p><?php _e('The booking has been cancelled', 'salon-booking-system'); ?></p>
				<?php endif ?>
				<?php if (!empty($data['new']['items'])):?>
					<p><?php _e('Here you have your next reservations with us, pay attention to the \'pending\' reservations', 'salon-booking-system'); ?></p>
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
						<?php foreach ( $data['new']['items'] as $item ): ?>
							<tr>
								<td data-th="<?php _e('Booking','salon-booking-system');?>"><?php echo $item['id'] ?></td>
								<td data-th="<?php _e('Date','salon-booking-system');?>"><strong><?php echo $item['date'] ?></strong></td>
								<td data-th="<?php _e('Services','salon-booking-system');?>"><?php echo $item['services']; ?></td>
								<?php if($data['attendant_enabled']): ?>
									<td data-th="<?php _e('Assistant','salon-booking-system');?>"><?php echo $item['assistant'] ?></td>
								<?php endif; ?>
								<?php if(!$data['hide_prices']): ?>
									<td data-th="<?php _e('Total','salon-booking-system');?>"><nobr><strong><?php echo $item['total'] ?></strong></nobr></td>
								<?php endif; ?>
								<td data-th="<?php _e('Status','salon-booking-system');?>">
									<div class="status">
										<nobr>
											<span class="glyphicon <?php echo SLN_Enum_BookingStatus::getIcon($item['status_code']); ?>" aria-hidden="true"></span>
											<span class="glyphicon-class"><?php echo $item['status']; ?></span>
										</nobr>
									</div>
								</td>
								<td data-th="<?php _e('Action','salon-booking-system');?>" class="col-md-2">
									<div>
										<?php if (in_array($item['status_code'], array(SLN_Enum_BookingStatus::PENDING, SLN_Enum_BookingStatus::PENDING_PAYMENT)) && $data['pay_enabled']): ?>
											<?php
											$booking = SLN_Plugin::getInstance()->createBooking($item['id']); ?>
											<div class="col-xs-offset-1 col-xs-10 col-sm-offset-3 col-sm-6 col-md-offset-0 col-md-12">
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
										<?php if ($item['status_code'] != SLN_Enum_BookingStatus::CANCELED
										          && $data['cancellation_enabled']): ?>
											<div class="col-xs-offset-1 col-xs-10 col-sm-offset-3 col-sm-6 col-md-offset-0 col-md-12">
												<?php if ($item['timestamp']-current_time('timestamp') > $data['seconds_before_cancellation']): ?>
													<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth danger">
														<button onclick="slnMyAccount.cancelBooking(<?php echo $item['id']; ?>);">
															<?php _e('Cancel booking','salon-booking-system');?>
														</button>
													</div>
												<?php else: ?>
													<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth disabled">
														<button data-toggle="tooltip" data-placement="top" style="cursor: not-allowed;"
														        title="<?php _e('Sorry, you cannot cancel this booking online. Please call ' . $data['gen_phone'], 'salon-booking-system'); ?>">
															<?php _e('Cancel booking','salon-booking-system');?>
														</button>
													</div>
												<?php endif ?>
											</div>
											<div style="clear: both"></div>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php else: ?>
					<p class="sln-my-account-message"><strong><?php _e('You don\'t have upcoming reservations, do you want to re-schedule your last appointment with us?', 'salon-booking-system'); ?></strong></p>
				<?php endif; ?>
				<div class="col-xs-10 col-sm-6 col-md-4">
					<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
						<a href="<?php echo $data['booking_url'] ?>"><?php _e('MAKE A NEW RESERVATION', 'salon-booking-system') ?></a>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="old">
				<?php if (!empty($data['history']['items'])):?>
					<p><?php _e('Here you have your past reservations, you can submit a review or re-schedule an appointment', 'salon-booking-system'); ?></p>

					<div id="sln-salon-my-account-history-content">
						<?php include '_salon_my_account_details_history_table.php' ?>
					</div>

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

					<div class="col-xs-1 col-sm-1 col-md-1 pull-right">
						<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth disabled" id="next_history_page_btn">
							<button type="button" onclick="slnMyAccount.loadNextHistoryPage();"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span></button>
						</div>
					</div>
				<?php else: ?>
					<p><?php _e('No bookings', 'salon-booking-system'); ?></p>
				<?php endif; ?>
				<div class="col-xs-10 col-sm-6 col-md-4">
					<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
						<a href="<?php echo $data['booking_url'] ?>"><?php _e('MAKE A NEW RESERVATION', 'salon-booking-system') ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
