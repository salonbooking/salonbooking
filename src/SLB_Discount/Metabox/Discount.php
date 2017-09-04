<?php

class SLB_Discount_Metabox_Discount extends SLN_Metabox_Abstract
{
    protected $fields = array(
        'amount'             => 'float',
        'amount_type'        => '',
        'usages_limit_total' => 'int',
        'usages_limit'       => 'int',
        'from'               => '',
        'to'                 => '',
        'services'           => '',
        'type'               => '',
        'code'               => '',
        'rules'              => '',
    );

    protected function init()
    {
        parent::init();
        add_action('admin_print_styles-edit.php', array($this, 'admin_print_styles'));
    }

    public function add_meta_boxes()
    {
        $postType = $this->getPostType();
        add_meta_box(
            'postexcerpt',
            __('Discount details', 'salon-booking-system'),
            array($this, 'details_meta_box'),
            $postType,
            'normal',
            'high'
        );

        remove_meta_box('postexcerpt', $postType, 'side');
        add_meta_box(
            'sln_service-details',
            __('&nbsp', 'salon-booking-system'),
            array($this, 'advanced_meta_box'),
            $postType,
            'normal'
        );

        remove_meta_box('postimagediv', $postType, 'side');

        if (isset($_GET['post'])) {
            add_meta_box(
                'sln_attendant-details',
                __('Discount usage history', 'salon-booking-system'),
                array($this, 'history_meta_box'),
                $postType,
                'normal'
            );
        }
    }

    public function details_meta_box($object, $box)
    {
        echo $this->getPlugin()->loadView(
            'metabox/_discount_details',
            array(
                'metabox'  => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'discount'   => SLB_Discount_Plugin::getInstance()->createDiscount($object),
                'postType' => $this->getPostType(),
                'helper'   => new SLN_Metabox_Helper(),
            )
        );
        do_action($this->getPostType().'_details_meta_box', $object, $box);
    }

    public function advanced_meta_box($object, $box)
    {
        echo $this->getPlugin()->loadView(
            'metabox/_discount_advanced',
            array(
                'metabox'  => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'discount'   => SLB_Discount_Plugin::getInstance()->createDiscount($object),
                'postType' => $this->getPostType(),
                'helper'   => new SLN_Metabox_Helper(),
            )
        );
        do_action($this->getPostType().'_advanced_meta_box', $object, $box);
    }

    public function history_meta_box($object, $box)
    {
        echo $this->getPlugin()->loadView(
            'metabox/_discount_history',
            array(
                'metabox'  => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'discount'   => SLB_Discount_Plugin::getInstance()->createDiscount($object),
                'postType' => $this->getPostType(),
                'helper'   => new SLN_Metabox_Helper(),
            )
        );
        do_action($this->getPostType().'_history_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        return apply_filters('sln.metabox.discount.getFieldList', $this->fields);
    }

    public function save_post($post_id, $post)
    {
        $pt = $this->getPostType();
        $h  = new SLN_Metabox_Helper;
        if ( ! $h->isValidRequest($pt, $post_id, $post)) {
            return;
        }

        if (!isset($_POST[$h::getFieldName($pt, 'services')])) {
            $_POST[$h::getFieldName($pt, 'services')] = array();
        }

        if (!empty($_POST[$h::getFieldName($pt, 'from')])) {
            $_POST[$h::getFieldName($pt, 'from')] = SLN_TimeFunc::evalPickedDate($_POST[$h::getFieldName($pt, 'from')]);
        }
        if (!empty($_POST[$h::getFieldName($pt, 'to')])) {
            $_POST[$h::getFieldName($pt, 'to')] = SLN_TimeFunc::evalPickedDate($_POST[$h::getFieldName($pt, 'to')]);
        }

        $k = $h::getFieldName($pt, 'rules');
        if(isset($_POST[$k])) {
            $_POST[$k] = SLB_Discount_Helper_DiscountItems::processSubmission($_POST[$k]);
        }

        $meta = $h->processRequest($pt, $this->getFieldList());
        foreach ($meta as $meta_key => $new_meta_value) {
            update_post_meta($post_id, $meta_key, $new_meta_value);
        }
    }
}