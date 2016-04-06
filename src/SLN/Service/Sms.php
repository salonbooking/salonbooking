<?php

class SLN_Service_Sms
{
    private $plugin;
    private $exception;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function send($number, $message)
    {
        try {
            $provider = SLN_Enum_SmsProvider::getService(
                $this->plugin->getSettings()->get('sms_provider'),
                $this->plugin
            );
            $provider->send($number, $message);
        } catch (SLN_Action_Sms_Exception $e) {
            $this->exception = $e;
        }
    }

    public function hasError()
    {
        return isset($this->exception);
    }

    public function getError()
    {
        return $this->getException()->getMessage();
    }

    /**
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}