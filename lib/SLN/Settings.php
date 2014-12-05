<?php

class SLN_Settings
{
    const KEY = 'saloon_settings';
    private $settings;

    public function __construct()
    {
        $this->settings = get_option(self::KEY);
    }

    public function get($k)
    {
        return isset($this->settings[$k]) ? $this->settings[$k] : null;
    }

    public function getCurrency()
    {
        return empty($this->settings['pay_currency']) ? 'USD' : $this->settings['pay_currency'];
    }

    public function getCurrencySymbol()
    {
        return SLN_Currency::getSymbol($this->getCurrency());
    }

    public function getInterval()
    {
        return isset($this->settings['interval']) ? $this->settings['interval'] : 15;
    }
}