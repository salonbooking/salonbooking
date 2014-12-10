<?php
/**
 * @param $this SLN_Admin_Settings
 */
function sln_availability_row($prefix, $row)
{
    foreach (SLN_Func::getDays() as $k => $day) {
        ?>
        <label>
            <?php SLN_Form::fieldCheckbox(
                $prefix . "[days][{$k}]",
                $row['days'][$k]
            ) ?>
            <?php echo substr($day, 0, 3) ?></label>
    <?php } ?><br/>
    <?php foreach (array(0, 1) as $i) { ?>
    <div>
        <?php foreach (array('from' => __('From', 'sln'), 'to' => __('To', 'sln')) as $k => $v) { ?>
            <label><?php echo $v ?>
                <?php SLN_Form::fieldTime($prefix . "[$k][$i]", $row[$k][$i]) ?>
            </label>
        <?php } ?>
    </div>
<?php } ?>
<?php
}

?>
<table class="form-table">

    <?php
    $key   = 'available';
    $label = __('Online booking not available on', 'sln');
    $availabilities = $this->getOpt('availabilities');
    ?>
    <tr valign="top">
        <th scope="row" nowrap="nowrap"><?php echo $label ?></th>
        <td>
            <div id="sln-availabilities">
                <div class="items">
                    <?php
                    foreach ($availabilities as $k => $row) {
                        ?>
                        <div class="item"><?php
                        sln_availability_row("saloon_settings[availabilities][$k]", $row);
                        ?>
                        <button data-collection="remove">Remove</button></div><?php
                    }
                    ?>
                </div>
                <button data-collection="addnew">addnew</button>
                <div data-collection="prototype" data-count="<?php echo count($availabilities) ?>">
                    <?php sln_availability_row("saloon_settings[availabilities][__new__]", $row); ?>
                    <button data-collection="remove">Remove</button>
                </div>
            </div>
            </div>
        </td>
    </tr>
    <?php
    $this->row_input_checkbox('confirmation', __('Bookings Confirmation', 'sln'));
    $this->row_input_page('thankyou', __('Thank you page', 'sln'));
    ?>
</table>
