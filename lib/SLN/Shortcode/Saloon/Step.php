<?php

abstract class SLN_Shortcode_Saloon_Step
{
    private $plugin;
    private $attrs;
    private $step;

    function __construct(SLN_Plugin $plugin, $attrs, $step)
    {
        $this->plugin = $plugin;
        $this->attrs  = $attrs;
        $this->step = $step;
    }

    abstract public function execute();

    protected function getStep(){
        return $this->step;
    }

    /** @return SLN_Plugin */
    protected function getPlugin(){
        return $this->plugin;
    }
}