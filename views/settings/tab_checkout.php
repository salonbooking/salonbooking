<?php
$plugin = SLN_Plugin::getInstance();
?>
<div class="sln-tab" id="sln-tab-checkout">
	<div class="sln-box sln-box--main">
		<h2 class="sln-box-title"><?php _e('Checkout options <span> - </span>','salon-booking-system') ?></h2>
		<div class="row">
			<div class="col-sm-10 col-md-10 form-group">
				<div class="sln-checkbox">
					<?php $this->row_input_checkbox('enabled_guest_checkout', __('Enable guest checkout', 'salon-booking-system')); ?>
					<p class="sln-input-help"><?php _e('If enabled users can checkout as a guest and no account will be created for them.', 'salon-booking-system') ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10 col-md-10 form-group">
				<div class="sln-checkbox">
					<?php $this->row_input_checkbox('enabled_force_guest_checkout', __('Enable force guest checkout', 'salon-booking-system')); ?>
					<p class="sln-input-help"><?php _e('If enabled all users will checkout as a guest and no account will be created for them.', 'salon-booking-system') ?></p>
				</div>
			</div>
		</div>
	</div>
</div>