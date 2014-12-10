<?php

class SLN_Shortcode_Saloon
{
    private $plugin;
    private $attrs;

    private $steps = array(
        'date',
        'services',
        'secondary',
        'details',
        'summary',
        'thankyou'
    );


    function __construct(SLN_Plugin $plugin, $attrs)
    {
        $this->plugin = $plugin;
        $this->attrs  = $attrs;
    }

    public static function init(SLN_Plugin $plugin)
    {
        add_shortcode(
            'saloon',
            function ($attrs) use ($plugin) {
                $obj = new self($plugin, $attrs);
                return $obj->execute();
            }
        );
    }


    public function execute()
    {
        ob_start();
        $this->dispatchStep($this->getCurrentStep());
        $ret = ob_get_clean();

        return $ret;
    }

    private function dispatchStep($curr)
    {
        $found = false;
        foreach ($this->steps as $step) {
            if ($curr == $step || $found) {
                $found = true;
                $class = __CLASS__ . '_' . ucwords($step).'Step';
                $obj = new $class($this->plugin, $this->attrs,$step);
                if(!$obj->execute()){
                    return;
                }
            }
        }
    }

    private function getCurrentStep()
    {
        return isset($_GET['sln_step_page']) ? $_GET['sln_step_page'] : 'date';
    }
}