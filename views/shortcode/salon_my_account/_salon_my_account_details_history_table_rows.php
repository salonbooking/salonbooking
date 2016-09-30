<?php foreach ( $data['history']['items'] as $item ): ?>
	<tr data-page="<?php echo $data['history']['page'] ?>" data-end="<?php echo $data['history']['end'] ?>">
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
				<?php if($item['status_code'] == SLN_Enum_BookingStatus::PAY_LATER OR $item['status_code'] == SLN_Enum_BookingStatus::PAID   OR $item['status_code'] == SLN_Enum_BookingStatus::CONFIRMED): ?>
					<input type="hidden" name="sln-rating" value="<?php echo $item['rating']; ?>">
					<div class="rating" id="<?php echo $item['id']; ?>" style="display: none;"></div>
				<?php endif; ?>
			</div>
		</td>
		<td data-th="<?php _e('Action','salon-booking-system');?>" class="col-md-3">
			<div>
				<?php if($item['status_code'] == SLN_Enum_BookingStatus::PAY_LATER OR $item['status_code'] == SLN_Enum_BookingStatus::PAID   OR $item['status_code'] == SLN_Enum_BookingStatus::CONFIRMED): ?>
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
			</div>
		</td>
	</tr>
<?php endforeach; ?>
