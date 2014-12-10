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
        $this->step   = $step;
    }

    public function isValid()
    {
        return ($_POST['submit_' . $this->getStep()] && $this->dispatchForm());
    }

    public function render()
    {
        return $this->getPlugin()->loadView('shortcode/saloon_' . $this->getStep(), $this->getViewData());
    }

    protected function getViewData()
    {
        $step = $this->getStep();

        return array(
            'formAction' => add_query_arg(array('sln_step_page' => $step)),
            'submitName' => 'submit_' . $step
        );
    }

    protected function getStep()
    {
        return $this->step;
    }

    /** @return SLN_Plugin */
    protected function getPlugin()
    {
        return $this->plugin;
    }

    abstract protected function dispatchForm();
}