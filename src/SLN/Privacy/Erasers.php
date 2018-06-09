<?php
class SLN_Privacy_Erasers{
	public static function customer_data_eraser( $email_address, $page ) {
		$response = array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);

		$user = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

		if ( ! $user instanceof WP_User ) {
			return $response;
		}

		$props_to_erase = apply_filters( 'sln_privacy_erase_customer_personal_data_props', SLN_Enum_CheckoutFields::toArray(), $user );

		foreach ( $props_to_erase as $prop => $label ) {
			$erased = delete_user_meta( $user->ID, '_sln_'.$prop);

			$erased = apply_filters( 'sln_privacy_erase_customer_personal_data_prop', $erased, $prop, $user );

			if ( $erased ) {
				/* Translators: %s Prop name. */
				$response['messages'][]    = sprintf( __( 'Removed customer "%s"', 'salon-booking-system' ), $label );
				$response['items_removed'] = true;
			}
		}
		
		/**
		 * Allow extensions to remove data for this customer and adjust the response.
		 *
		 * @since 3.4.0
		 * @param array    $response Array resonse data. Must include messages, num_items_removed, num_items_retained, done.
		 * @param WP_User.
		 */
		return apply_filters( 'sln_privacy_erase_personal_data_customer', $response, $user );
	}
}