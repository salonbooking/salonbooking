<?php
class SLN_Privacy_Exporters{
	public static function customer_data_exporter( $email_address, $page ) {
		$user           = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.
		$data_to_export = array();

		if ( $user instanceof WP_User ) {
			$customer_personal_data = self::get_customer_personal_data( $user );
			if ( ! empty( $customer_personal_data ) ) {
				$data_to_export[] = array(
					'group_id'    => 'sln_customer',
					'group_label' => __( 'Customer Data', 'salon-booking-system' ),
					'item_id'     => 'user',
					'data'        => $customer_personal_data,
				);
			}
		}

		return array(
			'data' => $data_to_export,
			'done' => true,
		);
	}

	/**
	 * Get personal data (key/value pairs) for a user object.
	 *
	 * @param WP_User $user user object.
	 * @return array
	 */
	protected static function get_customer_personal_data( $user ) {
		$personal_data = array();				

		$props_to_export = apply_filters( 'sln_privacy_export_customer_personal_data_props', SLN_Enum_CheckoutFields::toArray()
		, $user );

		foreach ( $props_to_export as $prop => $description ) {
			$value = get_user_meta($user->ID, '_sln_'.$prop, true);

			$value = apply_filters( 'sln_privacy_export_customer_personal_data_prop_value', $value, $prop, $user );

			if ( $value ) {
				$personal_data[] = array(
					'name'  => $description,
					'value' => $value,
				);
			}
		}

		/**
		 * Allow extensions to register their own personal data for this customer for the export.
		 *
		 */
		$personal_data = apply_filters( 'sln_privacy_export_customer_personal_data', $personal_data, $user );

		return $personal_data;
	}
}
