<?php

class SLN_Shortcode_Saloon
{
    const STEP_KEY = 'sln_step_page';
    const STEP_DEFAULT = 'date';

    private $plugin;
    private $attrs;

    private $steps;


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
        foreach ($this->getSteps() as $step) {
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
        $saloon = $this;
        return $this->plugin->loadView('shortcode/saloon', compact('content', 'saloon'));
    }


    private function getCurrentStep()
    {
        return isset($_GET[self::STEP_KEY]) ? $_GET[self::STEP_KEY] : self::STEP_DEFAULT;
    }

    private function needSecondary()
    {
        foreach ($this->plugin->getServices() as $service) {
            if ($service->isSecondary()) {
                return true;
            }
        }
    }

    public function getSteps()
    {
        if (!isset($this->steps)) {
            $this->steps = array(
                'date',
                'services',
                'secondary',
                'details',
                'summary',
                'thankyou'
            );
            if (!$this->needSecondary()) {
                unset($this->steps[array_search('secondary', $this->steps)]);
            }
        }

        return $this->steps;
    }
}