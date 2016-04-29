<div class="row sln-summary">
    <div class="col-md-12">
        <div class="row sln-summary-row">
            <div class="col-sm-6 col-md-6 sln-data-desc">
                <?php
                $label = __('Date and time booked', 'salon-booking-system');
                $value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

                if(current_user_can('manage_options')) {
                    ?>
                    <span class="sln-edit-label-text"><?php echo $value; ?></span>
                    <input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
                    <?php
                } else {
                    ?>
                    <span><?php echo $value; ?></span>
                    <?php
                }
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
                    $label = __('Assistants', 'salon-booking-system');
                    $value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

                    if(current_user_can('manage_options')) {
                        ?>
                        <span class="sln-edit-label-text"><?php echo $value; ?></span>
                        <input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
                        <?php
                    } else {
                        ?>
                        <span><?php echo $value; ?></span>
                        <?php
                    }
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
                $label = __('Services booked', 'salon-booking-system');
                $value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

                if(current_user_can('manage_options')) {
                    ?>
                    <span class="sln-edit-label-text"><?php echo $value; ?></span>
                    <input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
                    <?php
                } else {
                    ?>
                    <span><?php echo $value; ?></span>
                    <?php
                }
                ?>
            </div>
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
        <?php
        $label = __('Do you have any message for us?', 'salon-booking-system');
        $value = SLN_Plugin::getInstance()->getSettings()->getCustomText($label);

        if(current_user_can('manage_options')) {
            ?>
            <label class="sln-edit-label-text"><?php echo $value; ?></label>
            <input class="sln-edit-text" id="<?php echo $label; ?>" value="<?php echo $value; ?>" />
            <?php
        } else {
            ?>
            <label><?php echo $value; ?></label>
            <?php
        }
        ?>
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
