<?php 	
abstract class SLN_Admin_SettingTabs_AbstractTab
{
	protected $settings;

	private function validate(){}

	private function postProcess(){}
    function getOpt($key)
    {
        return $this->settings->get($key);
    }


    function row_input_checkbox($key, $label, $settings = array())
    {
        SLN_Form::fieldCheckbox(
            "salon_settings[{$key}]",
            $this->getOpt($key),
            $settings
        )
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_input_checkbox_switch($key, $label, $settings = array())
    { ?>
        <h6 class="sln-fake-label"><?php echo $label ?></h6>
        <?php SLN_Form::fieldCheckbox(
        "salon_settings[{$key}]",
        $this->getOpt($key),
        $settings
            )
        ?>
        <label for="salon_settings_<?php echo $key ?>" class="sln-switch-btn" data-on="On" data-off="Off"></label>
        <?php
        if (isset($settings['help'])) { ?>
            <label class="sln-switch-text" for="salon_settings_<?php echo $key ?>"
                   data-on="<?php echo $settings['bigLabelOn'] ?>"
                   data-off="<?php echo $settings['bigLabelOff'] ?>"></label>
        <?php }
        if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_input_text($key, $label, $settings = array())
    {
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php SLN_Form::fieldText("salon_settings[$key]", $this->getOpt($key), $settings) ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_input_email($key, $label, $settings = array())
    {
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php echo SLN_Form::fieldEmail("salon_settings[$key]", $this->getOpt($key)) ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }


    function row_checkbox_text($key, $label, $settings = array())
    {
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php echo SLN_Form::fieldCheckbox("salon_settings[$key]", $this->getOpt($key)) ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php }
    }

    function row_input_textarea($key, $label, $settings = array())
    {
        if (!isset($settings['textarea'])) {
            $settings['textarea'] = array();
        }
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label>
        <?php SLN_Form::fieldTextarea("salon_settings[$key]", $this->getOpt($key), $settings['textarea']); ?>
        <?php if (isset($settings['help'])) { ?><p class="help-block"><?php echo $settings['help'] ?></p><?php } ?>
        <?php
    }

    function row_input_page($key, $label, $settings = array())
    {
        ?>
        <label for="<?php echo $key ?>"><?php echo $label ?></label>
        <?php
        wp_dropdown_pages(
            array(
                'name' => 'salon_settings['.$key.']',
                'selected' => $this->getOpt($key) ? $this->settings->{'get'.ucfirst($key).'PageId'}() : null,
                'show_option_none' => 'Nessuna',
            )
        );
    }

    /**
     * select_text
     * @param type $list
     * @param type $value
     * @param type $settings
     */
    function select_text($key, $label, $list, $settings = array())
    {
        ?>
        <label for="salon_settings_<?php echo $key ?>"><?php echo $label ?></label></th>
        <select name="salon_settings[<?php echo $key ?>]">
            <?php
            foreach ($list as $k => $value) {
                $lbl = $value['label'];
                $sel = ($value['id'] == $this->getOpt($key)) ? "selected" : "";
                echo "<option value='$k' $sel>$lbl</option>";
            }
            ?>
        </select>
        <?php
    }

    function hidePriceSettings()
    {
        $ret = $this->getOpt('hide_prices') ? array(
            'attrs' => array(
                'disabled' => 'disabled',
                'title' => 'Please disable hide prices from general settings to enable online payment.',
            ),
        ) : array();

        return $ret;
    }

}

?> 		