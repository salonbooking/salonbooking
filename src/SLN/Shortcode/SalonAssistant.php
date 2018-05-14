<?php // algolplus

class SLN_Shortcode_SalonAssistant
{
    const NAME = 'salon_booking_assistant';

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
        $attendants = false;
        $display = false;
        if(!empty($this->attrs['attendants'])){
            $attendants = explode(',',$this->attrs['attendants']);
        }
        if(!empty($this->attrs['display'])){
            $display = explode(',',$this->attrs['display']);
        }
        $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
        
        $criteria = $attendants ? array(
            '@wp_query' => array('post__in' => $attendants)
        ) : array();
        
        $attendants = $repo->get($criteria);
        $data = array('attendants' => $attendants);
        $data['styled'] = !empty($this->attrs['styled']) && $this->attrs['styled']=== 'true';
        if(!empty($this->attrs['columns']) && intval($this->attrs['columns'])) $data['columns'] =  intval($this->attrs['columns']);
        $data['display'] = $display;
        $data['booking_url'] = get_the_permalink($this->plugin->getSettings()->getPayPageId());
        return $this->render($data);
    }
   
    protected function render($data = array())
    {
        return $this->plugin->loadView('shortcode/salon_assistant', compact('data'));
    }

}
