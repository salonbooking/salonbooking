<?php
/**
 * @var $this SLN_Plugin
 */
$enum = new SLN_Enum_ShortcodeStyle();
$curr = $this->settings->getStyleShortcode();
$colors = $this->settings->get('style_colors') ? $this->settings->get('style_colors') : array();
?>
    <div class="sln-box sln-box--main">
        <h2 class="sln-box-title">
            <?php _e('Select your favorite booking form layout', 'salon-booking-system'); ?>
            <span><?php _e('Choose the one that best fits your page', 'salon-booking-system'); ?></span>
        </h2>
        <div class="row">
            <?php foreach ($enum->toArray() as $key => $label):
                ?>
                <div class="sln-radiobox sln-radiobox--fullwidth col-sm-4">
                    <input type="radio" name="salon_settings[style_shortcode]"
                           value="<?php echo $key ?>"
                           id="style_shortcode_<?php echo $key ?>"
                        <?php echo ($curr == $key) ? 'checked="checked"' : '' ?> >
                    <label for="style_shortcode_<?php echo $key ?>"><?php echo $label ?></label>
                    <img src="<?php echo $enum->getImage($key); ?>" style="width: 100%"/>
                    <p><?php echo $enum->getDescription($key) ?></p>
                </div>
            <?php endforeach ?>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="sln-box sln-box--main">
<div class="sln-box sln-box--main">
    <div class="row">
    <h2 class="sln-box-title">
            <?php _e('Custom colors', 'salon-booking-system'); ?>
            <span><?php _e('Choose the one that best fits your page', 'salon-booking-system'); ?></span>
        </h2>
        <div class="col-xs-12 col-sm-6 col-md-6 form-group sln-switch">
            <?php $this->row_input_checkbox_switch(
                'style_colors_enabled',
                'Custom colors',
                array(
                    'help' => __('customize colors of the salon shortcode.','salon-booking-system'),
                    'bigLabelOn' => 'Custom colors are enabled',
                    'bigLabelOff' => 'Custom colors are disabled'
                    )
            ); ?>
        </div>
    </div>
</div>
        <div class="row">
            <div class="col-md-12 col-lg-8 sln-colors-sample">
                <div class="wrapper">
                    <h1 class="sln-box-title">Sample page/step title</h1>
                    <label>Sample label</label><br>
                    <input type="text" value="Sample input" /><br>
                    <button value="Sample button">Sample button <i class="glyphicon glyphicon-chevron-right"></i></button>
                    <p>
                        Sample text. Pellentesque viverra dictum lectus eu fringilla. Nam metus sapien, pharetra id nunc sit amet, feugiat auctor ipsum. Proin volutpat, ipsum a laoreet tristique, dui tortor.
                    </p>
                    <small class="sln-input-help">Morbi non erat elementum neque lacinia finibus. Sed rutrum viverra tortor. Sed laoreet, quam vestibulum molestie laoreet, dui justo egestas.</small>

                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="row">
                    <div id="color-background" class="col-sm-4  col-lg-12 sln-input--simple sln-colorpicker">
                        <label><?php _e('Background color', 'salon-booking-system'); ?></label>
                        <div class="sln-colorpicker--subwrapper">
                            <span id="thisone" class="input-group-addon sln-colorpicker-addon"><i>color sample</i></span>
                            <input type="text" value="<?php echo isset($colors['background-a']) ? $colors['background-a'] : 'rgba(255, 255, 255, 1)' ?>" class="sln-input sln-input--text  sln-colorpicker--trigger" />
                        </div>
                    </div>
                    <div id="color-main" class="col-sm-4  col-lg-12 sln-input--simple sln-colorpicker">
                        <label for="salon_settings_gen_name"><?php _e('Main color', 'salon-booking-system'); ?></label>
                        <div class="sln-colorpicker--subwrapper">
                            <span id="thisone" class="input-group-addon sln-colorpicker-addon"><i>color sample</i></span>
                            <input type="text" value="<?php echo isset($colors['main-a']) ? $colors['main-a'] : 'rgba(2,119,189,1)' ?>" class="sln-input sln-input--text  sln-colorpicker--trigger" />
                        </div>
                    </div>
                    <div id="color-text" class="col-sm-4  col-lg-12 sln-input--simple sln-colorpicker">
                        <label for="salon_settings_gen_name"><?php _e('Text color', 'salon-booking-system'); ?></label>
                        <div class="sln-colorpicker--subwrapper">
                            <span id="thisone" class="input-group-addon sln-colorpicker-addon"><i>color sample</i></span>
                            <input type="text" value="<?php echo isset($colors['text-a']) ? $colors['text-a'] : 'rgba(68,68,68,1)' ?>" class="sln-input sln-input--text  sln-colorpicker--trigger" />
                        </div>
                    </div>
                    <div class="col-sm-6  col-lg-12 form-group sln-box-maininfo">
                        <?php foreach(array('background-a', 'main-a', 'main-b','main-c','text-a', 'text-b', 'text-c') as $k): ?>
                            <input class="hidden" name="salon_settings[style_colors][<?php echo $k ?>]" id="color-<?php echo $k ?>" type="text" value="<?php echo isset($colors[$k]) ? $colors[$k] : '' ?>">
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

