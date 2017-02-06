<?php
/**
 * 
 */
?>
<div class="row sln-summary">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <div class="row sln-summary-row">
                    <div class="col-sm-6 col-md-6 sln-data-desc">
                        <?php
                        $args = array(
                            'label'        => __('Date and time booked', 'salon-booking-system'),
                            'tag'          => 'span',
                            'textClasses'  => 'text-min label',
                            'inputClasses' => 'input-min',
                            'tagClasses'   => 'label',
                        );
                        echo $plugin->loadView('shortcode/_editable_snippet', $args);
                        ?>
                    </div>
                    <div class="col-sm-6 col-md-6 sln-data-val">
                        <?php echo $plugin->format()->date($datetime); ?> / <?php echo $plugin->format()->time($datetime) ?>
                    </div>
                    <div class="col-sm-12 col-md-12"><hr></div>
                </div>
                <?php if($attendants = $bb->getAttendants()) :  ?>
                    <div class="row sln-summary-row">
                        <div class="col-sm-6 col-md-6 sln-data-desc">
                            <?php
                            $args = array(
                                'label'        => __('Assistants', 'salon-booking-system'),
                                'tag'          => 'span',
                                'textClasses'  => 'text-min label',
                                'inputClasses' => 'input-min',
                                'tagClasses'   => 'label',
                            );
                            echo $plugin->loadView('shortcode/_editable_snippet', $args);
                            ?>
                        </div>
                        <div class="col-sm-6 col-md-6 sln-data-val"><?php $names = array(); foreach(array_unique($attendants) as $att) { $names[] = $att->getName(); } echo implode(', ', $names); ?></div>
                        <div class="col-sm-12 col-md-12"><hr></div>
                    </div>
                <?php // IF ASSISTANT
                endif ?>
                <div class="row sln-summary-row">
                    <div class="col-sm-6 col-md-6 sln-data-desc">
                        <?php
                        $args = array(
                            'label'        => __('Services booked', 'salon-booking-system'),
                            'tag'          => 'span',
                            'textClasses'  => 'text-min label',
                            'inputClasses' => 'input-min',
                            'tagClasses'   => 'label',
                        );
                        echo $plugin->loadView('shortcode/_editable_snippet', $args);
                        ?>
                    </div>
                    <div class="col-sm-6 col-md-6 sln-data-val">
                        <ul class="sln-list--dashed">
                            <?php foreach ($bb->getServices() as $service): ?>
                                <li> <span class="service-label"><?php echo $service->getName(); ?></span>
                                    <?php if($showPrices){?>
                                        <small> (<?php echo $plugin->format()->moneyFormatted($service->getPrice()) ?>)</small>
                                    <?php } ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="col-sm-12 col-md-12"><hr></div>
                </div>
                <?php do_action('sln.template.summary.before_total_amount', $bb, $size); ?>
            </div>
        </div>
        <div class="row sln-total">
            <div class="col-md-12"><hr></div>
            <div class="col-md-12">
                <?php if($showPrices){?>
                    <h3 class="col-xs-6 sln-total-label"><?php _e('Total amount', 'salon-booking-system') ?></h3>
                    <h3 class="col-xs-6 sln-total-price"><?php echo $plugin->format()->moneyFormatted(
                            $plugin->getBookingBuilder()->getTotal()
                        ) ?> </h3>
                <?php }; ?>
            </div>
        </div>
        <?php do_action('sln.template.summary.after_total_amount', $bb, $size); ?>
        <div class="row">
            <div class="col-md-12 sln-input sln-input--simple">
                <?php
                $args = array(
                    'label'        => __('Do you have any message for us?', 'salon-booking-system'),
                    'tag'          => 'label',
                    'textClasses'  => '',
                    'inputClasses' => '',
                    'tagClasses'   => '',
                );
                echo $plugin->loadView('shortcode/_editable_snippet', $args);
                ?>
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