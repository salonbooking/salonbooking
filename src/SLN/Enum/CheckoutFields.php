<?php

class SLN_Enum_CheckoutFields
{
    
    private static $field_type = array('text','textarea','checkbox','select');    
    private static $default_fields = array();
    private static $fields;
    private static $additional_fields = array();
    private static $requiredByDefault;
    public static $additional_fields_types = array();
    public static $fields_select_options = array();
    //public static $additional_hidden_fields_callback;

    public static function toArray($context = 'all')
    {   
        if($context == 'defaults') return self::$default_fields;
        if($context == 'additional') return self::$additional_fields;
        return self::$fields;
    }

    public static function toArrayFull()
    {
        $ret = self::$fields;

        $ret['password']         = __('Password', 'salon-booking-system');
        $ret['password_confirm'] = __('Confirm your password', 'salon-booking-system');

        return $ret;
    }

    public static function getRequiredFields(){

        $checkoutFieldsSettings = SLN_Plugin::getInstance()->getSettings()->get('checkout_fields');
        $checkoutFieldsSettings = !empty($checkoutFieldsSettings) ? $checkoutFieldsSettings :  false;
        
        if($checkoutFieldsSettings){
            $ret = array(); 
            foreach(array_keys(self::$fields) as $field  ) {
                if (self::isRequiredByDefault($field) || (isset($checkoutFieldsSettings[$field]['require']) && $checkoutFieldsSettings[$field]['require']))   $ret[$field] = $field;
            }
        }else{
            $ret = self::$requiredByDefault;
        }
        return $ret;
    }

    public static function getLabel($key)
    {
        return isset(self::$fields[$key]) ? self::$fields[$key] : '';
    }

    public static function getSettingLabel($key)
    {
        
        return isset(self::$fields[$key]) ? (in_array($key,self::$requiredByDefault) ? self::$fields[$key].' '.__('(not editable)', 'salon-booking-system') : self::$fields[$key]) : '';
    }

    public static function isHidden($key) {
        if (self::isRequiredByDefault($key)) {
            return false;
        }
        else {
            $checkoutFieldsSettings = SLN_Plugin::getInstance()->getSettings()->get('checkout_fields');
            $checkoutFieldsSettings = !empty($checkoutFieldsSettings) ? $checkoutFieldsSettings :  false;

            if ($checkoutFieldsSettings && isset($checkoutFieldsSettings[$key]) && isset($checkoutFieldsSettings[$key]['hide']) && $checkoutFieldsSettings[$key]['hide']) {
                return true;
            }
        }

        return false;
    }

    public static function isRequired($key) {
        if (self::isRequiredByDefault($key)) {
            return true;
        }
        else {
            $checkoutFieldsSettings = SLN_Plugin::getInstance()->getSettings()->get('checkout_fields');
            $checkoutFieldsSettings = !empty($checkoutFieldsSettings) ? $checkoutFieldsSettings :  false;

            if ( $checkoutFieldsSettings && isset($checkoutFieldsSettings[$key]) && isset($checkoutFieldsSettings[$key]['require']) && $checkoutFieldsSettings[$key]['require']) {
                return true;
            }
        }

        return false;
    }

    public static function isRequiredNotHidden($key, $checkoutFields = null) {
        return self::isRequired($key, $checkoutFields) && !self::isHidden($key, $checkoutFields);
    }

    public static function isHiddenOrNotRequired($key, $checkoutFields = null) {
        return !self::isRequiredNotHidden($key, $checkoutFields);
    }

    public static function isRequiredByDefault($key) {
        return in_array($key, self::$requiredByDefault);
    }

    public static function init()
    {
        self::$default_fields = array(
            'firstname' => __('First name', 'salon-booking-system'),
            'lastname'  => __('Last name', 'salon-booking-system'),
            'email'     => __('e-mail', 'salon-booking-system'),
            'phone'     => __('Mobile phone', 'salon-booking-system'),
            'address'   => __('Address', 'salon-booking-system'),
        );
        self::$fields = self::$default_fields;

        self::$requiredByDefault = array(
            'firstname',
            'email',
        );

        $additional_fields = apply_filters('sln.checkout.additional_fields',array());
        if(is_array($additional_fields) &&  $additional_fields){
            
            self::initAdditionalFields($additional_fields);    
        }
    }

    public static function initAdditionalFields($additional_fields){

        $default_field_settings = array(
            "label"     => '',
            "type"      => 'text'
        );
        $plugin = SLN_Plugin::getInstance();
        
        foreach ($additional_fields as $field => $opts ) {
            $field_settings = $default_field_settings;
            if(is_array($opts)){
                foreach ($field_settings as $key => $value) {
                    if(isset($key,$opts)){
                        if(in_array($key,array('label','type')) && !is_string($opts[$key])) continue;
                        if($key === 'type' && !in_array($opts[$key],self::$field_type)) continue;
                         $field_settings[$key] = $opts [$key];
                    }
                }
            }else{
                if(is_string($opts)) $field_settings['label'] = $opts;
            }
            
            if(!isset(self::$fields[$field])){
                self::$additional_fields[$field] = $field_settings['label'];
                self::$fields[$field] = $field_settings['label'];
                self::$additional_fields_types[$field] = $field_settings['type'];
                if($field_settings['type'] == 'select') self::$fields_select_options[$field] = isset($opts['options'])? $opts['options'] : array();   
                //if($field_settings['type'] == 'hidden') self::$additional_hidden_fields_callback[$field] = isset($opts['callback'])? $opts['callback'] : false;     
            } 
        }
        
    }

}
SLN_Enum_CheckoutFields::init();
