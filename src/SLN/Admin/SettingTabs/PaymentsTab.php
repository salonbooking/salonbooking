<?php 	
class SLN_Admin_SettingTabs_PaymentsTab extends SLN_Admin_SettingTabs_AbstractTab
{
    protected $fields = array(
        'hide_prices',
        'pay_method',
        'pay_currency',
        'pay_currency_pos',
        'pay_decimal_separator',
        'pay_thousand_separator',
        'pay_paypal_email',
        'pay_paypal_test',
        'pay_cash',
        'pay_offset_enabled',
        'pay_offset',
        'pay_enabled',
        'pay_deposit',
        'pay_deposit_fixed_amount',
    );

}
 ?>