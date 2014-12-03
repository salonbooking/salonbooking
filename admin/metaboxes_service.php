<?php
final class SLN_Service_Post_Meta_Boxes {

	public static function setup() {

		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );

		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
	}

	public static function add_meta_boxes() {

		add_meta_box( 
			'sln-service-details', 
			__( 'Service Details', 'sln' ), 
			array( __CLASS__, 'details_meta_box' ), 
			'sln_service', 
			'side', 
			'core' 
		);
	}

	public static function details_meta_box( $object, $box ) { ?>

		<input type="hidden" name="sln_service_details_meta_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<p>
			<label for="sln-service-price"><?php _e( 'Price', 'sln' ); ?></label>
			<br />
			<input type="text" class="widefat" name="sln-service-price" id="sln-service-price" value="<?php echo esc_attr( sln_get_service_price( $object->ID ) ); ?>" />
		</p>
		<p>
			<label for="sln-service-unit"><?php _e( 'Unit per hour', 'sln' ); ?></label>
			<br />
			<input type="text" class="widefat" name="sln-service-unit" id="sln-service-unit" value="<?php echo esc_attr( sln_get_service_unit( $object->ID ) ); ?>" />
		</p>
		<p>
			<label for="sln-service-secondary"><?php _e( 'Secondary', 'sln' ); ?>
			<input type="checkbox" name="sln-service-secondary" id="sln-service-secondary" value="1" <?php echo sln_get_service_secondary( $object->ID ) ? 'checked="checked"' : ''?> />
                        </label>
		</p>
<strong>Not Available At</strong>
<?php
$timestamp = strtotime('next Sunday');
$days = array();
for ($i = 0; $i < 7; $i++) {
 $days[] = strftime('%A', $timestamp);
 $timestamp = strtotime('+1 day', $timestamp);
}
?>
<?php foreach($days as $k => $day){ ?>
<p>            <label><input type="checkbox" name="sln-service-notav-<?php echo $k?>" value="1" <?php echo sln_get_service_notav($object->ID, $k)? 'checked="checked"' : '' ?>/><?php echo substr($day, 0,3)?></label>
<?php } ?></p>
<?php foreach(array('from' => __('From','sln'), 'to' => __('To','sln')) as $k => $v){ ?>
        <p><label><?php echo $v ?><br/> <input type="text" name="sln-service-notav-<?php echo $k ?>" value="<?php echo sln_get_service_notav_time($object->ID,$k)?>" /> </label></p>
<?php } ?>
	
<?php do_action( 'sln_service_details_meta_box', $object, $box );
	}

	public static function save_post( $post_id, $post ) {
		/* Verify the nonce. */
		if ( !isset( $_POST['sln_service_details_meta_nonce'] ) || !wp_verify_nonce( $_POST['sln_service_details_meta_nonce'], plugin_basename( __FILE__ ) ) )
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
			'_sln_service_price' => floatval( strip_tags( $_POST['sln-service-price'] ) ),
			'_sln_service_unit' => floatval( strip_tags( $_POST['sln-service-unit'] ) ),
			'_sln_service_notav_from' => sln_filter( strip_tags( $_POST['sln-service-notav-from'] ), 'time' ),
			'_sln_service_notav_to' => sln_filter( strip_tags( $_POST['sln-service-notav-to'] ) , 'time'),
			'_sln_service_secondary' => sln_filter($_POST['sln-service-secondary'], 'bool')
		);
                for($i = 0; $i<7; $i++){
			$meta['_sln_service_notav_'.$i] = sln_filter($_POST['sln-service-notav-'.$i], 'bool');
                }
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

SLN_Service_Post_Meta_Boxes::setup();
