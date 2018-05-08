<?php // algolplus

class SLN_Shortcode_SalonServices
{
    const NAME = 'salon_booking_services';

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
        SLN_TimeFunc::startRealTimezone();

        $obj = new self(SLN_Plugin::getInstance(), $attrs);

        $ret = $obj->execute();
        SLN_TimeFunc::endRealTimezone();
        return $ret;
    }

    public function execute()
    {
        $services = false;
        $categories = false;
        $display = false;
        if(!empty($this->attrs['services'])){
            $services = explode(',',$this->attrs['services']);
        }
        if(!empty($this->attrs['categories'])){
            $categories = explode(',',$this->attrs['categories']);
        }
        if(!empty($this->attrs['display'])){
            $display = explode(',',$this->attrs['display']);
        }
        $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
        
        $criteria = $services ? array(
            '@wp_query' => array(
                'post__in' => $services,                
            )
        ) : array('@wp_query' => array());
        if($categories) $criteria['@wp_query']['tax_query'] = array(
            array(
                'taxonomy' => SLN_Plugin::TAXONOMY_SERVICE_CATEGORY,
                'field' => 'slug',
                'terms' => $categories
            )
        );
        
        $services = $repo->get($criteria);
        $data = array('services' => $services);
        $data['styled'] = !empty($this->attrs['styled']) && $this->attrs['styled']=== 'true';
        if(!empty($this->attrs['columns']) && intval($this->attrs['columns'])) $data['columns'] =  intval($this->attrs['columns']);
        $data['display'] = $display;

        return $this->render($data);
    }
   
    protected function render($data = array())
    {
        return $this->plugin->loadView('shortcode/salon_booking_services', compact('data'));
    }

}
