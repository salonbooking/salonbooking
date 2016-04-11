<?php

abstract class SLN_Repository_AbstractWrapperRepository extends SLN_Repository_AbstractRepository
{
    abstract public function getWrapperClass();

    protected $plugin;
    protected $postType;

    public function __construct(SLN_Plugin $plugin, SLN_PostType_Abstract $postType)
    {
        $this->plugin = $plugin;
        $this->postType = $postType;
    }


    public function create($data = null)
    {
        if (is_int($data)) {
            $data = get_post($data);
        }
        $class = $this->getWrapperClass();

        return new $class($data);
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
        $args = $this->processCriteria($criteria);
        $query = new WP_Query($args);
        $posts = $query->get_posts();
        wp_reset_query();
        wp_reset_postdata();

        $ret = [];
        foreach ($posts as $post) {
            $ret[] = $this->create($post);
        }

        return $ret;
    }

    public function getOne($criteria)
    {
        $criteria['@limit'] = 1;
        $ret = $this->get($criteria);

        return isset($ret[0]) ? $ret[0] : null;
    }

    protected function processCriteria($criteria)
    {
        $ret = ['post_type' => $this->getPostType()];

        if (isset($criteria['@limit'])) {
            $ret['posts_per_page'] = $criteria['@limit'];
        } else {
            $ret['nopaging'] = true;
        }
        if (isset($criteria['@query'])) {
            $ret = array_merge($ret, $criteria['@wp_query']);
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
