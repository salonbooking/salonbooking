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
                $obj = new SLN_Shortcode_Saloon($plugin, $attrs);

                return $obj->execute();
            }
        );
    }


    public function execute()
    {
        return $this->dispatchStep($this->getCurrentStep());
    }

    private function dispatchStep($curr)
    {
        $found = false;
        foreach ($this->steps as $step) {
            if ($curr == $step || $found) {
                $found = true;
                $class = __CLASS__ . '_' . ucwords($step) . 'Step';
                $obj   = new $class($this->plugin, $this->attrs, $step);
                if ($obj instanceof SLN_Shortcode_Saloon_Step) {
                    if (!$obj->isValid()) {
                        return $this->render($obj->render());
                    }
                } else {
                    throw new Exception('bad object ' . $class);
                }
            }
        }
    }

    protected function render($content)
    {
        return '<div id="sln-saloon">' . $content . '</div>';
    }


    private function getCurrentStep()
    {
        return isset($_GET['sln_step_page']) ? $_GET['sln_step_page'] : 'date';
    }
}