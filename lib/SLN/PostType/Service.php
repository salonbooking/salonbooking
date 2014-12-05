<?php

class SLN_PostType_Service extends SLN_PostType_Abstract
{
    function init()
    {

        $args = array(
            'public'              => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'show_in_menu'        => 'saloon',
            'rewrite'             => false,
            'supports'            => array(
                'title',
                'excerpt',
                'thumbnail',
                'revisions',
            ),
            'labels'              => array(
                'name'               => __('Services', 'sln'),
                'singular_name'      => __('Service', 'sln'),
                'menu_name'          => __('Saloon', 'sln'),
                'name_admin_bar'     => __('Saloon Service', 'sln'),
                'all_items'          => __('Services', 'sln'),
                'add_new'            => __('Add Service', 'sln'),
                'add_new_item'       => __('Add New Service', 'sln'),
                'edit_item'          => __('Edit Service', 'sln'),
                'new_item'           => __('New Service', 'sln'),
                'view_item'          => __('View Service', 'sln'),
                'search_items'       => __('Search Services', 'sln'),
                'not_found'          => __('No services found', 'sln'),
                'not_found_in_trash' => __('No services found in trash', 'sln'),
                'archive_title'      => __('Services Archive', 'sln'),
            )
        );
        register_post_type('sln_service', $args);
    }

    function enter_title_here($title, $post)
    {

        if ('sln_service' === $post->post_type) {
            $title = __('Enter service name', 'sln');
        }

        return $title;
    }

    function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages['sln_service'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf(
                __('Service updated. <a href="%s">View service</a>', 'sln'),
                esc_url(get_permalink($post_ID))
            ),
            2  => '',
            3  => '',
            4  => __('Service updated.', 'sln'),
            5  => isset($_GET['revision']) ? sprintf(
                __('Service restored to revision from %s', 'sln'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6  => sprintf(
                __('Service published. <a href="%s">View service</a>', 'sln'),
                esc_url(get_permalink($post_ID))
            ),
            7  => __('Service saved.', 'sln'),
            8  => sprintf(
                __('Service submitted. <a target="_blank" href="%s">Preview service</a>', 'sln'),
                esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))
            ),
            9  => sprintf(
                __(
                    'Service scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview service</a>',
                    'sln'
                ),
                date_i18n(__('M j, Y @ G:i', 'restaurant'), strtotime($post->post_date)),
                esc_url(get_permalink($post_ID))
            ),
            10 => sprintf(
                __('Service draft updated. <a target="_blank" href="%s">Preview service</a>', 'sln'),
                esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))
            ),
        );


        return $messages;
    }
}