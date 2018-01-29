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
    <?php
    if ($size == '900') { ?>
        <div class="row">
            <div class="col-sm-6 col-md-4 sln-input sln-input--simple"><?php echo $fieldEmail?></div>
            <div class="col-sm-6 col-md-4 sln-input sln-input--simple"><?php echo $fieldPassword?></div>
            <div class="col-sm-6 col-md-4 pull-right sln-input sln-input--simple">
                <label for="login_name">&nbsp;</label>
                <div class="sln-btn sln-btn--emphasis sln-btn--big sln-btn--fullwidth">
                    <button type="submit"
                        <?php if ($ajaxEnabled): ?>
                            data-salon-data="<?php echo "sln_step_page={$current}&{$submitName}=next" ?>" data-salon-toggle="next"
                        <?php endif ?>
                            name="<?php echo $submitName ?>" value="next">
                        <?php echo __('Login', 'salon-booking-system') ?> <i class="glyphicon glyphicon-user"></i>
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
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
        <div class="row">
            <div class="col-sm-6 col-md-6 sln-input sln-input--simple"><?php echo $fieldEmail?></div>
            <div class="col-sm-6 col-md-6 sln-input sln-input--simple"><?php echo $fieldPassword?></div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-6"></div>
            <div class="col-sm-6 col-md-6 sln-input sln-input--simple">
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
    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
        <div class="row">
            <div class="col-xs-12 sln-input sln-input--simple"><?php echo $fieldEmail?></div>
            <div class="col-xs-12 sln-input sln-input--simple"><?php echo $fieldPassword?></div>
            <div class="col-xs-12 sln-input sln-input--simple">
                <label for="login_name">&nbsp;</label>
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
    <?php
    // IF SIZE 400 // END
    } else  { ?>

    <?php
    // ELSE // END
    }  ?>
    </form>
    <?php endif; ?>
    <form method="post" action="<?php echo $formAction ?>" role="form" id="salon-step-details-new">
        <div class="row">
            <div>
            <?php if($plugin->getSettings()->get('enabled_force_guest_checkout')): ?>
                <h2 class="salon-step-title"><?php _e('Please fill out the form to checkout', 'salon-booking-system') ?></h2>
                <?php SLN_Form::fieldCheckbox(
                    'sln[no_user_account]',
                    $bb->get('no_user_account'),
                    array(
                        'type' => 'hidden',
                        'attrs' => array(
                            'checked' => 'checked',
                            'style' => 'display:none'
                        )
                    )
                ) ?>
            <?php elseif($plugin->getSettings()->get('enabled_guest_checkout')): ?>
                <div class="col-xs-2 col-sm-1 sln-checkbox">
                    <div class="sln-checkbox">
                        <?php SLN_Form::fieldCheckbox(
                            'sln[no_user_account]',
                            $bb->get('no_user_account'),
                            array()
                        ) ?>
                        <label for="<?php echo SLN_Form::makeID('sln[no_user_account]') ?>"></label>
                    </div>
                </div>
                <div class="col-md-11">
                    <label for="<?php echo SLN_Form::makeID('sln[no_user_account]') ?>"><h2 class="salon-step-title"><?php _e('checkout as a guest', 'salon-booking-system') ?>, <?php _e('no account will be created', 'salon-booking-system') ?></h2></label>
                </div>
            <?php else: ?>
                <h2 class="salon-step-title"><?php _e('Checkout as a guest', 'salon-booking-system') ?>, <?php _e('An account will be automatically created', 'salon-booking-system') ?></h2>
            <?php endif; ?>
            </div>
        </div>
    <?php
    if ($size == '900') { ?>
    <div class="row">
    <div class="col-md-8">
        <div class="row">
            <?php foreach (SLN_Enum_CheckoutFields::toArrayFull() as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                }; 
                $type = isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? SLN_Enum_CheckoutFields::$additional_fields_types[$field] : false;
            ?>
                <?php if($field === 'password') echo '</div><div class="row">'; // close previous row & open next ?>
                <div class="col-sm-6 col-md-<?php echo $field == 'address' ? 12 : 6 ?> <?php echo 'field-'.$field ?> <?php if($type !== 'checkbox'){ echo 'sln-input sln-input--simple'; } ?> <?php echo $type ? 'sln-'.$type  : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                            <?php endif ?>
                        <?php
                            if(strpos($field, 'password') === 0){
                                SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => true, 'type' => 'password'));
                            } else if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else {
                            if($type){
                                $additional_opts = array( 
                                    'sln[' . $field . ']', $bb->get($field), 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                $method_name = 'field'.ucfirst($type);
                                if($type === 'checkbox') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';                                    
                                }
                                
                                if($type === 'select') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                    
                                }
call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
}else{
                                SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                                }
                            }
                        ?>
                        <?php if(($field == 'phone') && isset($prefix)):?>
                        </div>
                        <?php endif ?>
                </div>

            <?php endforeach ?>
        </div>
    </div>
    <div class="col-md-4 sln-input sln-input--action  sln-box--main sln-box--formactions">
        <label for="login_name">&nbsp;</label>
        <?php include "_form_actions.php" ?>
    </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
    <div class="row">
            <?php foreach (SLN_Enum_CheckoutFields::toArrayFull() as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                };
                $type = isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? SLN_Enum_CheckoutFields::$additional_fields_types[$field] : false;
                 ?>
                <?php if($field === 'password') echo '</div><div class="row">'; // close previous row & open next ?>
                <div class="col-sm-6 col-md-<?php echo $field == 'address' ? 12 : 6 ?> <?php echo 'field-'.$field ?> <?php if($type !== 'checkbox'){ echo 'sln-input sln-input--simple'; } ?> <?php echo $type ? 'sln-'.$type  : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                            <?php endif ?>

                        <?php
                            if(strpos($field, 'password') === 0){
                                SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => true, 'type' => 'password'));
                            } else if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else {
                            if($type){
                                $additional_opts = array( 
                                    'sln[' . $field . ']', $bb->get($field), 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                $method_name = 'field'.ucfirst($type);
                                if($type === 'checkbox') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';                                    
                                }
                                
                                if($type === 'select') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                    
                                }
call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
}else{
                                SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                                }
                            }
                        ?>
                        <?php if(($field == 'phone') && isset($prefix)):?>
                        </div>
                        <?php endif ?>
                </div>

            <?php endforeach ?>
    </div>
    <div class="row sln-box--main sln-box--formactions">
           <div class="col-md-12">
           <?php include "_form_actions.php" ?></div>
        </div>
    <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
    <div class="row">
            <?php foreach (SLN_Enum_CheckoutFields::toArrayFull() as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                };
                $type = isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? SLN_Enum_CheckoutFields::$additional_fields_types[$field] : false; ?>
                <?php if($field === 'password') echo '</div><div class="row">'; // close previous row & open next ?>
                <div class="col-xs-12 <?php echo 'field-'.$field ?> <?php if($type !== 'checkbox'){ echo 'sln-input sln-input--simple'; } ?> <?php echo $type ? 'sln-'.$type  : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                            <?php endif ?>
                        <?php
                            if(strpos($field, 'password') === 0){
                                SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => true, 'type' => 'password'));
                            } else if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else {
                            if($type){
                                $additional_opts = array( 
                                    'sln[' . $field . ']', $bb->get($field), 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                $method_name = 'field'.ucfirst($type);
                                if($type === 'checkbox') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';                                    
                                }
                                
                                if($type === 'select') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                    
                                }
call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
}else{
                                SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                                }
                            }
                        ?>
                        <?php if(($field == 'phone') && isset($prefix)):?>
                        </div>
                        <?php endif ?>
                </div>

            <?php endforeach ?>
            <div class="col-xs-12  sln-box--formactions"><label for="login_name">&nbsp;</label><?php include "_form_actions.php" ?></div>
    </div>
    <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
    <?php
    // IF SIZE 400 // END
    } else  { ?>

    <?php
    // ELSE // END
    }  ?>
    </form>
<?php else: ?>

    <form method="post" action="<?php echo $formAction ?>" role="form">
        <?php
        $args = array(
            'label'        => __('Checkout', 'salon-booking-system'),
            'tag'          => 'h2',
            'textClasses'  => 'salon-step-title',
            'inputClasses' => '',
            'tagClasses'   => 'salon-step-title',
        );
        echo $plugin->loadView('shortcode/_editable_snippet', $args);
        ?>
    <?php
    if ($size == '900') { ?>
    <div class="row">
    <div class="col-md-8">
        <div class="row">
            <?php foreach (SLN_Enum_CheckoutFields::toArray() as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                };
                $type = isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? SLN_Enum_CheckoutFields::$additional_fields_types[$field] : false; ?>
                <div class="col-sm-6 col-md-<?php echo $field == 'address' ? 12 : 6 ?> <?php echo 'field-'.$field ?> <?php if($type !== 'checkbox'){ echo 'sln-input sln-input--simple'; } ?> <?php echo $type ? 'sln-'.$type  : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                        <?php endif ?>
                        <?php
                           if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else{
                            if($type){
                                $additional_opts = array( 
                                    'sln[' . $field . ']', $bb->get($field), 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                $method_name = 'field'.ucfirst($type);
                                if($type === 'checkbox') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';                                    
                                }
                                
                                if($type === 'select') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                    
                                }
call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
}else{
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                            }
                           }
                        ?>
                            <?php if(($field == 'phone') && isset($prefix)):?>
                                </div>
                            <?php endif ?>
                </div>

            <?php endforeach ?>
        </div>
    </div>
    <div class="col-md-4 sln-input sln-input--action sln-box--formactions">
        <label for="login_name">&nbsp;</label>
        <?php include "_form_actions.php" ?>
    </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
    <div class="row">
            <?php foreach (SLN_Enum_CheckoutFields::toArray() as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                };
                $type = isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? SLN_Enum_CheckoutFields::$additional_fields_types[$field] : false; ?>
                <div class="col-sm-6 col-md-<?php echo $field == 'address' ? 12 : 6 ?> <?php echo 'field-'.$field ?> <?php if($type !== 'checkbox'){ echo 'sln-input sln-input--simple'; } ?> <?php echo $type ? 'sln-'.$type  : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                        <?php endif ?>
                        <?php
                           if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else{
                            if($type){
                                $additional_opts = array( 
                                    'sln[' . $field . ']', $bb->get($field), 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                $method_name = 'field'.ucfirst($type);
                                if($type === 'checkbox') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';                                    
                                }
                                
                                if($type === 'select') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                    
                                }
call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
}else{
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                            }
                           }
                        ?>
                            <?php if(($field == 'phone') && isset($prefix)):?>
                                </div>
                            <?php endif ?>
                </div>

            <?php endforeach ?>
        </div>
    <div class="row sln-box--formactions">
           <div class="col-md-12">
           <?php include "_form_actions.php" ?></div>
        </div>
    <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
    <div class="row">
            <?php foreach (SLN_Enum_CheckoutFields::toArray() as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                };
                $type = isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? SLN_Enum_CheckoutFields::$additional_fields_types[$field] : false; ?>
                <div class="col-xs-12 <?php echo 'field-'.$field ?> <?php if($type !== 'checkbox'){ echo 'sln-input sln-input--simple'; } ?> <?php echo $type ? 'sln-'.$type  : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                        <?php endif ?>
                        <?php
                           if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else{
                            if($type){
                                $additional_opts = array( 
                                    'sln[' . $field . ']', $bb->get($field), 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                $method_name = 'field'.ucfirst($type);
                                if($type === 'checkbox') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';                                    
                                }
                                
                                if($type === 'select') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                    
                                }
call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
}else{
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                            }
                           }
                        ?>
                            <?php if(($field == 'phone') && isset($prefix)):?>
                                </div>
                            <?php endif ?>
                </div>
            <?php endforeach ?>
            <div class="col-xs-12 sln-box--formactions"><label for="login_name">&nbsp;</label><?php include "_form_actions.php" ?></div>
    </div>
    <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
    <?php
    // IF SIZE 400 // END
    } else  { ?>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <?php foreach (SLN_Enum_CheckoutFields::toArray() as $field => $label):  ?>
                <?php if(SLN_Enum_CheckoutFields::isHidden($field)) {
                    SLN_Form::fieldText(
                        "sln[{$field}]",
                        '',
                        array('type' => 'hidden')
                    );
                    continue;
                };
                $type = isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? SLN_Enum_CheckoutFields::$additional_fields_types[$field] : false; ?>
                <div class="col-md-<?php echo $field == 'address' ? 12 : 6 ?> <?php echo 'field-'.$field ?> <?php if($type !== 'checkbox'){ echo 'sln-input sln-input--simple'; } ?> <?php echo $type ? 'sln-'.$type  : '' ?>">
                        <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
                        <?php if(($field == 'phone') && ($prefix = $plugin->getSettings()->get('sms_prefix'))): ?>
                        <div class="input-group sln-input-group">
                            <span class="input-group-addon sln-input--addon"><?php echo $prefix?></span>
                        <?php endif ?>
                        <?php
                           if(strpos($field, 'email') === 0){
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field), 'type' => 'email'));
                           } else{
                            if($type){
                                $additional_opts = array( 
                                    'sln[' . $field . ']', $bb->get($field), 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                $method_name = 'field'.ucfirst($type);
                                if($type === 'checkbox') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';                                    
                                }
                                
                                if($type === 'select') {
                                    $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                    
                                }
call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
}else{
                               SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => SLN_Enum_CheckoutFields::isRequired($field)));
                            }
                           }
                        ?>
                            <?php if(($field == 'phone') && isset($prefix)):?>
                                </div>
                            <?php endif ?>
                </div>

            <?php endforeach ?>
        </div>
    </div>
    <div class="col-md-4 sln-input sln-input--action sln-box--formactions">
        <label for="login_name">&nbsp;</label>
        <?php include "_form_actions.php" ?>
    </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><?php include '_errors.php'; ?></div>
    </div>
    <?php
    // ELSE // END
    }  ?>
    </form>
<?php endif ?>

