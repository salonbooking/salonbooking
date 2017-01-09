<?php

abstract class SLN_Wrapper_Abstract
{
    protected $object;

    abstract public function getPostType();

    function __construct($object)
    {
        if (!is_object($object)) {
            $object = get_post($object);
        }
        $this->object = $object;
    }
    public function reload(){
        $this->object = get_post($this->getId());
    }

    function getId()
    {
        if ($this->object) {
            return $this->object->ID;
        }
    }

    public function isEmpty()
    {
        return empty($this->object);
    }

    public function getMeta($key)
    {
        $pt = $this->getPostType();

        return apply_filters("$pt.$key.get", get_post_meta($this->getId(), "_{$pt}_$key", true));
    }

    public function setMeta($key, $value)
    {
        $pt = $this->getPostType();
        update_post_meta($this->getId(), "_{$pt}_$key", apply_filters("$pt.$key.set", $value));
    }

    public function getStatus()
    {
        return $this->object->post_status;
    }

    public function hasStatus($status)
    {
        return SLN_Func::has($this->getStatus(), $status);
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $post = array();
        $post['ID'] = $this->getId();
        $post['post_status'] = $status;
        wp_update_post($post);
        $this->object->post_status = $status;

        return $this;
    }

    public function getTitle()
    {
        if ($this->object) {
            return $this->object->post_title;
        }
    }

    public function getPostDate()
    {
        if ($this->object) {
            return $this->object->post_date;
        }
    }

    public function getExcerpt()
    {
        if ($this->object) {
            return $this->object->post_excerpt;
        }
    }
}
