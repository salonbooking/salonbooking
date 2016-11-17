<?php

abstract class SLN_Repository_AbstractWrapperRepository extends SLN_Repository_AbstractRepository
{
    abstract public function getWrapperClass();

    protected $plugin;
    protected $postType;
    protected $cache = array();

    public function __construct(SLN_Plugin $plugin, SLN_PostType_Abstract $postType)
    {
        $this->plugin = $plugin;
        $this->postType = $postType;
    }


    public function create($data = null)
    {
        if (is_int($data)) {
            if (isset($this->cache[$data])) {
                return $this->cache[$data];
            }
            $data = get_post($data);
        }
        $class = $this->getWrapperClass();

        $ret = new $class($data);
        $this->cache[$ret->getId()] = $ret;
        return $ret;
    }

    public function getBindings()
    {
        return array($this->getWrapperClass(), $this->getPostType());
    }

    public function getPostType()
    {
        return $this->postType->getPostType();
    }

    public function get($criteria = array())
    {
        $ret = array();
        foreach ($this->getPosts($criteria) as $post) {
            $ret[] = $this->create($post);
        }

        return $ret;
    }


    protected function getPosts($criteria)
    {
        $args = $this->processCriteria($criteria);
        global $post_type;
        $tmp = $post_type;
        $post_type = $args['post_type'];
        $query = new WP_Query();
        $posts = $query->query($args);
        wp_reset_query();
        wp_reset_postdata();
        $post_type = $tmp;
        return $posts;
    }

    public function getOne($criteria)
    {
        $criteria['@limit'] = 1;
        $ret = $this->get($criteria);

        return isset($ret[0]) ? $ret[0] : null;
    }

    protected function processCriteria($criteria)
    {
        $ret = array('post_type' => $this->getPostType());

        if (isset($criteria['@limit'])) {
            $ret['posts_per_page'] = $criteria['@limit'];
        } else {
            $ret['nopaging'] = true;
        }
        if (isset($criteria['@wp_query'])) {
            $ret = array_merge($ret, $criteria['@wp_query']);
        }
        if (isset($criteria['post_status'])) {
            $ret['post_status'] = $criteria['post_status'];
        }
        if (isset($criteria['date_query'])) {
            $ret['date_query'] = $criteria['date_query'];
        }
        return $ret;
    }

    public static function getSecureId($id)
    {
        if (is_int($id)) {
            return $id;
        } elseif (isset($id->ID)) {
            return $id->ID;
        } elseif (isset($id)) {
            return $id->getId();
        } else {
            return $id;
        }
    }
}
