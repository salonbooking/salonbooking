<?php

abstract class SLN_PostType_Abstract
{
    public function __construct()
    {
        add_action('init', array($this, 'init'));
        add_filter('post_updated_messages', array($this, 'post_updated_messages'));
        add_filter('enter_title_here', 'enter_title_here', 10, 2);
    }

    abstract public function init();

    abstract public function enter_title_here($title, $post);

    abstract public function updated_messages($messages);
}