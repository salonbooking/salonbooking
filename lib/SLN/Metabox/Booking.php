<?php

class SLN_Metabox_Booking extends SLN_Metabox_Abstract
{
    public function add_meta_boxes()
    {
        add_meta_box(
            'sln-booking-details',
            __('Booking Details', 'sln'),
            array($this, 'details_meta_box'),
            'sln_booking',
            'normal',
            'high'
        );
    }

    public function details_meta_box($object, $box)
    {
        $booking  = $this->getPlugin()->createBooking($object);
        $settings = $this->getPlugin()->getSettings();
        $this->showCSS();
        ?>
        <input type="hidden" name="sln_booking_details_meta_nonce"
               value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>"/>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-booking-name"><?php _e('First Name', 'sln'); ?>
                <?php SLN_Form::fieldText("sln-booking-firstname", $booking->getFirstname()); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-booking-name"><?php _e('Last Name', 'sln'); ?>
                <?php SLN_Form::fieldText("sln-booking-lastname", $booking->getLastname()); ?>
        </div>

        <div class="sln_meta_field sln-col-x4">
            <label for="sln-booking-email"><?php _e('E-mail', 'sln'); ?>
                <?php SLN_Form::fieldText("sln-booking-email", $booking->getEmail()); ?>
        </div>

        <div class="sln_meta_field sln-col-x4">
            <label for="sln-booking-name"><?php _e('Phone', 'sln'); ?>
                <?php SLN_Form::fieldText("sln-booking-phone", $booking->getPhone()); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-booking-amount"><?php _e('Amount', 'sln'); ?>
                (<?php echo $settings->getCurrencySymbol() ?>)</label>
            <?php SLN_Form::fieldText("sln-booking-amount", $booking->getAmount()); ?>
        </div>
        <div class="sln_meta_field sln-col-x2">
            <label for="sln-booking-date"><?php _e('Date', 'sln'); ?>
                <?php SLN_Form::fieldDate("sln-booking-date", $booking->getDate()); ?>
                <?php SLN_Form::fieldTime("sln-booking-time", $booking->getTime()); ?>
                </label>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-booking-unit"><?php _e('Duration', 'sln'); ?></label>
            <?php SLN_Form::fieldTime(
                "sln-booking-duration",
                $booking->getDuration(),
                array('interval' => 10, 'maxItems' => 61)
            ); ?>
        </div>
        <div class="sln-clear"></div>
        <?php do_action('sln_booking_details_meta_box', $object, $box);
    }

    public function save_post($post_id, $post)
    {
        /* Verify the nonce. */
        if (!isset($_POST['sln_booking_details_meta_nonce']) || !wp_verify_nonce(
                $_POST['sln_booking_details_meta_nonce'],
                plugin_basename(__FILE__)
            )
        ) {
            return;
        }

        /* Get the post type object. */
        $post_type = get_post_type_object($post->post_type);

        /* Check if the current user has permission to edit the post. */
        if (!current_user_can($post_type->cap->edit_post, $post_id)) {
            return $post_id;
        }

        /* Don't save if the post is only a revision. */
        if ('revision' == $post->post_type) {
            return;
        }

        $meta = array(
            '_sln_booking_price'      => SLN_Func::filter($_POST['sln-booking-price'], 'float'),
            '_sln_booking_unit'       => SLN_Func::filter($_POST['sln-booking-unit'], 'int'),
            '_sln_booking_notav_from' => SLN_Func::filter($_POST['sln-booking-notav-from'], 'time'),
            '_sln_booking_notav_to'   => SLN_Func::filter($_POST['sln-booking-notav-to'], 'time'),
            '_sln_booking_secondary'  => SLN_Func::filter($_POST['sln-booking-secondary'], 'bool')
        );
        for ($i = 0; $i < 7; $i++) {
            $meta['_sln_booking_notav_' . $i] = SLN_Func::filter($_POST['sln-booking-notav-' . $i], 'bool');
        }
        foreach ($meta as $meta_key => $new_meta_value) {

            /* Get the meta value of the custom field key. */
            $meta_value = get_post_meta($post_id, $meta_key, true);

            /* If a new meta value was added and there was no previous value, add it. */
            if ($new_meta_value && '' == $meta_value) {
                add_post_meta($post_id, $meta_key, $new_meta_value, true);
            } /* If the new meta value does not match the old value, update it. */
            elseif ($new_meta_value && $new_meta_value != $meta_value) {
                update_post_meta($post_id, $meta_key, $new_meta_value);
            } /* If there is no new meta value but an old value exists, delete it. */
            elseif ('' == $new_meta_value && $meta_value) {
                delete_post_meta($post_id, $meta_key, $meta_value);
            }
        }
    }
}
