<?php

abstract class SLN_PaymentMethod_Abstract
{
    protected $plugin;
    protected $methodKey;
    protected $methodLabel;
    private $error;
    public function getError(){
        return $this->error;
    }
    protected function setError($error){
        $this->error = $error;
    }

    abstract function getFields();

    public function __construct(SLN_Plugin $plugin, $methodKey, $methodLabel)
    {
        $this->plugin = $plugin;
        $this->methodKey = $methodKey;
        $this->methodLabel = $methodLabel;
    }

    public function getMethodKey()
    {
        return $this->methodKey;
    }

    public function getMethodLabel()
    {
        return $this->methodLabel;
    }

    public function renderPayButton($data){
        return $this->plugin->loadView('payment_method/'.$this->getMethodKey().'/pay', $data); 
    }

    public function renderSettingsFields($data){
        return $this->plugin->loadView('payment_method/'.$this->getMethodKey().'/settings', $data); 
    }

}
