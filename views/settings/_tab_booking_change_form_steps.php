<?php
/**
 * @var $plugin SLN_Plugin
 * @var $helper SLN_Admin_Settings
 */
?>
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="sln-box sln-box--main sln-box--main--small">
        <h2 class="sln-box-title"><?php _e('Change booking form steps order','salon-booking-system');?></h2>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 form-group sln-checkbox">
                <?php $helper->row_input_checkbox(
                    'change_form_steps',
                    __('Change order', 'salon-booking-system'),
                    array('help' => __(
                                        'Selecting this option the booking process will follow this order: A - Services B - Assistants C - Date/Time',
                                        'salon-booking-system'
                                    )
                        )
                ); ?>
            </div>
        </div>
    </div>
</div>