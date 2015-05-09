<?php

abstract class SLN_Action_Sms_Abstract
{
    protected $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    abstract public function send($to, $message);
}