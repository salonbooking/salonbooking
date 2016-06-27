<?php
/**
 * @var $this SLN_Plugin
 */
$enum = new SLN_Enum_ShortcodeStyle();
$curr = $this->settings->getStyleShortcode();
?>
<div class="sln-tab" id="sln-tab-style">
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
        <h2 class="sln-box-title">
            <?php _e('Select your favorite booking form layout', 'salon-booking-system'); ?>
            <span><?php _e('Choose the one that best fits your page', 'salon-booking-system'); ?></span>
        </h2>
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
                    <div id="color-backgroud" class="col-sm-4  col-lg-12 sln-input--simple sln-colorpicker">
                        <label><?php _e('Background color', 'salon-booking-system'); ?></label>
                        <div class="sln-colorpicker--subwrapper">
                            <span id="thisone" class="input-group-addon sln-colorpicker-addon""><i>color sample</i></span>
                            <input type="text" value="rgba(255, 255, 255, 1)" class="sln-input sln-input--text  sln-colorpicker--trigger" />
                        </div>
                    </div>
                    <div id="color-main" class="col-sm-4  col-lg-12 sln-input--simple sln-colorpicker">
                        <label for="salon_settings_gen_name"><?php _e('Main color', 'salon-booking-system'); ?></label>
                        <div class="sln-colorpicker--subwrapper">
                            <span id="thisone" class="input-group-addon sln-colorpicker-addon""><i>color sample</i></span>
                            <input type="text" value="rgba(2,119,189,1)" class="sln-input sln-input--text  sln-colorpicker--trigger" />
                        </div>
                    </div>
                    <div id="color-text" class="col-sm-4  col-lg-12 sln-input--simple sln-colorpicker">
                        <label for="salon_settings_gen_name"><?php _e('Text color', 'salon-booking-system'); ?></label>
                        <div class="sln-colorpicker--subwrapper">
                            <span id="thisone" class="input-group-addon sln-colorpicker-addon""><i>color sample</i></span>
                            <input type="text" value="rgba(68,68,68,1)" class="sln-input sln-input--text  sln-colorpicker--trigger" />
                        </div>
                    </div>
                    <div class="col-sm-6  col-lg-12 form-group sln-box-maininfo">
                        <input id="color-main-a" type="text">
                        <input id="color-main-b" type="text">
                        <input id="color-main-c" type="text">
                        <input id="color-text-a" type="text">
                        <input id="color-text-b" type="text">
                        <input id="color-text-c" type="text">
                        <p class="sln-input-help">Morbi non erat elementum neque lacinia finibus. Sed rutrum viverra tortor. Sed laoreet, quam vestibulum molestie laoreet, dui justo egestas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
