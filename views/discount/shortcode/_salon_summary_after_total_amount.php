<?php
/**
 * @var SLN_Plugin $plugin
 * @var int $size
 */
?>

<?php echo ($size === SLN_Enum_ShortcodeStyle::getSize(SLN_Enum_ShortcodeStyle::_LARGE) ? '<div class="row">' : ''); ?>
	<div class="col-xs-12 col-sm-12 col-md-12 sln-input sln-input--simple">
		<?php
		$args = array(
			'label'        => __('Do you have any discount code?', 'salon-booking-system'),
			'tag'          => 'label',
			'textClasses'  => '',
			'inputClasses' => '',
			'tagClasses'   => '',
		);
		echo $plugin->loadView('shortcode/_editable_snippet', $args);
		?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6 sln-input sln-input--simple">
		<?php SLN_Form::fieldText(
			'sln[discount]',
			'',
			array('attrs' => array('placeholder' => __('key in your coupon code', 'salon-booking-system')))
		); ?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
			<button data-salon-toggle="discount" id="sln_discount_btn" type="button" onclick="applyDiscountCode();">
				<?php _e('Apply', 'salon-booking-system'); ?>
			</button>
		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div id="sln_discount_status"></div>
	</div>
<?php echo ($size === SLN_Enum_ShortcodeStyle::getSize(SLN_Enum_ShortcodeStyle::_LARGE) ? '</div>' : ''); ?>