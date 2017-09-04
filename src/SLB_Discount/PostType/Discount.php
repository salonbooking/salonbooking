<?php

class SLB_Discount_PostType_Discount extends SLN_PostType_Abstract
{

    public function init()
    {
        parent::init();

        if (is_admin()) {
            add_action('manage_'.$this->getPostType().'_posts_custom_column', array($this, 'manage_column'), 10, 2);
            add_filter('manage_'.$this->getPostType().'_posts_columns', array($this, 'manage_columns'));
            add_filter('manage_edit-'.$this->getPostType().'_sortable_columns', array($this, 'custom_columns_sort'));
            add_action('admin_head-post-new.php', array($this, 'posttype_admin_css'));
            add_action('admin_head-post.php', array($this, 'posttype_admin_css'));
            add_action('admin_enqueue_scripts', array($this, 'load_scripts'));
            add_action('wp_ajax_sln_discount', array($this, 'ajax'));
            add_filter('post_row_actions', array($this, 'post_row_actions'), 10, 2);
        }
    }

    public function custom_columns_sort( $columns ) {
        $custom = array(
            'title' => 'title',
        );
        return $custom;
    }

    public function load_scripts()
    {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
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

    public function post_row_actions($actions, $post) {
        if ($post->post_type === $this->getPostType()) {
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }

    public function manage_columns($columns)
    {
        $new_columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'discount_type' => __('Type', 'salon-booking-system'),
            'discount_amount' => __('Amount', 'salon-booking-system'),
            'active' => __('Active', 'salon-booking-system'),
            'discount_usages' => __('Discount usages', 'salon-booking-system'),
        );

        return $new_columns;
    }

    public function manage_column($column, $post_id)
    {
        $obj = SLB_Discount_Plugin::getInstance()->createDiscount($post_id);
        switch ($column) {
            case 'discount_type':
                $type = SLB_Discount_Enum_DiscountType::getLabel($obj->getDiscountType());
                echo $type;
                break;
            case 'discount_amount':
                $amount = $obj->getAmountString();
                echo $amount;
                break;
            case 'active':
                $now = new SLN_DateTime(current_time('mysql'));
                if ($now >= $obj->getStartsAt() && $now <= $obj->getEndsAt()) {
                    $status = __('Yes', 'salon-booking-system');
                }
                else {
                    $status = __('No', 'salon-booking-system');
                }
                echo $status;
                break;
            case 'discount_usages':
                echo $obj->getTotalUsagesNumber();
                break;
        }
    }

    public function enter_title_here($title, $post)
    {

        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter discount name', 'salon-booking-system');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        $messages[$this->getPostType()] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(
                __('Discount updated.', 'salon-booking-system')
            ),
            2 => '',
            3 => '',
            4 => __('Discount updated.', 'salon-booking-system'),
            5 => isset($_GET['revision']) ? sprintf(
                __('Discount restored to revision from %s', 'salon-booking-system'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6 => sprintf(
                __('Discount published.', 'salon-booking-system')
            ),
            7 => __('Discount saved.', 'salon-booking-system'),
            8 => sprintf(
                __('Discount submitted.', 'salon-booking-system')
            ),
            9 => '',
            10 => sprintf(
                __('Discount draft updated.', 'salon-booking-system')
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
                'name' => __('Discounts', 'salon-booking-system'),
                'singular_name' => __('Discount', 'salon-booking-system'),
                'menu_name' => __('Salon', 'salon-booking-system'),
                'name_admin_bar' => __('Salon Discount', 'salon-booking-system'),
                'all_items' => __('Discounts', 'salon-booking-system'),
                'add_new' => __('Add Discount', 'salon-booking-system'),
                'add_new_item' => __('Add New Discount', 'salon-booking-system'),
                'edit_item' => __('Edit Discount', 'salon-booking-system'),
                'new_item' => __('New Discount', 'salon-booking-system'),
                'view_item' => __('View Discount', 'salon-booking-system'),
                'search_items' => __('Search Discounts', 'salon-booking-system'),
                'not_found' => __('No discounts found', 'salon-booking-system'),
                'not_found_in_trash' => __('No discounts found in trash', 'salon-booking-system'),
                'archive_title' => __('Discounts Archive', 'salon-booking-system'),
            ),
            'capability_type' => array(SLN_Plugin::POST_TYPE_BOOKING, SLN_Plugin::POST_TYPE_BOOKING.'s'),
            'map_meta_cap' => true
        );
    }

    public function posttype_admin_css()
    {
        global $post_type;
        if ($post_type == $this->getPostType()) {
            $this->getPlugin()->loadView('metabox/_discount_head');
        }
    }
}