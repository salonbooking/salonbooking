<?php // algolplus

class SLN_Shortcode_SalonMyAccount
{
    const NAME = 'salon_booking_my_account';

    private $plugin;
    private $attrs;

    function __construct(SLN_Plugin $plugin, $attrs)
    {
        $this->plugin = $plugin;
        $this->attrs = $attrs;
    }

    public static function init(SLN_Plugin $plugin)
    {
        add_shortcode(self::NAME, array(__CLASS__, 'create'));
    }

    public static function create($attrs)
    {
        if($timezone = get_option('timezone_string'))
            date_default_timezone_set($timezone);


        $obj = new self(SLN_Plugin::getInstance(), $attrs);

        $ret = $obj->execute();
        if($timezone = get_option('timezone_string'))
            date_default_timezone_set('UTC');
        return $ret;
    }

    public function execute()
    {
        if (!is_user_logged_in()) {
            return wp_login_form();
        }

        $args=array(
            'name'           => 'booking',
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
        );
        $query = new WP_Query($args);
        $post = $query->get_queried_object();
        wp_reset_query();

        return $this->render(array(
            'user_name'   => wp_get_current_user()->user_firstname,
            'booking_url' => get_post_permalink($post->ID),
        ));
    }

    protected function render($data)
    {
        return $this->plugin->loadView('shortcode/salon_my_account', compact('data'));
    }

}
