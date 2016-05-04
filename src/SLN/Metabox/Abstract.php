<?php

abstract class SLN_Metabox_Abstract
{
    private $plugin;
    private $postType;

    public function __construct(SLN_Plugin $plugin, $postType)
    {
        $this->plugin   = $plugin;
        $this->postType = $postType;
        $this->init();
    }

    protected function init()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_post'), 10, 2);
        add_filter('wp_insert_post_data', array($this, 'wp_insert_post_data'), 99, 2);
    }

    abstract public function add_meta_boxes();

    abstract protected function getFieldList();

    public function save_post($post_id, $post)
    {
        $pt = $this->getPostType();
        $h  = new SLN_Metabox_Helper;
        if (!$h->isValidRequest($pt, $post_id, $post)) {
            return;
        }
        $h->updateMetas($post_id, $h->processRequest($pt, $this->getFieldList()));
    }

    public function wp_insert_post_data($data, $postarr)
    {
        return $data;
    }

    /**  @return SLN_Plugin */
    protected function getPlugin()
    {
        return $this->plugin;
    }

    /** @return string */
    protected function getPostType()
    {
        return $this->postType;
    }
}
