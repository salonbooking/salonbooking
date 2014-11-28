<?php
add_action( 'init', 'sln_register_post_type_service' );

/* Filter post updated messages for custom post types. */
add_filter( 'post_updated_messages', 'sln_service_updated_messages' );

add_filter( 'enter_title_here', 'sln_service_enter_title_here', 10, 2 );

function sln_register_post_type_service() {

	$args = array(
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => true,
#		'show_in_nav_menus'   => false,
#		'show_ui'             => true,
		'show_in_menu'        => 'saloon',
#		'show_in_admin_bar'   => true,
#		'menu_position'       => null,
#		'menu_icon'           => null,
/*
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => true,
		'query_var'           => 'sln_service',
		'capability_type'     => 'sln_service',
		'map_meta_cap'        => true,

		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_sln_service',
			'read_post'              => 'read_sln_service',
			'delete_post'            => 'delete_sln_service',

			// primitive/meta caps
			'create_posts'           => 'create_sln_services',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_sln_services',
			'edit_others_posts'      => 'manage_sln',
			'publish_posts'          => 'manage_sln',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_sln',
			'delete_private_posts'   => 'manage_sln',
			'delete_published_posts' => 'manage_sln',
			'delete_others_posts'    => 'manage_sln',
			'edit_private_posts'     => 'edit_sln_services',
			'edit_published_posts'   => 'edit_sln_services'
		),
*/
		'rewrite' => array(
			'slug'       => '/services/items',
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
			'name'               => __( 'Services',                   'sln' ),
			'singular_name'      => __( 'Service',                    'sln' ),
			'menu_name'          => __( 'Saloon',                     'sln' ),
	          	'name_admin_bar'     => __( 'Saloon Service',             'sln' ),
			'all_items'          => __( 'Services',                   'sln' ),
			'add_new'            => __( 'Add Service',                'sln' ),
			'add_new_item'       => __( 'Add New Service',            'sln' ),
			'edit_item'          => __( 'Edit Service',               'sln' ),
			'new_item'           => __( 'New Service',                'sln' ),
			'view_item'          => __( 'View Service',               'sln' ),
			'search_items'       => __( 'Search Services',            'sln' ),
			'not_found'          => __( 'No services found',          'sln' ),
			'not_found_in_trash' => __( 'No services found in trash', 'sln' ),

			'archive_title'      => __( 'Services Archive', 'sln' ),
		)
	);
	/* Register the post type. */
	register_post_type( 'sln_service', $args );
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
function sln_service_enter_title_here( $title, $post ) {

	if ( 'sln_service' === $post->post_type )
		$title = __( 'Enter service name', 'sln' );

	return $title;
}

/**
 * @since  1.0.0
 * @access public
 * @return void
 */
function sln_service_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['sln_service'] = array(
		 0 => '', // Unused. Messages start at index 1.
		 1 => sprintf( __( 'Service updated. <a href="%s">View service</a>', 'sln' ), esc_url( get_permalink( $post_ID ) ) ),
		 2 => '',
		 3 => '',
		 4 => __( 'Service updated.', 'sln' ),
		 5 => isset( $_GET['revision'] ) ? sprintf( __( 'Service restored to revision from %s', 'sln' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		 6 => sprintf( __( 'Service published. <a href="%s">View service</a>', 'sln' ), esc_url( get_permalink( $post_ID ) ) ),
		 7 => __( 'Service saved.', 'sln' ),
		 8 => sprintf( __( 'Service submitted. <a target="_blank" href="%s">Preview service</a>', 'sln' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		 9 => sprintf( __( 'Service scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview service</a>', 'sln' ), date_i18n( __( 'M j, Y @ G:i', 'restaurant' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Service draft updated. <a target="_blank" href="%s">Preview service</a>', 'sln' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);


	return $messages;
}
