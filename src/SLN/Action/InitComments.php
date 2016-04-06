<?php

class SLN_Action_InitComments
{
    public function __construct()
    {
        add_filter('manage_edit-comments_columns', array($this, 'add_comment_columns'));
        add_filter('manage_comments_custom_column', array($this, 'comment_column'), 10, 2);
        add_filter('comment_text', array($this, 'comment_text'), 10, 3);
    }

    public function comment_text($comment_text, $comment, $args)
    {
        $current_screen = get_current_screen();
        if ((empty($current_screen) || $current_screen->base != 'edit-comments') && $comment->comment_type == 'sln_review') {
            $post = get_post($comment->comment_post_ID);
            if ($post->post_type == 'sln_booking') {
                $rating = get_post_meta($post->ID, '_sln_booking_rating', true);

                return '<script> jQuery(document).ready(function() { slnMyAccount.createRatings(); }); </script>
                            <div><input type="hidden" name="sln-rating" value="'.$rating.'"/>
                            <div class="rating" style="display: none;"></div></div>'.$comment_text;
            }
        }

        return $comment_text;
    }

    public function add_comment_columns($columns)
    {
        $columns['sln_rating_column'] = __('Rating', 'salon-booking-system');

        return $columns;
    }

    public function comment_column($column, $comment_ID)
    {
        if ('sln_rating_column' == $column) {
            $comment = get_comment($comment_ID);
            if ($comment->comment_type == 'sln_review') {
                $post = get_post($comment->comment_post_ID);
                if ($post->post_type == 'sln_booking') {
                    $rating = get_post_meta($post->ID, '_sln_booking_rating', true);

                    echo '<input type="hidden" name="sln-rating" value="'.$rating.'">
                            <div class="rating" style="display: none;"></div><a name="salon-review"></a>';
                }
            }
        }
    }

}