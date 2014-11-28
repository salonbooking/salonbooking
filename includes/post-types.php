<?php
add_action( 'init', 'sln_register_post_types' );

/* Filter post updated messages for custom post types. */
add_filter( 'post_updated_messages', 'sln_post_updated_messages' );

add_filter( 'enter_title_here', 'sln_enter_title_here', 10, 2 );

/**
 * Registers post types needed by the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function sln_register_post_types() {

	/* Get plugin settings. */
	$settings = get_option( 'sln_settings', sln_get_default_settings() );

	/* Set up the arguments for the post type. */
	$args = array(
		'description'         => $settings['sln_booking_description'],
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => sln_sln_menu_base(),
		'query_var'           => 'sln_booking',
		'capability_type'     => 'sln_booking',
		'map_meta_cap'        => true,

		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_sln_booking',
			'read_post'              => 'read_sln_booking',
			'delete_post'            => 'delete_sln_booking',

			// primitive/meta caps
			'create_posts'           => 'create_sln_bookings',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_sln_bookings',
			'edit_others_posts'      => 'manage_sln',
			'publish_posts'          => 'manage_sln',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_sln',
			'delete_private_posts'   => 'manage_sln',
			'delete_published_posts' => 'manage_sln',
			'delete_others_posts'    => 'manage_sln',
			'edit_private_posts'     => 'edit_sln_bookings',
			'edit_published_posts'   => 'edit_sln_bookings'
		),

		'rewrite' => array(
			'slug'       => sln_sln_menu_base() . '/items',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'revisions',
		),

		'labels' => array(
			'name'               => __( 'Menu Items',                   'sln' ),
			'singular_name'      => __( 'Menu Item',                    'sln' ),
			'menu_name'          => __( 'Restaurant',                   'sln' ),
			'name_admin_bar'     => __( 'Restaurant Menu Item',         'sln' ),
			'all_items'          => __( 'Menu Items',                   'sln' ),
			'add_new'            => __( 'Add Menu Item',                'sln' ),
			'add_new_item'       => __( 'Add New Menu Item',            'sln' ),
			'edit_item'          => __( 'Edit Menu Item',               'sln' ),
			'new_item'           => __( 'New Menu Item',                'sln' ),
			'view_item'          => __( 'View Menu Item',               'sln' ),
			'search_items'       => __( 'Search Menu Items',            'sln' ),
			'not_found'          => __( 'No menu items found',          'sln' ),
			'not_found_in_trash' => __( 'No menu items found in trash', 'sln' ),

			/* Custom archive label.  Must filter 'post_type_archive_title' to use. */
			'archive_title'      => $settings['sln_booking_archive_title'],
		)
	);

	/* Register the post type. */
	register_post_type( 'sln_booking', $args );
}

/**
 * Custom "enter title here" text.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $title
 * @param  object  $post
 * @return string
 */
function sln_enter_title_here( $title, $post ) {

	if ( 'sln_booking' === $post->post_type )
		$title = __( 'Enter menu item name', 'sln' );

	return $title;
}

/**
 * @since  1.0.0
 * @access public
 * @return void
 */
function sln_post_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['sln_service'] = array(
		 0 => '', // Unused. Messages start at index 1.
		 1 => sprintf( __( 'Menu item updated. <a href="%s">View menu item</a>', 'sln' ), esc_url( get_permalink( $post_ID ) ) ),
		 2 => '',
		 3 => '',
		 4 => __( 'Menu item updated.', 'sln' ),
		 5 => isset( $_GET['revision'] ) ? sprintf( __( 'Menu item restored to revision from %s', 'sln' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		 6 => sprintf( __( 'Menu item published. <a href="%s">View menu item</a>', 'sln' ), esc_url( get_permalink( $post_ID ) ) ),
		 7 => __( 'Menu item saved.', 'sln' ),
		 8 => sprintf( __( 'Menu item submitted. <a target="_blank" href="%s">Preview menu item</a>', 'sln' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		 9 => sprintf( __( 'Menu item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview menu item</a>', 'sln' ), date_i18n( __( 'M j, Y @ G:i', 'restaurant' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Menu item draft updated. <a target="_blank" href="%s">Preview menu item</a>', 'sln' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	$messages['sln_booking'] = array(
		 0 => '', // Unused. Messages start at index 1.
		 1 => sprintf( __( 'Menu item updated. <a href="%s">View menu item</a>', 'sln' ), esc_url( get_permalink( $post_ID ) ) ),
		 2 => '',
		 3 => '',
		 4 => __( 'Menu item updated.', 'sln' ),
		 5 => isset( $_GET['revision'] ) ? sprintf( __( 'Menu item restored to revision from %s', 'sln' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		 6 => sprintf( __( 'Menu item published. <a href="%s">View menu item</a>', 'sln' ), esc_url( get_permalink( $post_ID ) ) ),
		 7 => __( 'Menu item saved.', 'sln' ),
		 8 => sprintf( __( 'Menu item submitted. <a target="_blank" href="%s">Preview menu item</a>', 'sln' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		 9 => sprintf( __( 'Menu item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview menu item</a>', 'sln' ), date_i18n( __( 'M j, Y @ G:i', 'restaurant' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Menu item draft updated. <a target="_blank" href="%s">Preview menu item</a>', 'sln' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);


	return $messages;
}
