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
        add_action('save_post', array($this, 'may_save_post'), 10, 2);
        add_filter('wp_insert_post_data', array($this, 'wp_insert_post_data'), 99, 2);

        add_action('admin_print_styles-post.php', array($this, 'admin_print_styles'));
        add_action('admin_print_styles-post-new.php', array($this, 'admin_print_styles'));


    }

    public function admin_print_styles()
    {
        global $post;
        if(empty($post)) return;
        if ($post->post_type == $this->getPostType()) {
            $this->enqueueAssets();
            add_filter( 'wpseo_use_page_analysis', '__return_false' );
            remove_meta_box('wpseo_meta', $this->getPostType(), 'normal');
        }
    }

    protected function enqueueAssets(){

        SLN_Action_InitScripts::enqueueTwitterBootstrap(true);
        SLN_Action_InitScripts::enqueueSelect2();
        SLN_Action_InitScripts::enqueueAdmin();
        SLN_Action_InitScripts::enqueueCustomSliderRange();
    }

    abstract public function add_meta_boxes();

    abstract protected function getFieldList();

    public function may_save_post($post_id, $post)
    {
        $pt = $this->getPostType();

        if (is_admin() && $pt == $post->post_type) {
            return $this->save_post($post_id, $post);
        }
    }

    public function save_post($post_id, $post)
    {
        $pt = $this->getPostType();
        $h  = new SLN_Metabox_Helper;
        if ( ! $h->isValidRequest($pt, $post_id, $post)) {
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
