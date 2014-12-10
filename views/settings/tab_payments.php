<table class="form-table">
    <?php
    $this->row_input_checkbox('pay_enabled', __('Enable online payments', 'sln'));
    $this->row_input_checkbox('pay_cash', __('Client can pay on delivery', 'sln'));
    ?>
    <tr valign="top">
        <th scope="row"><label for="saloon_settings[pay_currency]">Currency</label></th>
        <td>
            <?php echo SLN_Form::fieldCurrency(
                "saloon_settings[pay_currency]",
                $this->settings->getCurrency()
            ) ?>
        </td>
    </tr>
    <?php
    $this->row_input_text('pay_paypal_email', __('Paypal E-mail', 'sln'));
    $this->row_input_checkbox('pay_paypal_test', __('Paypal Test Mode', 'sln'));
    ?>
</table>