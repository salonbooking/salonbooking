<!-- algolplus -->
<div id="sln-salon" class="sln-bootstrap">
	<div class="mobile-version">
		<div class="col-md-12"><?php echo sprintf(__('Hi %s!','salon-booking-system'), $data['user_name']); ?></div>
		<div class="col-md-8"><?php _e('are you planning to make a new reservation?','salon-booking-system') ?></div>
		<div class="col-md-4">
			<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
				<button type="submit" onclick="window.location.href='<?php echo $data['booking_url'] ?>'">
					<?php _e('NEW RESERVATION', 'salon-booking-system') ?>
				</button>
			</div>
		</div>
	</div>
	<div id="sln-salon-my-account">

	</div>
</div>
