<?php
/**
 * @var SLN_Plugin                $plugin
 * @var string                    $formAction
 * @var string                    $submitName
 * @var SLN_Shortcode_Salon_Step $step
 */
$bb = $plugin->getBookingBuilder();
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
$checkoutFieldsSettings = (array)$plugin->getSettings()->get('checkout_fields');
global $current_user;
wp_get_current_user();
$values = array(
    'firstname' => $current_user->user_firstname,
    'lastname'  => $current_user->user_lastname,
    'email'     => $current_user->user_email,
    'phone'     => get_user_meta($current_user->ID, '_sln_phone', true)
);

$current     = $step->getShortcode()->getCurrentStep();
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();

ob_start();
?>
<label for="login_name"><?php _e('E-mail') ?></label>
<input name="login_name" type="text" class="sln-input sln-input--text"/>
<span class="help-block"><a href="<?php echo wp_lostpassword_url() ?>" class="tec-link"><?php _e('Forgot password?', 'salon-booking-system') ?></a></span>
<?php
$fieldEmail = ob_get_clean();

ob_start();
?>
<label for="login_password"><?php _e('Password') ?></label>
<input name="login_password" type="password" class="sln-input sln-input--text"/>
<?php
$fieldPassword = ob_get_clean();

?>
<?php if (!is_user_logged_in()): ?>
    <?php if (!$plugin->getSettings()->get('enabled_force_guest_checkout')): ?>
        <form method="post" action="<?php echo $formAction ?>" role="form" id="salon-step-details">
            <h2 class="salon-step-title"><?php _e('Returning customer?', 'salon-booking-system') ?> <?php _e('Please, log-in.', 'salon-booking-system') ?> </h2>
            <div class="row">
            <div class="<?php echo ($size == '300' ? 'col-xs-12' : ( 'col-sm-6 '.( $size == '600' ? 'col-md-6  ' : 'col-md-4 ' ) ) )?> sln-input sln-input--simple"><?php echo $fieldEmail?></div>
            <div class="<?php echo ($size == '300' ? 'col-xs-12' : ( 'col-sm-6 '.( $size == '600' ? 'col-md-6  ' : 'col-md-4 ' ) ) )?> sln-input sln-input--simple"><?php echo $fieldPassword?></div>
        <?php if ($size == '600'){ ?>
        </div>
        <div class="row">
        <?php } ?>

            <div class="<?php echo ($size == '300' ? 'col-xs-12' : ( 'col-sm-6 '.( $size == '600' ? 'col-md-6  ' : 'col-md-4 ' ) ) )?> sln-input sln-input--simple">
        <?php if ($size == '600'){ ?>        
            </div>
            <div class="col-sm-6 col-md-6 sln-input sln-input--simple">
        <?php }else{ ?>
                <label for="login_name">&nbsp;</label>
        <?php } ?>
                <div class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth">
                <button type="submit"
                    <?php if ($ajaxEnabled): ?>
                        data-salon-data="<?php echo "sln_step_page={$current}&{$submitName}=next" ?>" data-salon-toggle="next"
                    <?php endif ?>
                        name="<?php echo $submitName ?>" value="next">
                    <?php echo __('Login','salon-booking-system')?> <i class="glyphicon glyphicon-user"></i>
                </button>
                </div>
                <span class="help-block">
                    <a href="#" class="tec-link"
                        <?php if ($ajaxEnabled): ?>
                            data-salon-data="<?php echo "sln_step_page={$current}&{$submitName}=next" ?>" data-salon-toggle="ajax"
                        <?php endif ?>
                       data-salon-click="fb_login"><?php _e('log-in with Facebook', 'salon-booking-system'); ?></a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12"><?php include '_errors.php'; ?></div>
        </div>
        </form>
    <?php endif; ?>
        <?php endif; ?>

    <form method="post" action="<?php echo $formAction ?>" role="form">
        <?php
        if (is_user_logged_in()){
        $args = array(
            'label'        => __('Checkout', 'salon-booking-system'),
            'tag'          => 'h2',
            'textClasses'  => 'salon-step-title',
            'inputClasses' => '',
            'tagClasses'   => 'salon-step-title',
        );
        echo $plugin->loadView('shortcode/_editable_snippet', $args);
        }
        $fields = !is_user_logged_in() ? SLN_Enum_CheckoutFields::toArrayFull() : SLN_Enum_CheckoutFields::toArray() ;
        ?>
        <div class="row">
            <?php foreach ($fields as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                }; ?>
                <?php if(!is_user_logged_in() && $field === 'password') echo '</div><div class="row">'; // close previous row & open next ?>
                <div class="<?php echo ( $size == 400 ? 'col-xs-12' : 'col-sm-6 col-md-' . ($field == 'address' ? 12 : 6).' ' ) ?> <?php echo 'field-'.$field ?> sln-input sln-input--simple">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                        <?php endif ?>
                        <?php
                           if(strpos($field, 'password') === 0){
                                            SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => true, 'type' => 'password'));
                            }else if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else{
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                           }
                        ?>
                            <?php if(($field == 'phone') && isset($prefix)):?>
                                </div>
                            <?php endif ?>
                </div>
            <?php endforeach ?>
            <div class="<?php echo ( $size == 400 ? 'col-xs-12' : ( $size == 600 ? 'col-md-12' : 'col-md-4 ' )) ?> sln-box--formactions"><label for="login_name">&nbsp;</label><?php include "_form_actions.php" ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12"><?php include '_errors.php'; ?></div>
        </div>
    </form>


