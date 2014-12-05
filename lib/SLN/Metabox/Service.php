<?php

class SLN_Metabox_Service extends SLN_Metabox_Abstract
{
    public function add_meta_boxes()
    {
        add_meta_box(
            'sln-service-details',
            __('Service Details', 'sln'),
            array($this, 'details_meta_box'),
            'sln_service',
            'normal',
            'high'
        );
    }

    public function details_meta_box($object, $box)
    {
        $service  = $this->getPlugin()->createService($object);
        $settings = $this->getPlugin()->getSettings();
        $this->showCSS()
        ?>
        <input type="hidden" name="sln_service_details_meta_nonce"
               value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>"/>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-service-price"><?php _e('Price', 'sln'); ?>
                (<?php echo $settings->getCurrencySymbol() ?>)</label>
            <?php SLN_Form::fieldText("sln-service-price", $service->getPrice()); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-service-unit"><?php _e('Unit per hour', 'sln'); ?></label>
            <?php SLN_Form::fieldNumeric("sln-service-unit", $service->getUnit()); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-service-unit"><?php _e('Duration', 'sln'); ?></label>
            <?php SLN_Form::fieldTime("sln-service-duration", $service->getDuration()); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label for="sln-service-secondary"><?php _e('Secondary', 'sln'); ?></label>
            <?php SLN_Form::fieldCheckbox('sln-service-secondary', $service->isSecondary()) ?>
            <br/><em>Select this if you want this service considered as secondary level service</em>
        </div>
        <div class="sln-clear"></div>
        <h3>Not Available At</h3>
        <?php
        $days = SLN_Func::getDays();
        ?>
        <div class="sln_meta_field  sln-col-x2">
            <?php foreach ($days as $k => $day) { ?>
                <label>
                    <input type="checkbox" name="sln-service-notav-<?php echo $k ?>"
                           value="1" <?php echo $service->getNotAvailableOn($k) ? 'checked="checked"' : '' ?>/>
                    <?php echo substr($day, 0, 3) ?>
                </label>
            <?php } ?></div>
        <div class="sln_meta_field  sln-col-x2">
            <label>
                <?php echo __('From', 'sln') ?>
                <?php SLN_Form::fieldTime("sln-service-notav-from", $service->getNotAvailableFrom()) ?>
            </label>
            <label>
                <?php echo __('To', 'sln') ?>
                <?php SLN_Form::fieldTime("sln-service-notav-from", $service->getNotAvailableTo()) ?>
            </label>
        </div>
        <em>Leave blank if you want this service available everydays at every hour</em>
        <div class="sln-clear"></div>
        <?php do_action('sln_service_details_meta_box', $object, $box);
    }

    public function save_post($post_id, $post)
    {
        /* Verify the nonce. */
        if (!isset($_POST['sln_service_details_meta_nonce']) || !wp_verify_nonce(
                $_POST['sln_service_details_meta_nonce'],
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
            '_sln_service_price'      => SLN_Func::filter($_POST['sln-service-price'], 'float'),
            '_sln_service_unit'       => SLN_Func::filter($_POST['sln-service-unit'], 'int'),
            '_sln_service_notav_from' => SLN_Func::filter($_POST['sln-service-notav-from'], 'time'),
            '_sln_service_notav_to'   => SLN_Func::filter($_POST['sln-service-notav-to'], 'time'),
            '_sln_service_secondary'  => SLN_Func::filter($_POST['sln-service-secondary'], 'bool')
        );
        for ($i = 0; $i < 7; $i++) {
            $meta['_sln_service_notav_' . $i] = SLN_Func::filter($_POST['sln-service-notav-' . $i], 'bool');
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
