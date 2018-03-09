<?php
$plugin = SLN_Plugin::getInstance();
?>

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
	<div class="sln-box sln-box--main">
		<h2 class="sln-box-title"><?php _e('Facebook login','salon-booking-system') ?></h2>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 form-group">
				<div class="sln-checkbox">
					<?php $this->row_input_checkbox('enabled_fb_login', __('Enable Facebook login', 'salon-booking-system')); ?>
					<p class="sln-input-help"><?php _e('-', 'salon-booking-system') ?></p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 form-group sln-input--simple">
				<?php $this->row_input_text('fb_app_id', __('Facebook application ID', 'salon-booking-system')); ?>
				<p class="sln-input-help"><?php _e('-', 'salon-booking-system') ?></p>
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
            <div class="sln-box sln-box--main sln-box--main--small">
                <h2 class="sln-box-title"><?php _e('Advanced Discount System','salon-booking-system') ?></h2>
                <div class="row">
                    <div class="col-xs-12 form-group">
                        <div class="sln-checkbox">
                            <?php $this->row_input_checkbox('enable_discount_system', __('Enable', 'salon-booking-system')); ?>
                            <p class="sln-input-help"><?php _e('Check this box if you want to enable the Discount section', 'salon-booking-system') ?></p>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-8">
			<div class="sln-box sln-box--main sln-box--main--small">
				<h2 class="sln-box-title"><?php _e('Checkout form fields', 'salon-booking-system'); ?>
					<span class="block"><?php _e('Use this option to control the form fields to checkout', 'salon-booking-system') ?></span>
				</h2>
				<div class="row">
					<div class="col-xs-6 col-md-8"></div>
					<div class="col-xs-3 col-md-2"><?php _e('Hide', 'salon-booking-system') ?></div>
					<div class="col-xs-3 col-md-2"><?php _e('Required', 'salon-booking-system') ?></div>
				</div>
				<?php foreach(SLN_Enum_CheckoutFields::toArray() as $field => $title): ?>
					<?php $settings = (SLN_Enum_CheckoutFields::isRequiredByDefault($field) ? array('attrs' => array('disabled' => 'disabled')) : array()); ?>
					<div class="row">
						<div class="col-xs-6 col-md-8"><?php echo SLN_Enum_CheckoutFields::getSettingLabel($field); ?></div>
						<div class="col-xs-3 col-md-2 form-group">
							<div class="sln-checkbox">
								<?php SLN_Form::fieldCheckbox(
										"salon_settings[checkout_fields][{$field}][hide]",
										SLN_Enum_CheckoutFields::isHidden($field),
										$settings
								); ?>
								<label for="salon_settings_checkout_fields_<?php echo $field ?>_hide"></label>
							</div>
							<?php if (SLN_Enum_CheckoutFields::isRequiredByDefault($field)) {
								SLN_Form::fieldText(
										"salon_settings[checkout_fields][{$field}][hide]",
										false,
										array('type' => 'hidden')
								);
							} ?>
						</div>
						<div class="col-xs-3 col-md-2 form-group">
							<div class="sln-checkbox">
								<?php SLN_Form::fieldCheckbox(
										"salon_settings[checkout_fields][{$field}][require]",
										SLN_Enum_CheckoutFields::isRequired($field),
										$settings
								); ?>
								<label for="salon_settings_checkout_fields_<?php echo $field ?>_require"></label>
							</div>
							<?php if (SLN_Enum_CheckoutFields::isRequiredByDefault($field)) {
								SLN_Form::fieldText(
										"salon_settings[checkout_fields][{$field}][require]",
										true,
										array('type' => 'hidden')
								);
							} ?>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
