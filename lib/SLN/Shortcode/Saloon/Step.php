<?php

abstract class SLN_Shortcode_Saloon_Step
{
    private $plugin;
    private $attrs;
    private $step;
    private $shortcode;

    function __construct(SLN_Plugin $plugin, SLN_Shortcode_Saloon $shortcode, $step)
    {
        $this->plugin    = $plugin;
        $this->shortcode = $shortcode;
        $this->step      = $step;
    }

    public function isValid()
    {
        return ($_POST['submit_' . $this->getStep()] || $_GET['submit_' . $this->getStep()]) && $this->dispatchForm();
    }

    public function render()
    {
        return $this->getPlugin()->loadView('shortcode/saloon_' . $this->getStep(), $this->getViewData());
    }

    protected function getViewData()
    {
        $step = $this->getStep();

        return array(
            'formAction' => add_query_arg(array('sln_step_page' => $this->shortcode->getCurrentStep())),
            'backUrl'    => add_query_arg(array('sln_step_page' => $this->shortcode->getPrevStep())),
            'submitName' => 'submit_' . $step,
            'step'       => $this,
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

    public function getShortcode()
    {
        return $this->shortcode;
    }

    abstract protected function dispatchForm();


}