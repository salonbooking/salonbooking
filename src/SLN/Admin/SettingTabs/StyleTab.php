<?php 	
class SLN_Admin_SettingTabs_StyleTab extends SLN_Admin_SettingTabs_AbstractTab
{
	protected $fields = array(
        'style_shortcode',
        'style_colors_enabled',
        'style_colors',
    );

	protected function postProcess(){
		$this->settings->save();
        if ($this->settings->get('style_colors_enabled')) {
            $this->saveCustomCss();
        }
	}

    protected function saveCustomCss()
    {
        $css = file_get_contents(SLN_PLUGIN_DIR.'/css/sln-colors--custom.css');
        $colors = $this->settings->get('style_colors');

        if ($colors) {
            foreach ($colors as $k => $v) {
                $css = str_replace("{color-$k}", $v, $css);
            }
        }
        $dir = wp_upload_dir();
        $dir = $dir['basedir'];
        file_put_contents($dir.'/sln-colors.css', $css);
    }
}
 ?>