<?php

class SLN_PostType_Booking extends SLN_PostType_Abstract
{
    public function init()
    {
        /* Set up the arguments for the post type. */
        $args = array(
            'description'         => __('This is where bookings are stored.', 'sln'),
            'public'              => true,
            'show_ui'             => true,
            /*              'capability_type'     => 'sln_booking',*/
            'map_meta_cap'        => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_in_menu'        => 'saloon',
            'hierarchical'        => false,
            'show_in_nav_menus'   => true,
            'rewrite'             => false,
            'query_var'           => false,
            'supports'            => array('title', 'comments', 'custom-fields'),
            'has_archive'         => false,
            'rewrite'             => false,
            'supports'            => array(
                'revisions',
            ),
            'labels'              => array(
                'name'               => __('Bookings', 'sln'),
                'singular_name'      => __('Booking', 'sln'),
                'menu_name'          => __('Saloon', 'sln'),
                'name_admin_bar'     => __('Saloon Booking', 'sln'),
                'all_items'          => __('Bookings', 'sln'),
                'add_new'            => __('Add Booking', 'sln'),
                'add_new_item'       => __('Add New Booking', 'sln'),
                'edit_item'          => __('Edit Booking', 'sln'),
                'new_item'           => __('New Booking', 'sln'),
                'view_item'          => __('View Booking', 'sln'),
                'search_items'       => __('Search Bookings', 'sln'),
                'not_found'          => __('No bookings found', 'sln'),
                'not_found_in_trash' => __('No bookings found in trash', 'sln'),
                'archive_title'      => __('Booking Archive', 'sln'),
            )
        );

        /* Register the post type. */
        register_post_type('sln_booking', $args);
    }

    public function enter_title_here($title, $post)
    {

        if ('sln_booking' === $post->post_type) {
            $title = __('Enter booking name', 'sln');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages['sln_booking'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf(
                __('Booking updated. <a href="%s">View booking</a>', 'sln'),
                esc_url(get_permalink($post_ID))
            ),
            2  => '',
            3  => '',
            4  => __('Booking updated.', 'sln'),
            5  => isset($_GET['revision']) ? sprintf(
                __('Booking restored to revision from %s', 'sln'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6  => sprintf(
                __('Booking published. <a href="%s">View booking</a>', 'sln'),
                esc_url(get_permalink($post_ID))
            ),
            7  => __('Booking saved.', 'sln'),
            8  => sprintf(
                __('Booking submitted. <a target="_blank" href="%s">Preview booking</a>', 'sln'),
                esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))
            ),
            9  => sprintf(
                __(
                    'Booking scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview booking</a>',
                    'sln'
                ),
                date_i18n(__('M j, Y @ G:i', 'restaurant'), strtotime($post->post_date)),
                esc_url(get_permalink($post_ID))
            ),
            10 => sprintf(
                __('Booking draft updated. <a target="_blank" href="%s">Preview booking</a>', 'sln'),
                esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))
            ),
        );


        return $messages;
    }
}