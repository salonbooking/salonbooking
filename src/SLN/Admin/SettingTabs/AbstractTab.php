<?php 	
abstract class SLN_Admin_SettingTabs_AbstractTab
{
	const PAGE = 'salon-settings';

	protected $plugin;
	protected $settings;
	protected $fields;		
	protected $slug;
	protected $label;
	protected $submitted;

	
	function __construct($slug,$label,$plugin){
				
		$this->plugin = $plugin;
		$this->settings = $plugin->getSettings();
		$this->slug = $slug;
		$this->label = $label;
		$this->fields = apply_filters('sln.settings.'.$slug.'.fields',$this->fields, $this);
		if ($_POST) {
            if (empty($_POST[self::PAGE.$this->slug]) || !wp_verify_nonce($_POST[self::PAGE.$this->slug])) {
                $this->process();
            } else {
                $this->showAlert(
                    'error',
                    __('try again', 'salon-booking-system'),
                    __('Page verification failed', 'salon-booking-system')
                );
            }
        }
	}

    public function getFields(){
        return $this->fields;
    }

    public function getSlug(){
        return $this->slug;
    }

    public function getLabel(){
        return $this->label;
    }    
    
	public function show(){        
		include $this->plugin->getViewFile('admin/utilities/settings-sidebar');
        echo '<div class="sln-tab" id="sln-tab-'.$this->slug.'">';
        include $this->plugin->getViewFile('settings/tab_'.$this->slug);        
        do_action('sln.view.settings.'.$this->slug.'.additional_fields',$this);
        echo '</div>
        <div class="clearfix"></div>';
	}

	public function process(){
				
		$this->getSubmittedFields();
		$this->validate();        
        apply_filters( 'sln.settings.'.$this->slug.'.validate',$this->submitted, $this );
        $this->saveSettings();
        $this->postProcess();        
        apply_filters( 'sln.settings.'.$this->slug.'.post_process',$this->submitted, $this );
        $this->showAlert(
            'success',
            __(''.$this->label.' settings are updated', 'salon-booking-system'),
            __('Update completed with success', 'salon-booking-system')
        );
	}

	protected function validate(){}

	protected function postProcess(){}

	protected function getSubmittedFields(){
		
        $posted = $_POST['salon_settings'];
		$submitted = array();
		foreach ($this->fields as $k) {
			$submitted[$k] = isset($posted[$k]) ? $posted[$k] : null;
        }
        $this->submitted = $submitted;
	}

	private function saveSettings()
    {
    	$submitted = $this->submitted;
        foreach ($submitted as $k => $data ) {
            
            if(is_string($data))$data = stripcslashes($data);
            $this->settings->set($k, $data);
        }
        $this->settings->save();
    }

    protected function showAlert($type, $txt, $title = null)
    {
        ?>
        <div id="sln-setting-<?php echo $type ?>" class="updated settings-<?php echo $type ?>">
            <?php if (!empty($title)) { ?>
                <p><strong><?php echo $title ?></strong></p>
            <?php } ?>
            <p><?php echo $txt ?></p>
        </div>
        <?php
    }

    function getOpt($key)
    {
        return $this->settings->get($key);
    }


    function row_input_checkbox($key, $label, $settings = array())
    {?>
         <input type='hidden' value='0' name='salon_settings[<?php echo $key; ?>]'>
        <?php SLN_Form::fieldCheckbox(
            "salon_settings[$key]",
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
        <input type='hidden' value='0' name='salon_settings[<?php echo $key; ?>]'>
        <?php SLN_Form::fieldCheckbox(
        "salon_settings[$key]",
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