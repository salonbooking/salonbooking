<?php

class SLN_Formatter
{
    private $plugin;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function money($val, $showFree = true)
    {
        $s = $this->plugin->getSettings();
        $isLeft = $s->get('pay_currency_pos') == 'left';
        $rightSymbol = $isLeft ? '' : $s->getCurrencySymbol();
        $leftSymbol = $isLeft ? $s->getCurrencySymbol() : '';
        
        if (!$showFree) {
            return ($leftSymbol. number_format($val, 2) . $rightSymbol);
        }
        return $val > 0 ? ($leftSymbol . number_format($val, 2) . $rightSymbol) : __('free','salon-booking-system');
    }

    public function datetime($val)
    {
        return self::date($val).' '.self::time($val);
    }

    public function date($val)
    {
        if ($val instanceof DateTime) {
            $val = $val->format('Y-m-d H:i');
        }

        $f = $this->plugin->getSettings()->getDateFormat();
        $phpFormat = SLN_Enum_DateFormat::getPhpFormat($f);
        return ucwords(date_i18n($phpFormat, strtotime($val)));
    }

    public function time($val)
    {
        $f = $this->plugin->getSettings()->getTimeFormat();
        $phpFormat = SLN_Enum_TimeFormat::getPhpFormat($f);
        if ($val instanceof DateTime) {
            $val = $val->format('Y-m-d H:i');
        }

        return date_i18n($phpFormat, strtotime($val));
    }

    public function phone($val){
        $s = $this->plugin->getSettings();
        $prefix = $s->get('sms_prefix');
        if($s->get('sms_trunk_prefix') && strpos($val,'0') === 0){
            $val = substr($val,1);
        }
        $val = str_replace(' ','',$val);
        return $prefix . $val;
    }
}
