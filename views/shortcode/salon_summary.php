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
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
?>
<form method="post" action="<?php echo $formAction ?>" role="form"  id="salon-step-summary">
<h2 class="salon-step-title"><?php _e('Booking summary', 'salon-booking-system') ?></h2>
<div class="row">
    <div class="col-md-8">
        <p class="sln-text--dark"><?php _e('Dear', 'salon-booking-system') ?>
            <strong><?php echo esc_attr($bb->get('firstname')) . ' ' . esc_attr($bb->get('lastname')); ?></strong>
            <br/>
            <?php _e('Here the details of your booking:', 'salon-booking-system') ?>
        </p>
    </div>
</div>
<?php
    if ($size == '900') { ?>
<div class="row sln-summary">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
            <div class="row sln-summary-row">
                <div class="col-sm-6 col-md-6 sln-data-desc"><span class="label"><?php _e('Date and time booked', 'salon-booking-system') ?></span></div>
                <div class="col-sm-6 col-md-6 sln-data-val">
                    <?php echo $plugin->format()->date($datetime); ?> / <?php echo $plugin->format()->time($datetime) ?>
                </div>
                <div class="col-sm-12 col-md-12"><hr></div>
            </div>
            <?php if($attendants = $bb->getAttendants()) :  ?>
            <div class="row sln-summary-row">
                <div class="col-sm-6 col-md-6 sln-data-desc"><span class="label"><?php _e('Assistants', 'salon-booking-system') ?></span></div>
                <div class="col-sm-6 col-md-6 sln-data-val"><?php $names = array(); foreach(array_unique($attendants) as $att) { $names[] = $att->getName(); } echo implode(', ', $names); ?></div>
                <div class="col-sm-12 col-md-12"><hr></div>
            </div>
            <?php // IF ASSISTANT
             endif ?>
             <div class="row sln-summary-row">
                <div class="col-sm-6 col-md-6 sln-data-desc"><span class="label"><?php _e('Services booked', 'salon-booking-system') ?></span></div>
                <div class="col-sm-6 col-md-6 sln-data-val">
                    <ul class="sln-list--dashed">
                <?php foreach ($bb->getServices() as $service): ?>
                    <li> <span class="service-label"><?php echo $service->getName(); ?></span>
                    <?php if($showPrices){?>
                    <small> (<?php echo $plugin->format()->money($service->getPrice()) ?>)</small>
                    <?php } ?>
                    </li>
                <?php endforeach ?>
            </ul>
                </div>
                <div class="col-sm-12 col-md-12"><hr></div>
            </div>
            </div>
        </div>
        <div class="row sln-total">
            <div class="col-md-12"><hr></div>
            <div class="col-md-12">
                <?php if($showPrices){?>
                <h3 class="col-xs-6 sln-total-label"><?php _e('Total amount', 'salon-booking-system') ?></h3>
                <h3 class="col-xs-6 sln-total-price"><?php echo $plugin->format()->money(
                        $plugin->getBookingBuilder()->getTotal()
                    ) ?> </h3>
                <?php }; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 sln-input sln-input--simple">
                <label><?php _e('Do you have any message for us?', 'salon-booking-system') ?></label>
                <?php SLN_Form::fieldTextarea(
                    'sln[note]',
                    $bb->get('note'),
                    array('attrs' => array('placeholder' => __('Leave a message', 'salon-booking-system')))
                ); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <p><strong><?php _e('Terms & conditions','salon-booking-system')?></strong></p>

            <p><?php echo $plugin->getSettings()->get('gen_timetable')
            /*_e(
                'In case of delay of arrival. we will wait a maximum of 10 minutes from booking time. Then we will release your reservation',
                'salon-booking-system'
            )*/ ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 sln-input sln-input--action">
        <?php $nextLabel = __('Finalise', 'salon-booking-system');
        include "_form_actions.php" ?>
    </div>
</div>
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
    <div class="row sln-summary">
    <div class="col-md-12">
    <div class="row sln-summary-row">
        <div class="col-sm-6 col-md-6 sln-data-desc"><?php _e('Date and time booked', 'salon-booking-system') ?></div>
        <div class="col-sm-6 col-md-6 sln-data-val">
            <?php echo $plugin->format()->date($datetime); ?> / <?php echo $plugin->format()->time($datetime) ?>
        </div>
        <div class="col-sm-12 col-md-12"><hr></div>
    </div>
    <?php if($attendants = $bb->getAttendants()) :  ?>
    <div class="row sln-summary-row">
        <div class="col-sm-6 col-md-6 sln-data-desc"><?php _e('Assistants', 'salon-booking-system') ?></div>
        <div class="col-sm-6 col-md-6 sln-data-val"><?php $names = array(); foreach(array_unique($attendants) as $att) { $names[] = $att->getName(); } echo implode(', ', $names); ?></div>
        <div class="col-sm-12 col-md-12"><hr></div>
    </div>
    <?php // IF ASSISTANT
     endif ?>
     <div class="row sln-summary-row">
        <div class="col-sm-6 col-md-6 sln-data-desc"><?php _e('Services booked', 'salon-booking-system') ?></div>
        <div class="col-sm-6 col-md-6 sln-data-val">
            <ul class="sln-list--dashed">
        <?php foreach ($bb->getServices() as $service): ?>
            <li><?php echo $service->getName(); ?>
            <?php if($showPrices){?>
            <small> (<?php echo $plugin->format()->money($service->getPrice()) ?>)</small>
            <?php } ?>
            </li>
        <?php endforeach ?>
    </ul>
        </div>
        <div class="col-sm-12 col-md-12"><hr></div>
    </div>
    </div>
    <div class="col-md-12 sln-total">
    <hr>
        <?php if($showPrices){?>
        <div class="row">
        <h3 class="col-xs-6 sln-total-label"><?php _e('Total amount', 'salon-booking-system') ?></h3>
        <h3 class="col-xs-6 sln-total-price"><?php echo $plugin->format()->money(
                $plugin->getBookingBuilder()->getTotal()
            ) ?> </h3>
        </div>
        <?php }; ?>
    </div>
    <div class="col-md-12 sln-input sln-input--simple">
        <label><?php _e('Do you have any message for us?', 'salon-booking-system') ?></label>
        <?php SLN_Form::fieldTextarea(
            'sln[note]',
            $bb->get('note'),
            array('attrs' => array('placeholder' => __('Leave a message', 'salon-booking-system')))
        ); ?>
    </div>
    <div class="col-md-12">
    <p><strong><?php _e('Terms & conditions','salon-booking-system')?></strong></p>

    <p><?php echo $plugin->getSettings()->get('gen_timetable')
    /*_e(
        'In case of delay of arrival. we will wait a maximum of 10 minutes from booking time. Then we will release your reservation',
        'salon-booking-system'
    )*/ ?></p>
    </div>
</div>
<div class="row sln-box--main sln-box--formactions">
    <div class="col-md-12">
    <label for="login_name">&nbsp;</label>
        <?php $nextLabel = __('Finalise', 'salon-booking-system');
        include "_form_actions.php" ?>
    </div>
</div>

    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
    <div class="row sln-summary">
    <div class="col-md-12">
    <div class="row sln-summary-row">
        <div class="col-xs-12 sln-data-desc"><span class="label"><?php _e('Date and time booked', 'salon-booking-system') ?></span></div>
        <div class="col-xs-12 sln-data-val">
            <?php echo $plugin->format()->date($datetime); ?> / <?php echo $plugin->format()->time($datetime) ?>
        </div>
        <div class="col-xs-12"><hr></div>
    </div>
    <?php if($attendants = $bb->getAttendants()) :  ?>
    <div class="row sln-summary-row">
        <div class="col-xs-12 sln-data-desc"><span class="label"><?php _e('Assistants', 'salon-booking-system') ?></span></div>
        <div class="col-xs-12 sln-data-val"><?php $names = array(); foreach(array_unique($attendants) as $att) { $names[] = $att->getName(); } echo implode(', ', $names); ?></div>
        <div class="col-xs-12"><hr></div>
    </div>
    <?php // IF ASSISTANT // END
     endif ?>
     <div class="row sln-summary-row">
        <div class="col-xs-12 sln-data-desc"><span class="label"><?php _e('Services booked', 'salon-booking-system') ?></span></div>
        <div class="col-xs-12 sln-data-val">
            <ul class="sln-list--dashed">
        <?php foreach ($bb->getServices() as $service): ?>
            <li> <span class="service-label"><?php echo $service->getName(); ?></span>
            <?php if($showPrices){?>
            <small> (<?php echo $plugin->format()->money($service->getPrice()) ?>)</small>
            <?php } ?>
            </li>
        <?php endforeach ?>
    </ul>
        </div>
        <div class="col-md-12"><hr></div>
    </div>
    </div>
    <div class="col-md-12 sln-total">
    <hr>
        <?php if($showPrices){?>
        <h3 class="col-xs-6 sln-total-label"><?php _e('Total amount', 'salon-booking-system') ?></h3>
        <h3 class="col-xs-6 sln-total-price"><?php echo $plugin->format()->money(
                $plugin->getBookingBuilder()->getTotal()
            ) ?> </h3>
        <?php }; ?>
    </div>
    <div class="col-md-12 sln-input sln-input--simple">
        <label><?php _e('Do you have any message for us?', 'salon-booking-system') ?></label>
        <?php SLN_Form::fieldTextarea(
            'sln[note]',
            $bb->get('note'),
            array('attrs' => array('placeholder' => __('Leave a message', 'salon-booking-system')))
        ); ?>
    </div>
    <div class="col-md-12">
    <p><strong><?php _e('Terms & conditions','salon-booking-system')?></strong></p>

    <p><?php echo $plugin->getSettings()->get('gen_timetable')
    /*_e(
        'In case of delay of arrival. we will wait a maximum of 10 minutes from booking time. Then we will release your reservation',
        'salon-booking-system'
    )*/ ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 sln-input sln-input--action">
        <label for="login_name">&nbsp;</label>
        <?php $nextLabel = __('Finalise', 'salon-booking-system');
        include "_form_actions.php" ?>
    </div>
</div>
    <?php
    // IF SIZE 400 // END
    } else  { ?>

    <?php
    // ELSE // END
    }  ?>
</form>
