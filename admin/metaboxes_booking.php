<?php
final class SLN_Booking_Post_Meta_Boxes {

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function setup() {

		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );

		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
	}

	/**
	 * Adds the meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function add_meta_boxes() {

		add_meta_box( 
			'sln-booking-details', 
			__( 'Menu Item Details', 'sln' ), 
			array( __CLASS__, 'details_meta_box' ), 
			'sln_booking', 
			'side', 
			'core' 
		);
	}

	/**
	 * Displays the "booking booking details" meta box.  Currently, this only holds a single text field for 
	 * entering the booking booking price.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $object  Current post object.
	 * @param  array   $box
	 * @return void
	 */
	public static function details_meta_box( $object, $box ) { ?>

		<input type="hidden" name="sln_booking_details_meta_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<p>
			<label for="sln-booking-price"><?php _e( 'Price', 'sln' ); ?></label>
			<br />
			<input type="text" class="widefat" name="sln-booking-price" id="sln-booking-price" value="<?php echo esc_attr( sln_get_booking_price( $object->ID ) ); ?>" />
		</p>

		<p>
			<label for="sln-booking-date"><?php _e( 'Date', 'sln' ); ?></label>
			<br />
			<input type="text" class="widefat" name="sln-booking-date" id="sln-booking-date" value="<?php echo esc_attr( sln_get_booking_date( $object->ID ) ); ?>" />
		</p>


		<?php do_action( 'sln_booking_details_meta_box', $object, $box );
	}

	/**
	 * Saves the custom post meta for the booking booking.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @param  object  $post
	 * @return void
	 */
	public static function save_post( $post_id, $post ) {

		/* Verify the nonce. */
		if ( !isset( $_POST['sln_booking_details_meta_nonce'] ) || !wp_verify_nonce( $_POST['sln_booking_details_meta_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		/* Don't save if the post is only a revision. */
		if ( 'revision' == $post->post_type )
			return;

		$meta = array(
			'_sln_booking_price' => floatval( strip_tags( $_POST['sln-booking-price'] ) ),
			'_sln_booking_date' => floatval( strip_tags( $_POST['sln-booking-date'] ) )
		);

		foreach ( $meta as $meta_key => $new_meta_value ) {

			/* Get the meta value of the custom field key. */
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			/* If a new meta value was added and there was no previous value, add it. */
			if ( $new_meta_value && '' == $meta_value )
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );

			/* If the new meta value does not match the old value, update it. */
			elseif ( $new_meta_value && $new_meta_value != $meta_value )
				update_post_meta( $post_id, $meta_key, $new_meta_value );

			/* If there is no new meta value but an old value exists, delete it. */
			elseif ( '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, $meta_key, $meta_value );
		}
	}
}

SLN_Booking_Post_Meta_Boxes::setup();
