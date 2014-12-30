<?php

abstract class SLN_Shortcode_Saloon_Step
{
    private $plugin;
    private $attrs;
    private $step;
    private $shortcode;
    private $errors = array();

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
            'errors'     => $this->errors
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

    protected function addError($err)
    {
        $this->errors[] = $err;
    }
    protected function getErrors(){
        return $this->errors;
    }

    public function doAjax($method)
    {
        $method = 'doAjax' . ucwords($method);
        if (method_exists($this, $method)) {
            $ret = $this->$method();
            if (is_array($ret)) {
                header('Content-Type: application/json');
                echo json_encode($ret);
            } elseif (is_string($ret)) {
                echo $ret;
            }
        } else {
            throw new Exception("ajax method not found '$method'");
        }
    }
}