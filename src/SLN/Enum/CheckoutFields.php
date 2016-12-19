<?php

class SLN_Enum_CheckoutFields
{
    private static $fields;

    private static $requiredByDefault;

    private static $settingsLabels;

    public static function toArray()
    {
        return self::$fields;
    }

    public static function toArrayFull()
    {
        $ret = self::$fields;

        $ret['password']         = __('Password', 'salon-booking-system');
        $ret['password_confirm'] = __('Confirm your password', 'salon-booking-system');

        return $ret;
    }

    public static function getLabel($key)
    {
        return isset(self::$fields[$key]) ? self::$fields[$key] : '';
    }

    public static function getSettingLabel($key)
    {
        return isset(self::$settingsLabels[$key]) ? self::$settingsLabels[$key] : '';
    }

    public static function isHidden($key, $checkoutFields = null) {
        if (self::isRequiredByDefault($key)) {
            return false;
        }
        else {
            $checkoutFieldsSettings = (array)(!empty($checkoutFields) ? $checkoutFields : SLN_Plugin::getInstance()->getSettings()->get('checkout_fields'));

            if (isset($checkoutFieldsSettings[$key]['hide']) && $checkoutFieldsSettings[$key]['hide']) {
                return true;
            }
        }

        return false;
    }

    public static function isRequired($key, $checkoutFields = null) {
        if (self::isRequiredByDefault($key)) {
            return true;
        }
        else {
            $checkoutFieldsSettings = (array)(!empty($checkoutFields) ? $checkoutFields : SLN_Plugin::getInstance()->getSettings()->get('checkout_fields'));

            if (isset($checkoutFieldsSettings[$key]['require']) && $checkoutFieldsSettings[$key]['require']) {
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
        self::$fields = array(
            'firstname' => __('First name', 'salon-booking-system'),
            'lastname'  => __('Last name', 'salon-booking-system'),
            'email'     => __('e-mail', 'salon-booking-system'),
            'phone'     => __('Mobile phone', 'salon-booking-system'),
            'address'   => __('Address', 'salon-booking-system'),
        );

        self::$settingsLabels = array(
            'firstname' => __('First name (not editable)', 'salon-booking-system'),
            'lastname'  => __('Last name', 'salon-booking-system'),
            'email'     => __('Email address (not editable)', 'salon-booking-system'),
            'phone'     => __('Telephone', 'salon-booking-system'),
            'address'   => __('Address', 'salon-booking-system'),
        );

        self::$requiredByDefault = array(
            'firstname',
            'email',
        );
    }
}
SLN_Enum_CheckoutFields::init();
