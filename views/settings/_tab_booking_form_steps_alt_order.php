<?php
/**
 * @var $plugin SLN_Plugin
 * @var $helper SLN_Admin_Settings
 */
?>
<div class="col-xs-12">
    <div class="sln-box sln-box--main sln-box--main--small">
        <h2 class="sln-box-title"><?php _e('Change booking form steps order','salon-booking-system');?></h2>
        <div class="row">
            <div class="col-xs-12 col-sm-4 form-group sln-checkbox">
                <?php $helper->row_input_checkbox(
                    'form_steps_alt_order',
                    __('Change order', 'salon-booking-system'),
                    array('help' => '')
                ); ?>
            </div>
            <div class="col-xs-12 col-sm-4 form-group sln-box-maininfo align-top">
                <p class="sln-box-info"><?php _e('Selecting this option the booking process will follow this order: A - Services B - Assistants C - Date/Time',
                                        'salon-booking-system','salon-booking-system') ?></p>
            </div>
        </div>
    </div>
</div>
