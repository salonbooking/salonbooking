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
            <?php _e('Select your favorite layout', 'salon-booking-system'); ?>
            <span><?php _e('Choose the layout that best fit your page', 'salon-booking-system'); ?></span>
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
</div>
<div class="clearfix"></div>
