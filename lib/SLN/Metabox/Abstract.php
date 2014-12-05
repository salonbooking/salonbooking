<?php

abstract class SLN_Metabox_Abstract
{
    private $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_post'), 10, 2);
    }

    abstract public function add_meta_boxes();

    abstract public function save_post($post_id, $post);

    /**
     * @return SLN_Plugin
     */
    protected function getPlugin()
    {
        return $this->plugin;
    }

    protected function showCSS()
    {
        ?>
        <style type="text/css">
            .sln_meta_field {
                display: block;
            }

            .sln-col-x4 {
                float: left;
                width: 25%;
            }

            .sln-col-x2 {
                float: left;
                width: 50%;
            }

            .sln-col-x4 input[type="text"],
            .sln-col-x4 select {
                width: 100px;
            }

            .sln-date select{
                width: auto
            }

            .sln-clear {
                display: block;
                clear: both;
                height: 1px;
                width: 100%;
            }
        </style>
    <?php
    }
}
