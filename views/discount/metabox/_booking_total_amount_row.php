<?php
/**
 * @var SLN_Wrapper_Booking $booking
 */
?>

<?php
$discounts = SLB_Discount_Helper_Booking::getBookingDiscounts($booking);
/** @var SLB_Discount_Wrapper_Discount $discount */
$discount = reset($discounts);
?>
<div class="col-md-2 col-sm-4">
	<div class="form-group sln-input--simple">
		<label for=""><?php _e('Discount applied', 'salon-booking-system'); ?></label>

		<div>
			<label><?php echo $discount->getAmountString(); ?></label>
		</div>
	</div>
</div>
