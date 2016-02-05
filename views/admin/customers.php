<div class="wrap sln-bootstrap" id="sln-salon--admin">
	<h1><?php _e( 'Customers', 'salon-booking-system' ) ?>
	<a href="user-new.php" class="page-title-action"><?php echo esc_html_x( 'Add Customer', 'salon-booking-system' ); ?></a>
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
