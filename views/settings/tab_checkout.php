<?php
$plugin = SLN_Plugin::getInstance();
?>
<div class="sln-tab" id="sln-tab-checkout">
	<div class="sln-box sln-box--main">
		<h2 class="sln-box-title"><?php _e('Checkout options','salon-booking-system') ?></h2>
		<div class="row">
			<div class="col-xs-12 col-md-6 form-group">
				<div class="sln-checkbox">
					<?php $this->row_input_checkbox('enabled_guest_checkout', __('Enable guest checkout', 'salon-booking-system')); ?>
					<p class="sln-input-help"><?php _e('If enabled users can checkout as a guest and no account will be created for them.', 'salon-booking-system') ?></p>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 form-group">
				<div class="sln-checkbox">
					<?php $this->row_input_checkbox('enabled_force_guest_checkout', __('Enable force guest checkout', 'salon-booking-system')); ?>
					<p class="sln-input-help"><?php _e('If enabled all users will checkout as a guest and no account will be created for them.', 'salon-booking-system') ?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="sln-box sln-box--main sln-box--main--small">
		<h2 class="sln-box-title"><?php _e('Services selection limit','salon-booking-system') ?></h2>
		<div class="row">
				<div class="col-xs-12 form-group sln-select ">
					<label for="salon_settings_services_count"><?php _e('Services to be booked simultaneously','salon-booking-system') ?></label>
					<?php echo SLN_Form::fieldSelect(
							'salon_settings[services_count]',
							array(
								''   => "No limits",
								'1'  => "1",
								'2'  => "2",
								'3'  => "3",
								'4'  => "4",
								'5'  => "5",
								'6'  => "6",
								'7'  => "7",
								'8'  => "8",
								'9'  => "9",
								'10' => "10",
							),
							$this->settings->get('services_count'),
							array(),
							true
					) ?>
					<p class="sln-input-help"><?php _e('Set this option if you want to limit the number of services bookable during a single reservation.','salon-booking-system');?></p>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>