<?php

class SLN_PostType_Attendant extends SLN_PostType_Abstract
{

    public function init()
    {
        parent::init();

        if (is_admin()) {
            add_action('manage_' . $this->getPostType() . '_posts_custom_column', array($this, 'manage_column'), 10, 2);
            add_filter('manage_' . $this->getPostType() . '_posts_columns', array($this, 'manage_columns'));
            add_action('admin_head-post-new.php', array($this, 'posttype_admin_css'));
            add_action('admin_head-post.php', array($this, 'posttype_admin_css'));
        }
    }

    public function manage_columns($columns)
    {

        $new_columns = array(
            'cb' => $columns['cb'],
            'sln_thumb' => __('Thumbnail', 'sln'),
            'title' => $columns['title'],
            'taxonomy-sln_service_category' => __('Skills', 'sln'),
            'sln_email' => __('Email', 'sln'),
            'sln_phone' => __('Telephone', 'sln'),
            'sln_days_off' => __('Days off', 'sln'),
        );
//        return array_merge(
//            $columns,
//            array(
//            )
//        );
        return $new_columns;
    }

    public function manage_column($column, $post_id)
    {
        switch ($column) {
            case 'sln_email':
                echo get_post_meta($post_id, '_sln_attendant_email', true);
                break;
            case 'sln_phone':
                echo get_post_meta($post_id, '_sln_attendant_phone', true);
                break;
            case 'sln_days_off':
                $days = SLN_Func::getDays();
                $new_days = array();
                foreach (get_post_meta($post_id) as $key => $meta) {
                    preg_match('#^_sln_attendant_notav_([0-9]+)$#', $key, $matches);
                    if (isset($matches[1]))
                        $new_days[] = $days[$matches[1]];
                }
                echo implode(', ', $new_days);
                break;
            case 'sln_thumb':
                echo get_the_post_thumbnail( $post_id, array(70, 70) );
                break; 
        }
    }

    public function enter_title_here($title, $post)
    {

        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter the assistant name', 'sln');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->getPostType()] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(
                __('Assistant updated.', 'sln')
            ),
            2 => '',
            3 => '',
            4 => __('Assistant updated.', 'sln'),
            5 => isset($_GET['revision']) ? sprintf(
                    __('Assistant restored to revision from %s', 'sln'), wp_post_revision_title((int) $_GET['revision'], false)
                ) : false,
            6 => sprintf(
                __('Assistant published.', 'sln')
            ),
            7 => __('Assistant saved.', 'sln'),
            8 => sprintf(
                __('Assistant submitted.', 'sln')
            ),
            9 => sprintf(
                __(
                    'Assistant scheduled for: <strong>%1$s</strong>. ', 'sln'
                ), date_i18n(__('M j, Y @ G:i', 'restaurant'), strtotime($post->post_date))
            ),
            10 => sprintf(
                __('Assistant draft updated.', 'sln')
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
                'name' => __('Assistants', 'sln'),
                'singular_name' => __('Assistant', 'sln'),
                'menu_name' => __('Salon', 'sln'),
                'name_admin_bar' => __('Salon Assistant', 'sln'),
                'all_items' => __('Assistants', 'sln'),
                'add_new' => __('Add Assistant', 'sln'),
                'add_new_item' => __('Add New Assistant', 'sln'),
                'edit_item' => __('Edit Assistant', 'sln'),
                'new_item' => __('New Assistant', 'sln'),
                'view_item' => __('View Assistant', 'sln'),
                'search_items' => __('Search Assistants', 'sln'),
                'not_found' => __('No assistants found', 'sln'),
                'not_found_in_trash' => __('No assistants found in trash', 'sln'),
                'archive_title' => __('Assistants Archive', 'sln'),
            )
        );
    }

    function posttype_admin_css()
    {
        global $post_type;
        if ($post_type == SLN_Plugin::POST_TYPE_SERVICE) {

            ?>
            <style type="text/css">
                #post-preview, #view-post-btn,
                #edit-slug-box
                {
                    display: none;
                }
            </style>
            <?php
        }
    }
}
