<?php

function sln_admin_menu() {
        add_options_page( __( 'Saloon Settings','sln'), __( 'Saloon','sln' ), 'manage_options', 'saloon', 'sln_plugin_page' );
}

function sln_admin_field($key, $label){
    ?>
       <tr valign="top">
        <th scope="row"><?php echo $field['label']?></th>
        <td><input name="<?php echo $key?>" type="text" required="required" value="<?php echo get_option($key); ?>" /></td>
        </tr>
    <?php
}

function sln_plugin_page(){
$fields = array();
?>
<div class="wrap">
<h2>Saloon Settings</h2>

<form method="post" action="options-general.php?page=sln">
    <?php settings_fields( 'sln-settings-group' ); ?>
    <?php do_settings_sections( 'sln-settings-group' ); ?>
<?/*
    <?php if($_POST){
        foreach($fields as $k => $v){
            $val = $_POST[$k];
            $val = empty($v) ? sln_filter($val,$v) : $val;
            update_option($k,$val);
        }
        echo '<div id="message" class="updated fade"><p><strong>settings saved.</strong></p></div>';
    } ?>
*/?>
    <table class="form-table">
        <tr><th colspan="2"><strong>General</strong></th></tr>
       <tr valign="top">
        <th scope="row">Thank you page</th>
        <td>
<?php

$key = 'sln_thankyou';

 wp_dropdown_pages(array(
'name' => $key,
'selected' => get_option($key) ? get_option($key) : null,
'show_option_none'      => 'Nessuna'
             )) 
?> </td>
        </tr>


    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>
<?php

