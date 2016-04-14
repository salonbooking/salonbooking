<?php

class SLN_PostType_Service extends SLN_PostType_Abstract
{

    public function init()
    {
        parent::init();

        if (is_admin()) {
            add_action('pre_get_posts', array($this, 'admin_posts_sort'));
            add_action('wp_insert_post', array($this, 'wp_insert_post'));
            add_action('manage_'.$this->getPostType().'_posts_custom_column', array($this, 'manage_column'), 10, 2);
            add_filter('manage_'.$this->getPostType().'_posts_columns', array($this, 'manage_columns'));
            add_action('admin_head-post-new.php', array($this, 'posttype_admin_css'));
            add_action('admin_head-post.php', array($this, 'posttype_admin_css'));
            add_action('admin_enqueue_scripts', array($this, 'load_scripts'));
            add_action('wp_ajax_sln_service', array($this, 'ajax'));
        }
    }

    function admin_posts_sort($query)
    {
        global $pagenow, $post_type;

        if (is_admin() && 'edit.php' == $pagenow && $post_type == $this->getPostType()) {
            /** @var SLN_Repository_ServiceRepository $repo */
            $repo = $this->getPlugin()->getRepository($this->getPostType());
            foreach ($repo->getStandardCriteria() as $k => $v) {
                $query->set($k, $v);
            }
        }
    }

    public function load_scripts()
    {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
    }

    public function wp_insert_post($post_id, $wp_error = false)
    {
        global $post_type;

        if ($post_type == $this->getPostType()) {
            if (!get_post_meta($post_id, '_sln_service_order', true)) {
                $count_pages = wp_count_posts($this->getPostType());
                $pos = $count_pages->publish + 1;
                add_post_meta($post_id, '_sln_service_order', $pos, true);
            }
        }
    }

    public function ajax()
    {
        if (isset($_POST['method'])) {
            $method = 'ajax_'.$_POST['method'];
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
        die();
    }

    public function ajax_save_position()
    {
        parse_str($_POST['data'], $params);

        if (!isset($params['positions'])) {
            return;
        }

        foreach (explode(',', $params['positions']) as $item) {
            list($post_id, $pos) = explode('_', $item);
            update_post_meta($post_id, '_sln_service_order', $pos);
        }
    }

    public function ajax_save_cat_position()
    {
        global $wpdb;
        parse_str($_POST['data'], $params);

        if (!isset($params['positions'])) {
            return;
        }

        update_option(SLN_Plugin::CATEGORY_ORDER, $params['positions']);
    }

    public function manage_columns($columns)
    {
        $new_columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'service_duration' => __('Duration', 'salon-booking-system'),
            'service_price' => __('Price', 'salon-booking-system'),
            'taxonomy-sln_service_category' => $columns['taxonomy-sln_service_category'],
            'sln_days_off' => __('Availability', 'salon-booking-system'),
        );

//        return array_merge(
//            $columns, array(
//            'service_duration' => __('Duration', 'salon-booking-system'),
//            'service_price' => __('Price', 'salon-booking-system')
//            )
//        );
        return $new_columns;
    }

    public function manage_column($column, $post_id)
    {
        $obj = $this->getPlugin()->createService($post_id);
        switch ($column) {
            case 'service_duration':
                $time = SLN_Func::filter($obj->getDuration(), 'time');
                echo $time ? $time : '-';
                break;
            case 'service_price' :
                echo $this->getPlugin()->format()->money($obj->getPrice());
                break;
            case 'sln_days_off' :
                echo implode('<br/>',$obj->getAvailabilityItems()->toArray());
                break;
        }
    }

    public function enter_title_here($title, $post)
    {

        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter service name', 'salon-booking-system');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->getPostType()] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(
                __('Service updated.', 'salon-booking-system')
            ),
            2 => '',
            3 => '',
            4 => __('Service updated.', 'salon-booking-system'),
            5 => isset($_GET['revision']) ? sprintf(
                __('Service restored to revision from %s', 'salon-booking-system'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6 => sprintf(
                __('Service published.', 'salon-booking-system')
            ),
            7 => __('Service saved.', 'salon-booking-system'),
            8 => sprintf(
                __('Service submitted.', 'salon-booking-system')
            ),
            9 => sprintf(
                __(
                    'Service scheduled for: <strong>%1$s</strong>. ',
                    'salon-booking-system'
                ),
                date_i18n(__('M j, Y @ G:i', 'restaurant'), strtotime($post->post_date))
            ),
            10 => sprintf(
                __('Service draft updated.', 'salon-booking-system')
            ),
        );


        return $messages;
    }

    protected function getPostTypeArgs()
    {
        return array(
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'show_in_menu' => 'salon',
            'rewrite' => false,
            'supports' => array(
                'title',
                'excerpt',
                'thumbnail',
                'revisions',
            ),
            'labels' => array(
                'name' => __('Services', 'salon-booking-system'),
                'singular_name' => __('Service', 'salon-booking-system'),
                'menu_name' => __('Salon', 'salon-booking-system'),
                'name_admin_bar' => __('Salon Service', 'salon-booking-system'),
                'all_items' => __('Services', 'salon-booking-system'),
                'add_new' => __('Add Service', 'salon-booking-system'),
                'add_new_item' => __('Add New Service', 'salon-booking-system'),
                'edit_item' => __('Edit Service', 'salon-booking-system'),
                'new_item' => __('New Service', 'salon-booking-system'),
                'view_item' => __('View Service', 'salon-booking-system'),
                'search_items' => __('Search Services', 'salon-booking-system'),
                'not_found' => __('No services found', 'salon-booking-system'),
                'not_found_in_trash' => __('No services found in trash', 'salon-booking-system'),
                'archive_title' => __('Services Archive', 'salon-booking-system'),
            ),
            'capability_type' => array($this->getPostType(), $this->getPostType().'s'),
            'map_meta_cap' => true
        );
    }

    function posttype_admin_css()
    {
        global $post_type;
        if ($post_type == $this->getPostType()) {

            ?>
            <style type="text/css">
                #post-preview, #view-post-btn,
                #edit-slug-box {
                    display: none;
                }
            </style>
            <?php
        }
    }
}
