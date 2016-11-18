<?php
/**
 * @var $helper SLN_Admin_Settings
 */
?>
<h2 class="sln-box-title"><?php _e('Booking manual confirmation', 'salon-booking-system'); ?></h2>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-checkbox sln-moremargin--bottom">
        <?php $helper->row_input_checkbox(
            'confirmation',
            __('Booking confirmation', 'salon-booking-system'),
            array('help' => '')
        ); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 sln-box-maininfo  align-top">
        <p class="sln-box-info"><?php _e('Select this option to manually confirm each booking.', 'salon-booking-system');?></p>
    </div>
    </div>
    <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
        <?php $helper->row_input_page('pay', __('Booking page', 'salon-booking-system')); ?>
        <p class="help-block"><?php _e('Select a page with the booking form.', 'salon-booking-system') ?></p>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
        <?php $helper->row_input_page('thankyou', __('Thank you page', 'salon-booking-system')); ?>
        <p class="help-block"><?php _e(
                'Select a page where to redirect your users after booking completition.',
                'salon-booking-system'
            ) ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4 form-group sln-select ">
        <?php $helper->row_input_page('bookingmyaccount', __('Booking My Account', 'salon-booking-system')); ?>
        <p class="help-block"><?php _e(
                'Select a page where your users view their bookings.',
                'salon-booking-system'
            ) ?></p>
    </div>
</div>
