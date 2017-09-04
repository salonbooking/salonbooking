<?php

if (!class_exists('WP_Posts_List_Table')) {
	_get_list_table('WP_Posts_List_Table');
}

class SLB_Discount_Admin_DiscountsHistoryList extends WP_Posts_List_Table {

	protected function get_bulk_actions() {
		return array();
	}

	protected function extra_tablenav($which) {
		return;
	}

	protected function row_actions($actions, $always_visible = false) {
		return '';
	}

	public function get_columns() {
		$ret = array(
			'booking_customer'  => __('Customer', 'salon-booking-system'),
			'booking_date'      => __('Booking date', 'salon-booking-system'),
			'booking_amount'    => __('Booking amount', 'salon-booking-system'),
			'booking_discount'  => __('Booking discount', 'salon-booking-system'),
		);

		return $ret;
	}

	protected function get_items_per_page( $option, $default = 20 ) {
		global $wp_query;
		$wp_query->set('meta_key', '_sln_booking_discount_' . $_GET['post']);
		$wp_query->set('meta_value', '1');
		$wp_query->get_posts();
		parent::get_items_per_page($option, $default);
	}

	public function column_default( $post, $column_name ) {
		if ($column_name === 'booking_customer') {
			$booking = SLN_Plugin::getInstance()->createBooking($post);
			echo $booking->getDisplayName();
		}
		elseif ($column_name === 'booking_amount' || $column_name === 'booking_discount') {
			$booking  = SLN_Plugin::getInstance()->createBooking($post);
			$dAmount  = $booking->getMeta('discount_amount');
			if ($dAmount) {
				$dAmount = array_sum($dAmount);
			}

			if ($column_name === 'booking_amount') {
				$value = $booking->getAmount() + (float) $dAmount;
			}
			else {
				$value = $dAmount;
			}
			echo SLN_Plugin::getInstance()->format()->money($value, false);
		}
		else {
			parent::column_default($post, $column_name);
		}
	}

	protected function display_tablenav($which) {
		if ('top' === $which) {
			wp_referer_field();
		}
		?>
		<div class="tablenav <?php echo esc_attr($which); ?>">

			<?php if ($this->has_items()): ?>
				<div class="alignleft actions bulkactions">
					<?php $this->bulk_actions($which); ?>
				</div>
			<?php endif;
			$this->extra_tablenav($which);
			$this->pagination($which);
			?>

			<br class="clear" />
		</div>
		<?php
	}
}