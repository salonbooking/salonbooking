<?php

abstract class SLN_Action_Sms_Abstract
{
    protected $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    abstract public function send($to, $message);

    protected function getAccount()
    {
        return $this->plugin->getSettings()->get('sms_account');
    }

    protected function getPassword()
    {
        return $this->plugin->getSettings()->get('sms_password');
    }

    protected function getFrom()
    {
        return $this->plugin->getSettings()->get('sms_from');
    }

    protected function processTo($to)
    {
        return $this->plugin->format()->phone($to);
    }

    protected function createException($message, $code = 1000, $previous = null)
    {
        throw new SLN_Action_Sms_Exception($message, $code, $previous);
    }
}
