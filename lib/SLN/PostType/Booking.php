<?php

class SLN_PostType_Booking extends SLN_PostType_Abstract
{
    public function init()
    {
        parent::init();
        add_filter('wp_insert_post_data', array($this, 'insert_post_data'), '99', 2);

        if (is_admin()) {
            add_action('manage_' . $this->getPostType() . '_posts_custom_column', array($this, 'manage_column'), 10, 2);
            add_filter('manage_' . $this->getPostType() . '_posts_columns', array($this, 'manage_columns'));
        }
    }

    public function insert_post_data($data, $postarr)
    {
        if ($data['post_type'] == $this->getPostType()) {
            $data['post_title'] = 'Booking #' . $postarr['ID'];
        }

        return $data;
    }

    public function manage_columns($columns)
    {

        return array_merge(
            $columns,
            array(
                'booking_status' => __('Status', 'sln'),
                'booking_date'   => __('Booking Date', 'sln'),
                'date'           => __('Created At', 'sln')
            )
        );
    }

    public function manage_column($column, $post_id)
    {
        switch ($column) {
            case 'booking_status' :
                echo SLN_Enum_BookingStatus::getLabel(get_post_meta($post_id, '_sln_booking_status', true));
                break;
            case 'booking_date':
                $date = get_post_meta($post_id, '_sln_booking_date', true) . ' '
                    . get_post_meta($post_id, '_sln_booking_time', true);
                echo date_i18n(__('M j, Y @ G:i', 'restaurant'), strtotime($date));
                break;
        }
    }

    public function enter_title_here($title, $post)
    {
        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter booking name', 'sln');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->getPostType()] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf(
                __('Booking updated.', 'sln')
            ),
            2  => '',
            3  => '',
            4  => __('Booking updated.', 'sln'),
            5  => isset($_GET['revision']) ? sprintf(
                __('Booking restored to revision from %s', 'sln'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6  => sprintf(
                __('Booking published.', 'sln')
            ),
            7  => __('Booking saved.', 'sln'),
            8  => sprintf(
                __('Booking submitted.', 'sln')
            ),
            9  => sprintf(
                __(
                    'Booking scheduled for: <strong>%1$s</strong>.',
                    'sln'
                ),
                date_i18n(__('M j, Y @ G:i', 'restaurant'), strtotime($post->post_date))
            ),
            10 => sprintf(
                __('Booking draft updated.', 'sln')
            ),
        );


        return $messages;
    }

    protected function getPostTypeArgs()
    {
        return array(
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
    }
}