<div class="wrap sln-bootstrap" id="sln-salon--admin">
	<h1><?php _e('Customers', 'salon-booking-system') ?>
		<?php /** @var string $new_link */ ?>
	<a href="<?php echo $new_link; ?>" class="page-title-action"><?php _e('Add Customer', 'salon-booking-system'); ?></a>
	</h1>

<form method="get">
	<input type="hidden" name="page" class="post_type_page" value="salon-customers">
	<?php
	/** @var SLN_Admin_Customers_List $table */
	$table->display();
	?>
</form>
<br class="clear" />

</div>
