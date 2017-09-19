<?php

class SLN_Metabox_Service extends SLN_Metabox_Abstract
{
    protected $fields = array(
        'availabilities' => '',
        'price'          => 'float',
        'duration'       => 'time',
        'secondary'      => 'bool',
        'exclusive'      => 'bool',
        'secondary_display_mode'    => '',
        'secondary_parent_services' => '',
        'attendants'     => 'bool',
        'unit'           => 'int',
        'break_duration' => 'time',
        'exec_order'     => 'int',
    );

    protected function init()
    {
        parent::init();
        add_action('admin_print_styles-edit.php', array($this, 'admin_print_styles'));
        add_action('admin_enqueue_scripts', array($this, 'admin_print_styles_tag'));
    }

    public function admin_print_styles_tag()
    {
        global $taxnow, $pagenow;
        if($pagenow == 'edit-tags.php' && $taxnow == SLN_Plugin::TAXONOMY_SERVICE_CATEGORY) {
            $this->enqueueAssets();
        }
    }

    public function add_meta_boxes()
    {
        $postType = $this->getPostType();
        add_meta_box(
            $postType.'-details',
            __('Service details', 'salon-booking-system'),
            array($this, 'details_meta_box'),
            $postType,
            'normal',
            'high'
        );
        remove_meta_box('postexcerpt', $postType, 'side');
        add_meta_box(
            'postexcerpt',
            __('Service description', 'salon-booking-system'),
            array($this, 'post_excerpt_meta_box'),
            $postType,
            'normal',
            'high'
        );
    }

    public function post_excerpt_meta_box($post)
    {
        ?>
        <label class="screen-reader-text" for="excerpt">
            <?php _e('Service Description', 'salon-booking-system') ?>
        </label>
        <textarea rows="1" cols="40" name="excerpt"
                  id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped
            ?></textarea>
        <p><?php _e('A very short description of this service. It is optional', 'salon-booking-system'); ?></p>
        <?php
    }


    public function details_meta_box($object, $box)
    {
        echo $this->getPlugin()->loadView(
            'metabox/service',
            array(
                'metabox'  => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'service'  => $this->getPlugin()->createService($object),
                'postType' => $this->getPostType(),
                'helper'   => new SLN_Metabox_Helper(),
            )
        );
        do_action($this->getPostType().'_details_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        return apply_filters('sln.metabox.service.getFieldList', $this->fields);
    }

    protected function enqueueAssets()
    {
        parent::enqueueAssets();
        SLN_Action_InitScripts::enqueueCustomSliderRange();
        SLN_Action_InitScripts::enqueueCustomMetaService();
    }


    public function save_post($post_id, $post)
    {
        $k = '_sln_service_availabilities';
        if(isset($_POST[$k]))
            $_POST[$k] = SLN_Helper_AvailabilityItems::processSubmission($_POST[$k]);
        parent::save_post($post_id, $post);
    }
}
