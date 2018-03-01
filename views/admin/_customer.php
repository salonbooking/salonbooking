<?php
/**
 * @var WP_Error|null $error
 */
?>
<div class="wrap sln-bootstrap" id="sln-salon--admin">
	<h1>
		<?php /** @var SLN_Wrapper_Customer $customer */ ?>
		<?php _e($customer->isEmpty() ? 'New Customer' : 'Edit Customer', 'salon-booking-system') ?>
		<?php /** @var string $new_link */ ?>
		<a href="<?php echo $new_link; ?>" class="page-title-action"><?php _e('Add Customer', 'salon-booking-system'); ?></a>
	</h1>
	<br>

	<?php if(is_wp_error($error)): ?>
        <div class="error">
            <?php foreach ($error->get_error_messages() as $message): ?>
                <p><?php echo $message ?></p>
            <?php endforeach; ?>
        </div>
	<?php endif; ?>
	<form method="post">
	<div class="sln-tab">
		
	<div class="sln-admin-sidebar mobile affix-top">
		<input type="submit" name="save" value="<?php _e($customer->isEmpty() ? 'Publish' : 'Update', 'salon-booking-system'); ?>" class="sln-btn sln-btn--main sln-btn--big" />
	</div>

		<input type="hidden" name="id" id="id" value="<?php echo $customer->getId(); ?>">
			<div class="sln-box sln-box--main">
				<div class="row">
					<div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Customer details', 'salon-booking-system') ?></h2></div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-3 form-group sln_meta_field sln-input--simple">
						<label for="_sln_customer_first_name"><?php _e('First name', 'salon-booking-system') ?></label>
						<input type="text" name="sln_customer[first_name]" id="_sln_customer_first_name" value="<?php echo $customer->get('first_name'); ?>" class="form-control">
					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 form-group sln_meta_field sln-input--simple">
						<label for="_sln_customer_last_name"><?php _e('Last name', 'salon-booking-system') ?></label>
						<input type="text" name="sln_customer[last_name]" id="_sln_customer_last_name" value="<?php echo $customer->get('last_name'); ?>" class="form-control">
					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 form-group sln_meta_field sln-input--simple">
						<label for="_sln_customer_email"><?php _e('E-mail', 'salon-booking-system') ?></label>
						<input type="text" name="sln_customer[user_email]" id="_sln_customer_email" value="<?php echo $customer->get('user_email'); ?>" class="form-control" required>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 form-group sln_meta_field sln-input--simple">
						<label for="_sln_customer_sln_phone"><?php _e('Phone', 'salon-booking-system') ?></label>
						<input type="text" name="sln_customer_meta[_sln_phone]" id="_sln_customer_sln_phone" value="<?php echo $customer->get('_sln_phone'); ?>" class="form-control">
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 form-group sln_meta_field sln-input--simple">
						<label for="_sln_customer_sln_address"><?php _e('Address', 'salon-booking-system') ?></label>
						<textarea type="text" name="sln_customer_meta[_sln_address]" id="_sln_customer_sln_address" rows="5" class="form-control"><?php echo $customer->get('_sln_address'); ?></textarea>
					</div>
				</div>
				<div >
				<div class="sln-box--sub row">
					<div class="col-xs-12  form-group sln_meta_field sln-input--simple">
							<label for="_sln_customer_sln_personal_note"><?php _e('Personal note', 'salon-booking-system') ?></label>
							<textarea type="text" name="sln_customer_meta[_sln_personal_note]" id="_sln_customer_sln_personal_note" class="form-control" rows="5"><?php echo $customer->get('_sln_personal_note'); ?></textarea>
					</div>
				</div>
				</div>
			</div>
		<div class="sln-box sln-box--main">
			<h2 class="sln-box-title"><?php _e('Customer\'s bookings', 'salon-booking-system') ?></h2>
			<div class="sln-box--sub row">
				<div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Booking statistics', 'salon-booking-system') ?></h2></div>
				<div class="col-xs-12">
				<div class="statistics_block sln-table">
			<div class="row statistics_row hidden-xs">
				<div class="col-xs-2 col-md-2 col-lg-2 col-sm-2">
					<?php _e('Reservations made and value', 'salon-booking-system') ?>
				</div>
				<div class="col-xs-2 col-md-2 col-lg-2 col-sm-2">
					<?php _e('Reservations per month', 'salon-booking-system') ?>
				</div>
				<div class="col-xs-2 col-md-2 col-lg-2 col-sm-2">
					<?php _e('Reservations per week', 'salon-booking-system') ?>
				</div>
				<div class="col-xs-2 col-md-2 col-lg-2 col-sm-2">
					<?php _e('Services booked per single reservation', 'salon-booking-system') ?>
				</div>
				<div class="col-xs-2 col-md-2 col-lg-2 col-sm-2">
					<?php _e('Favourite week days', 'salon-booking-system') ?>
				</div>
				<div class="col-xs-2 col-md-2 col-lg-2 col-sm-2">
					<?php _e('Favourite time', 'salon-booking-system') ?>
				</div>
			</div>
			<div class="row statistics_row">
				<div class="col-xs-12 visible-xs-block">
					<span class="statistics_block_desc"><?php _e('Reservations made and value', 'salon-booking-system') ?></span>
				</div>
				<div class="col-xs-12 col-md-2 col-lg-2 col-sm-2">
					<span>
						<?php
						$count  = $customer->getCountOfReservations();
						$amount = SLN_Plugin::getInstance()->format()->money($customer->getAmountOfReservations(), false);

						echo "$count ($amount)";
						?>
					</span>
				</div>
				<div class="col-xs-12 visible-xs-block">
					<span class="statistics_block_desc"><?php _e('Reservations per month', 'salon-booking-system') ?></span>
				</div>
				<div class="col-xs-12 col-md-2 col-lg-2 col-sm-2">
					<span>
						<?php
						$countPerMonth  = $customer->getCountOfReservations(MONTH_IN_SECONDS);
						$amountPerMonth = SLN_Plugin::getInstance()->format()->money($customer->getAmountOfReservations(MONTH_IN_SECONDS), false);

						echo "$countPerMonth ($amountPerMonth)";
						?>
					</span>
				</div>
				<div class="col-xs-12 visible-xs-block">
					<span class="statistics_block_desc"><?php _e('Reservations per week', 'salon-booking-system') ?></span>
				</div>
				<div class="col-xs-12 col-md-2 col-lg-2 col-sm-2">
					<span>
						<?php
						$countPerWeek  = $customer->getCountOfReservations(WEEK_IN_SECONDS);
						$amountPerWeek = SLN_Plugin::getInstance()->format()->money($customer->getAmountOfReservations(WEEK_IN_SECONDS), false);

						echo "$countPerWeek ($amountPerWeek)";
						?>
					</span>
				</div>
				<div class="col-xs-12 visible-xs-block">
					<span class="statistics_block_desc"><?php _e('Services booked per single reservation', 'salon-booking-system') ?></span>
				</div>
				<div class="col-xs-12 col-md-2 col-lg-2 col-sm-2">
					<span>
						<?php echo $customer->getAverageCountOfServices(); ?>
					</span>
				</div>
				<div class="col-xs-12 visible-xs-block">
					<span class="statistics_block_desc"><?php _e('Favourite week days', 'salon-booking-system') ?></span>
				</div>
				<div class="col-xs-12 col-md-2 col-lg-2 col-sm-2">
					<span>
						<?php
						$favDays = $customer->getFavouriteWeekDays();
						if ($favDays) {
							foreach($favDays as &$favDay) {
								$favDay = SLN_Enum_DaysOfWeek::getLabel($favDay);
							}

							$favDaysText = implode(', ', $favDays);
						}
						else {
							$favDaysText = __('not avalable yet', 'salon-booking-system');
						}

						echo $favDaysText;
						?>
					</span>
				</div>
				<div class="col-xs-12 visible-xs-block">
					<span class="statistics_block_desc"><?php _e('Favourite time', 'salon-booking-system') ?></span>
				</div>
				<div class="col-xs-12 col-md-2 col-lg-2 col-sm-2">
					<span>
						<?php
						$favTimes = $customer->getFavouriteTimes();
						if ($favTimes) {
							$favTimesText = implode(', ', $favTimes);
						}
						else {
							$favTimesText = __('not avalable yet', 'salon-booking-system');
						}

						echo $favTimesText;
						?>
					</span>
				</div>
			</div>
			
					</div>
				</div>
			<!-- .sln-box-sub.row END-->
			</div>
			
		<?php if ($customer->getBookings()): ?>
			<div class="sln-box--sub row">
				<div class="col-xs-12"><h2 class="sln-box-title"><?php _e('Booking history', 'salon-booking-system') ?></h2></div>
				<div class="col-xs-12 sln-table">
				<?php

				$_GET['post_type'] = SLN_Plugin::POST_TYPE_BOOKING;
				$_GET['author'] = $customer->getId();
				get_current_screen()->add_option('post_type', SLN_Plugin::POST_TYPE_BOOKING);
				get_current_screen()->id = 'edit-sln_booking';
				get_current_screen()->post_type = SLN_Plugin::POST_TYPE_BOOKING;

				/** @var SLN_Admin_Customers_BookingsList $wp_list_table */
				$wp_list_table = new SLN_Admin_Customers_BookingsList();

				$wp_list_table->prepare_items();

				$wp_list_table->display();
				?>
				</div>
			</div>
		<?php endif; ?>
		<!-- sln-box-main END -->
		</div>
	</div>
	</form>

</div>