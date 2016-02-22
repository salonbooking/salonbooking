<?php

class SLN_Settings
{
    const KEY = 'salon_settings';
    private $settings;

    public function __construct()
    {
        $this->settings = get_option(self::KEY);
    }
    
    public function get($k)
    {
        return isset($this->settings[$k]) ? $this->settings[$k] : null;
    }

    public function set($key, $val)
    {
        if(is_string($val)) $val = trim($val);
        if (empty($val)) {
            unset($this->settings[$key]);
        } else {
            $this->settings[$key] = $val;
        }
    }

    public function save()
    {
        update_option(self::KEY, $this->settings);

        return $this;
    }

    public function clear()
    {
        delete_option(self::KEY, $this->settings);
    }

    public function getVersion()
    {
        return SLN_VERSION;
    }

    public function getDbVersion()
    {
        return empty($this->settings['sln_db_version']) ? '0.0.0' : $this->settings['sln_db_version'];
    }

    public function setDbVersion($version = null)
    {
        $this->settings['sln_db_version'] = is_null($version) ? $this->getVersion() : $version;

        return $this;
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

    public function getNoticesDisabled()
    {
        return isset($this->settings['notices_disabled']) ? $this->settings['notices_disabled'] : false;
    }

    public function setNoticesDisabled($val)
    {
        $this->settings['notices_disabled'] = $val;

        return $this;
    }

    public function isPaypalTest()
    {
        return $this->settings['pay_paypal_test'] ? true : false;
    }

    public function getPaypalEmail()
    {
        return $this->settings['pay_paypal_email'];
    }

    public function getThankyouPageId()
    {
        return $this->settings['thankyou'];
    }

    public function isDisabled()
    {
        return $this->get('disabled') ? true : false;
    }

    public function getDisabledMessage()
    {
        return nl2br(htmlentities($this->get('disabled_message')));
    }

    public function isAjaxEnabled(){
        return $this->get('ajax_enabled') ? true: false;
    }

    public function getDateFormat(){
        return $this->get('date_format');
    }

    public function getTimeFormat(){
        return $this->get('time_format');
    }
    public function getSalonName(){
        $ret = $this->get('gen_name');
        if(!$ret)
            $ret = get_bloginfo('name');
        return $ret;
    }
    public function getSalonEmail(){
        $ret = $this->get('gen_email');
        if(!$ret)
            $ret = get_bloginfo('admin_email');
        return $ret;
 
    }

    public function getHoursBeforeFrom(){
        $ret = $this->get('hours_before_from');
        return $ret ? $ret : '+30 minutes';
    }

    public function getHoursBeforeTo(){
        $ret = $this->get('hours_before_to');
        return $ret ? $ret : '+1 month';
    }
    public function getAvailabilityMode(){
        $ret = $this->get('availability_mode');
        return $ret ? $ret : 'basic';
    }
    
    public function getPaymentMethod()
    {
        return isset($this->settings['pay_method']) ? $this->settings['pay_method'] : 'paypal';
    }

    public function isHidePrices(){
        return $this->get('hide_prices') == 1 ? true : false;
    }
}
