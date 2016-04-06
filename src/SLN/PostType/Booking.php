<?php

class SLN_PostType_Booking extends SLN_PostType_Abstract
{

    public function init()
    {
        parent::init();
//        add_filter('wp_insert_post_data', array($this, 'insert_post_data'), '99', 2);

        if (is_admin()) {
            add_action('manage_'.$this->getPostType().'_posts_custom_column', array($this, 'manage_column'), 10, 2);
            add_filter('manage_'.$this->getPostType().'_posts_columns', array($this, 'manage_columns'));
            add_action('admin_footer-post.php', array($this, 'bulkAdminFooterEdit'));
            add_action('admin_footer-post-new.php', array($this, 'bulkAdminFooterNew'));
            add_filter('display_post_states', array($this, 'bulkPostStates'));
            add_action('admin_head-post-new.php', array($this, 'posttype_admin_css'));
            add_action('admin_head-post.php', array($this, 'posttype_admin_css'));
        }
        $this->registerPostStatus();
    }

//    public function insert_post_data($data, $postarr)
//    {
//        if ($data['post_type'] == $this->getPostType()) {
//            $data['post_title'] = 'Booking #' . $postarr['ID'];
//        }
//
//        return $data;
//    }

    public function manage_columns($columns)
    {
        $ret = array(
            'cb' => $columns['cb'],
            'ID' => __('Booking ID'),
            'booking_date' => __('Booking Date', 'salon-booking-system'),
            'booking_status' => __('Status', 'salon-booking-system'),
            'myauthor' => __('User name', 'salon-booking-system'),
            'booking_attendant' => __('Attendant', 'salon-booking-system'),
//            'date' => __('Submitted', 'salon-booking-system'),
            'booking_price' => __('Booking Price', 'salon-booking-system'),
            'booking_services' => __('Booking Services', 'salon-booking-system'),
            'booking_review' => __('Booking Review', 'salon-booking-system'),
        );

        return $ret;
    }

    public function manage_column($column, $post_id)
    {
        $obj = $this->getPlugin()->createBooking($post_id);
        switch ($column) {
            case 'ID' :
                echo edit_post_link($post_id, '<p>', '</p>', $post_id);
                break;
            case 'myauthor':
                echo edit_post_link($obj->getDisplayName(), null, null, $post_id);
                break;
            case 'booking_status' :
                echo SLN_Enum_BookingStatus::getLabel(get_post_status($post_id));
                break;
            case 'booking_date':
                echo $this->getPlugin()->format()->datetime(
                    new DateTime(
                        get_post_meta($post_id, '_sln_booking_date', true)
                        .' '.get_post_meta($post_id, '_sln_booking_time', true)
                    )
                );
                break;
            case 'booking_price' :
                echo $this->getPlugin()->format()->money(get_post_meta($post_id, '_sln_booking_amount', true));
                if (get_post_status($post_id) == SLN_Enum_BookingStatus::PAID && $deposit = get_post_meta(
                        $post_id,
                        '_sln_booking_deposit',
                        true
                    )
                ) {
                    echo '(deposit '.$this->getPlugin()->format()->money($deposit).')';
                }
                break;
            case 'booking_services' :
                $name_services = array();
                foreach ($obj->getServices() as $helper) {
                    $name_services[] = $helper->getName();
                }
                echo implode(', ', $name_services);
                break;
            case 'booking_attendant' :
                echo $obj->getAttendantsString();
                break;
            case 'booking_review' :
                $comments = get_comments("post_id=$post_id&type=sln_review");
                $comment = isset($comments[0]) ? $comments[0] : null;

                echo '<input type="hidden" name="sln-rating" value="'.$obj->getRating().'">
                        <div class="rating" style="display: none;"></div>';

                if (!empty($comment)) {
                    echo '<a href="'.esc_url(add_query_arg(array('p' => $post_id), admin_url('edit-comments.php'))).'#salon-review"
                            class="overflow-dots">'.$comment->comment_content.'</a>';
                }

                break;
        }
    }

    public function enter_title_here($title, $post)
    {
        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter booking name', 'salon-booking-system');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->getPostType()] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(
                __('Booking updated.', 'salon-booking-system')
            ),
            2 => '',
            3 => '',
            4 => __('Booking updated.', 'salon-booking-system'),
            5 => isset($_GET['revision']) ? sprintf(
                __('Booking restored to revision from %s', 'salon-booking-system'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6 => sprintf(
                __('Booking published.', 'salon-booking-system')
            ),
            7 => __('Booking saved.', 'salon-booking-system'),
            8 => sprintf(
                __('Booking submitted.', 'salon-booking-system')
            ),
            9 => sprintf(
                __(
                    'Booking scheduled for: <strong>%1$s</strong>.',
                    'salon-booking-system'
                ),
                date_i18n(__('M j, Y @ G:i', 'salon-booking-system'), strtotime($post->post_date))
            ),
            10 => sprintf(
                __('Booking draft updated.', 'salon-booking-system')
            ),
        );


        return $messages;
    }

    protected function getPostTypeArgs()
    {
        return array(
            'description' => __('This is where bookings are stored.', 'salon-booking-system'),
            'public' => true,
            'show_ui' => true,
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_menu' => 'salon',
            'hierarchical' => false,
            'show_in_nav_menus' => true,
            'rewrite' => false,
            'query_var' => false,
            'supports' => array('title', 'comments', 'custom-fields'),
            'has_archive' => false,
            'rewrite' => false,
            'supports' => array(
                'revisions',
            ),
            'labels' => array(
                'name' => __('Bookings', 'salon-booking-system'),
                'singular_name' => __('Booking', 'salon-booking-system'),
                'menu_name' => __('Salon', 'salon-booking-system'),
                'name_admin_bar' => __('Salon Booking', 'salon-booking-system'),
                'all_items' => __('Bookings', 'salon-booking-system'),
                'add_new' => __('Add Booking', 'salon-booking-system'),
                'add_new_item' => __('Add New Booking', 'salon-booking-system'),
                'edit_item' => __('Edit Booking', 'salon-booking-system'),
                'new_item' => __('New Booking', 'salon-booking-system'),
                'view_item' => __('View Booking', 'salon-booking-system'),
                'search_items' => __('Search Bookings', 'salon-booking-system'),
                'not_found' => __('No bookings found', 'salon-booking-system'),
                'not_found_in_trash' => __('No bookings found in trash', 'salon-booking-system'),
                'archive_title' => __('Booking Archive', 'salon-booking-system'),
            ),
        );
    }

    private function registerPostStatus()
    {
        foreach (SLN_Enum_BookingStatus::toArray() as $k => $v) {
            register_post_status(
                $k,
                array(
                    'label' => $v,
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop(
                        $v.' <span class="count">(%s)</span>',
                        $v.' <span class="count">(%s)</span>'
                    ),
                )
            );
        }
        add_action('transition_post_status', array($this, 'transitionPostStatus'), 10, 3);
    }

    public function transitionPostStatus($new_status, $old_status, $post)
    {
        if ($post->post_type == SLN_Plugin::POST_TYPE_BOOKING) {
            $p = $this->getPlugin();
            $booking = $p->createBooking($post);
            if ($new_status == SLN_Enum_BookingStatus::CONFIRMED && $old_status != $new_status) {
                $p->sendMail('mail/status_confirmed', compact('booking'));
                $this->sendSmsBooking($booking);
            } elseif ($new_status == SLN_Enum_BookingStatus::CANCELED && $old_status != $new_status) {
                $p->sendMail('mail/status_canceled', compact('booking'));
            } elseif ($new_status == SLN_Enum_BookingStatus::PENDING_PAYMENT && $old_status != $new_status) {
                $p->sendMail('mail/status_pending_payment', compact('booking'));
            } elseif (
                in_array($new_status, array(SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::PAY_LATER))
                && $old_status != $new_status
            ) {
                $p->sendMail('mail/summary', compact('booking'));
                $p->sendMail('mail/summary_admin', compact('booking'));
                $this->sendSmsBooking($booking);
            }

            //$ret = $GLOBALS['sln_googlescope']->create_event_from_booking($booking);
        }
    }

    public function sendSmsBooking($booking)
    {
        $p = $this->getPlugin();
        $sms = $p->sms();
        if ($p->getSettings()->get('sms_new')) {
            $phone = $p->getSettings()->get('sms_new_number');
            if ($phone) {
                $sms->send($phone, $p->loadView('sms/summary', compact('booking')));
            }
            $phone = $booking->getPhone();
            if ($phone) {
                $sms->send($phone, $p->loadView('sms/summary', compact('booking')));
            }
        }
        if ($p->getSettings()->get('sms_new_attendant') && $booking->getAttendant()) {
            $phone = $booking->getAttendant()->getPhone();
            if ($phone) {
                $sms->send($phone, $p->loadView('sms/summary', compact('booking')));
            }
        }
    }

    public function bulkAdminFooterNew()
    {
        $this->bulkAdminFooter(true);
    }

    public function bulkAdminFooterEdit()
    {
        $this->bulkAdminFooter(false);
    }

    public function bulkAdminFooter($isNew = false)
    {
        global $post;
        if ($post->post_type == SLN_Plugin::POST_TYPE_BOOKING) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('#save-post').attr('value', '<?php echo __(
                        $isNew ? "Add booking" : 'Update booking',
                        'salon-booking-system'
                    ) ?>').addClass('sln-btn sln-btn--main');
                    $('#misc-publishing-actions > *').css('display', 'none');
                    $('#submitdiv h3 span').text('<?php echo __('Booking', 'salon-booking-system') ?>');
                    <?php
                    foreach (SLN_Enum_BookingStatus::toArray() as $k => $v) {
                    $complete = '';
                    $label = '';
                    if ($post->post_status == $k) {
                        $complete = ' selected=\"selected\"';
                        $label = '<span id=\"post-status-display\">'.$v.'</span>';
                    }
                    ?>
                    $("select#post_status").append("<option value=\"<?php echo $k ?>\" <?php echo $complete ?>><?php echo $v ?></option>");
                    $(".misc-pub-section label").append("<?php echo $label ?>");
                    <?php
                    }
                    ?>
                });
            </script>
            <?php
        }
    }

    public function bulkPostStates()
    {
        global $post;
        $arg = get_query_var('post_status');
        if ($post->post_type == SLN_Plugin::POST_TYPE_BOOKING) {
            foreach (SLN_Enum_BookingStatus::toArray() as $k => $v) {

                if ($arg != $k) {
                    if ($post->post_status == $k) {
                        return array($v);
                    }
                }
            }
        }

        return null;
    }

    function posttype_admin_css()
    {
        global $post_type;
        if ($post_type == SLN_Plugin::POST_TYPE_BOOKING) {
            ?>
            <style type="text/css">
                #post-preview, #view-post-btn, #misc-publishing-actions #visibility,
                #major-publishing-actions,
                #post-body-content {
                    display: none;
                }
            </style>
            <script type="text/javascript">
                jQuery(function () {
                    jQuery('#_sln_booking_status, #post_status').change(function () {
                        jQuery('#_sln_booking_status, #post_status').val(jQuery(this).val());
                    });
                });
            </script>
            <?php
        }
    }

}
