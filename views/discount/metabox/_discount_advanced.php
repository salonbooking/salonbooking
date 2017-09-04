<?php
/**
 * @var SLN_Plugin $plugin
 * @var SLN_Settings $settings
 * @var SLN_Metabox_Helper $helper
 * @var SLB_Discount_Wrapper_Discount $discount
 * @var string $postType
 *
 */
?>

<div class="row">
	<div class="col-md-8 sln-input--simple">
		<label><?php echo __('This is a', 'salon-booking-system') ?></label>
		<div class="row">
			<?php
			$items = SLB_Discount_Enum_DiscountType::toArray();
			foreach ($items as $k => $title) {
			    ?>
			    <div class="sln-radiobox sln-radiobox--fullwidth col-md-6">
				    <span></span> <!-- don't delete it -->
			        <?php
			        SLN_Form::fieldRadiobox(
				        $helper->getFieldName($postType, 'type'),
				        $k,
				        $k === $discount->getDiscountType(),
				        array(
					        'attrs' => array(
						        'data-type' => 'discount-type'
					        )
				        )
			        );
			        ?>
			        <label for="<?php echo SLN_Form::makeID($helper->getFieldName($postType, 'type').'['.$k.']'); ?>"><?php echo $title; ?></label>
			    </div>
			    <?php
			}
			?>
		</div>
	</div>
	<div class="sln_discount_type sln_discount_type--<?php echo SLB_Discount_Enum_DiscountType::DISCOUNT_CODE; ?> <?php echo ( $discount->getDiscountType() === SLB_Discount_Enum_DiscountType::DISCOUNT_CODE ? '' : 'hide'); ?>">
		<div class="col-md-4 sln-input--simple">
			<label><?php echo __('Code to be used', 'salon-booking-system') ?></label>
			<?php
			$code = $discount->getCouponCode();
			$code = !empty($code) ? $code : $discount::generateCouponCode();
			SLN_Form::fieldText($helper->getFieldName($postType, 'code'), $code); ?>
			<p><?php _e('Copy this code and give it to your customers','salon-booking-system'); ?></p>
		</div>
	</div>
    <div class="sln-clear"></div>
</div>

<div class="row sln_discount_type sln_discount_type--<?php echo SLB_Discount_Enum_DiscountType::DISCOUNT_AUTO; ?> <?php echo ( $discount->getDiscountType() === SLB_Discount_Enum_DiscountType::DISCOUNT_AUTO ? '' : 'hide'); ?>">
	<div class="col-md-12 sln-input--simple">
		<h2 class="sln-box-title"><?php _e('Automatic discount settings', 'salon-booking-system'); ?></h2>
		<label><?php _e('Automatically apply when the rules are met', 'salon-booking-system'); ?></label>
		<div id="sln_discount_rules">
			<?php
			$rules = $discount->getDiscountRules();
			$rules['__new_discount_rule__'] = array(
				'mode'            => 'bookings',
				'bookings_number' => '',
				'amount_number'   => '',
				'daterange_from'  => '',
				'daterange_to'    => '',
				'weekdays'        => array(),
			);
			foreach($rules as $i => $rule): ?>
				<div class="sln_discount_rule <?php echo ($i === '__new_discount_rule__' ? 'hide' : ''); ?>" data-rule-id="<?php echo $i; ?>">
					<div class="row">
						<div class="col-md-8 sln-select">
							<?php SLN_Form::fieldSelect(
								$helper->getFieldName($postType, "rules[$i][mode]"),
								array(
									'bookings'  => __('Reservations collected by a single customer', 'salon-booking-system'),
									'amount'    => __('Reservations amount collected by a single customer', 'salon-booking-system'),
									'daterange' => __('On these specific date period', 'salon-booking-system'),
									'weekdays'  => __('On these specific days of the week', 'salon-booking-system'),
								),
								$rule['mode'],
								array(
									'attrs' => array(
										'data-type' => 'discount-rule-mode'
									)
								),
								true
							);
							?>
						</div>
						<div class="col-md-2">
							<button type="button" class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash"
							        data-action="remove-discount-rule"><?php echo __('Remove rule', 'salon-booking-system') ?></button>
						</div>
					</div>
					<div class="row">
						<!-- bookings mode -->
						<div class="sln_discount_rule_mode_details sln_discount_rule_mode_details--bookings <?php echo ($rule['mode'] === 'bookings' ? '' : 'hide'); ?>">
							<div class="col-md-4 sln-input--simple">
								<?php SLN_Form::fieldText($helper->getFieldName($postType, "rules[$i][bookings_number]"), $rule['bookings_number']); ?>
								<p><?php _e('Number of reservations','salon-booking-system'); ?></p>
							</div>
						</div>
						<!-- amount mode -->
						<div class="sln_discount_rule_mode_details sln_discount_rule_mode_details--amount <?php echo ($rule['mode'] === 'amount' ? '' : 'hide'); ?>">
							<div class="col-md-4 sln-input--simple">
								<?php SLN_Form::fieldText($helper->getFieldName($postType, "rules[$i][amount_number]"), $rule['amount_number']); ?>
								<p><?php _e('Amount of reservations','salon-booking-system'); ?></p>
							</div>
						</div>
						<!-- daterange mode -->
						<div class="sln_discount_rule_mode_details sln_discount_rule_mode_details--daterange <?php echo ($rule['mode'] === 'daterange' ? '' : 'hide'); ?>">
							<div class="col-md-4 sln-slider-wrapper">
								<div class="sln_datepicker">
									<?php SLN_Form::fieldJSDate(
										$helper->getFieldName($postType, "rules[$i][daterange_from]"),
										new SLN_DateTime($rule['daterange_from'])
									) ?>
								</div>
								<p><?php _e('From','salon-booking-system'); ?></p>
							</div>
							<div class="col-md-4 sln-slider-wrapper">
								<div class="sln_datepicker">
									<?php SLN_Form::fieldJSDate(
										$helper->getFieldName($postType, "rules[$i][daterange_to]"),
										new SLN_DateTime($rule['daterange_to'])
									) ?>
								</div>
								<p><?php _e('To','salon-booking-system'); ?></p>
							</div>
						</div>
						<!-- weekdays mode -->
						<div class="sln_discount_rule_mode_details sln_discount_rule_mode_details--weekdays <?php echo ($rule['mode'] === 'weekdays' ? '' : 'hide'); ?>">
							<div class="col-md-8 sln-select">
								<?php SLN_Form::fieldSelect(
									$helper->getFieldName($postType, "rules[$i][weekdays][]"),
									SLN_Enum_DaysOfWeek::toArray(),
									$rule['weekdays'],
									array('attrs' => array('multiple' => true, 'data-containerCssClass' => 'sln-select-wrapper-no-search')),
									true
								); ?>
								<p><?php _e('Select one or more days of the week where the discount is valid','salon-booking-system'); ?></p>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--file"
		        data-action="add-discount-rule"><?php echo __('Add new rule', 'salon-booking-system') ?></button>
	</div>
</div>

<div class="sln-clear"></div>
<?php do_action('sln.template.discount_advanced.metabox', $discount); ?>
