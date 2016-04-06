<?php
/**
 * @var SLN_Plugin                $plugin
 * @var string                    $formAction
 * @var string                    $submitName
 * @var SLN_Shortcode_Salon_Step $step
 */
$bb             = $plugin->getBookingBuilder();
$currencySymbol = $plugin->getSettings()->getCurrencySymbol();
$datetime       = $bb->getDateTime();
$confirmation = $plugin->getSettings()->get('confirmation'); 
$showPrices = ($plugin->getSettings()->get('hide_prices') != '1')? true : false;
?>
<h2><?php _e('Booking summary', 'salon-booking-system') ?></h2>
<form method="post" action="<?php echo $formAction ?>" role="form"  id="salon-step-summary">
    <p class="dear"><?php _e('Dear', 'salon-booking-system') ?>
        <strong><?php echo esc_attr($bb->get('firstname')) . ' ' . esc_attr($bb->get('lastname')); ?></strong>
        <br/>
        <?php _e('Here the details of your booking:', 'salon-booking-system') ?>
    </p>

    <div class="row summ-row">
        <div class="col-md-5"><span class="label"><?php _e('Date and time booked', 'salon-booking-system') ?></span></div>
        <div class="col-md-7"><p class="date"><strong><?php echo $plugin->format()->date($datetime); ?></strong><br/>
            <span class="time"><?php echo $plugin->format()->time($datetime) ?></span></p>
        </div>
    </div>

    <?php if($attendants = $bb->getAttendants()) :  ?>
    <div class="row summ-row">
        <div class="col-md-5"><span class="label"><?php _e('Assistants', 'salon-booking-system') ?></span></div>
        <div class="col-md-7">
            <span class="attendant-label"><?php $names = array(); foreach(array_unique($attendants) as $att) { $names[] = $att->getName(); } echo implode(', ', $names); ?></span></li>
        </div>
    </div>
    <?php endif ?>
    <div class="row summ-row">
        <div class="col-md-5"><span class="label"><?php _e('Services booked', 'salon-booking-system') ?></span></div>
        <div class="col-md-7">
            <ul class="list-unstyled">
                <?php foreach ($bb->getServices() as $service): ?>
                    <li> <span class="service-label"><?php echo $service->getName(); ?>
                    <?php if($showPrices){?>
					<span class="service-price"><?php echo $plugin->format()->money($service->getPrice()) ?>
					<?php } ?>
					</li>
                <?php endforeach ?>
                <?php if($showPrices){?>
				<li><span class="total-label"><?php _e('Total amount', 'salon-booking-system') ?></span>
                <span class="total-price"><?php echo $plugin->format()->money(
                        $plugin->getBookingBuilder()->getTotal()
                    ) ?></span></li>
				<?php } ?>
            </ul>
        </div>
    </div>

    <br/>
    <div class="row">
    <div class="form-group">
        <label><?php _e('Do you have any message for us?', 'salon-booking-system') ?></label>
        <?php SLN_Form::fieldTextarea(
            'sln[note]',
            $bb->get('note'),
            array('attrs' => array('placeholder' => __('Leave a message', 'salon-booking-system')))
        ); ?>
        
    
    <div class="alert ty">
            <strong><?php _e('Terms & conditions','salon-booking-system')?></strong><br />

            <?php echo $plugin->getSettings()->get('gen_timetable') 
            /*_e(
                'In case of delay of arrival. we will wait a maximum of 10 minutes from booking time. Then we will release your reservation',
                'salon-booking-system'
            )*/ ?>
    </div>
    
        
        
    </div>
    </div>
    <?php $nextLabel = __('Finalise', 'salon-booking-system');
    include "_form_actions.php" ?>
</form>
