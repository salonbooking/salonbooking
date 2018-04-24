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
        return $this->render();
    }
   
    protected function render($data = array())
    {
        return $this->plugin->loadView('shortcode/salon_assistant', compact('data'));
    }

}
