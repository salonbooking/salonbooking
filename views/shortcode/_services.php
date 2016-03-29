<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_ServicesStep $step
 * @var SLN_Wrapper_Service[]             $services
 */

$ah = $plugin->getAvailabilityHelper();
$bb = $plugin->getBookingBuilder();
$ah->setDate($bb->getDateTime());
$isSymbolLeft = $plugin->getSettings()->get('pay_currency_pos') == 'left';
$symbolLeft = $isSymbolLeft ? $plugin->getSettings()->getCurrencySymbol() : '';
$symbolRight = $isSymbolLeft ? '' : $plugin->getSettings()->getCurrencySymbol();
$showPrices = ($plugin->getSettings()->get('hide_prices') != '1')? true : false;
$grouped = SLN_Func::groupServicesByCategory($services);
$minutes = $ah->getFreeMinutes($bb->getDateTime()) - $bb->getServicesDurationMinutes();
 ?>
<div class="sln-service-list">
    <?php foreach ($grouped as $group): ?>
        <?php if($group['term'] !== false): ?>
        <div class="panel panel-salon">
            <h3 class="panel-heading"><a class="collapsed" role="button" data-toggle="collapse" href="#collapse<?php echo $group['term']->slug ?>" aria-expanded="false" aria-controls="collapse<?php echo $group['term']->slug ?>">
            <?php echo $group['term']->name ?>
            <span class="icon icon-plus"></span></a></h3>
        <div id="collapse<?php echo $group['term']->slug ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse<?php echo $group['term']->slug ?>Heading" aria-expanded="false" style="height: 0px;">

        <?php endif ?>
    <?php foreach ($group['services'] as $service) : ?>
         <?php
    $size = $_SESSION["size"];
    if ($size == '900') { ?>
    <div class="row sln-service">
        <div class="col-md-1 sln-checkbox sln-steps-check sln-service-check">
            <span class="service-checkbox <?php echo  $bb->hasService($service) ? 'is-checked' : '' ?>">
            <?php
            $serviceErrors   = $ah->validateService($service);
            $settings = array('attrs' => array(
                'data-price' => $service->getPrice(),
                'data-duration' => SLN_Func::getMinutesFromDuration($service->getDuration())
            ));
            if ($serviceErrors) {
                $settings['attrs']['disabled'] = 'disabled';
            }
            ?>

            <?php SLN_Form::fieldCheckbox(
                'sln[services][' . $service->getId() . ']',
                $bb->hasService($service),
                $settings
            ) ?>
            </span>
        <!-- .sln-service-check // END -->
        </div>
        <div class="col-md-11">
            <div class="row sln-steps-info sln-service-info">
                <div class="col-md-9">
                    <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                        <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName(); ?></h3>
                    </label>
                <!-- .sln-service-info // END -->
                </div>
                <h3 class="col-md-3  sln-steps-price  sln-service-price">
                    <?php echo $plugin->format()->money($service->getPrice())?>
                <!-- .sln-service-price // END -->
                </h3>
            </div>
            <div class="row sln-steps-description sln-service-description">
                    <div class="col-md-9">
                        <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                            <p><?php echo $service->getContent() ?></p>
                            <?php if ($service->getDuration()->format('H:i') != '00:00'): ?>
                                <span class="sln-steps-durationsln-service-duration"><?php echo __('Duration', 'salon-booking-system')?>: <?php echo $service->getDuration()->format(
                                        'H:i'
                                    ) ?></span>
                            <?php endif ?>
                        </label>
                    <!-- .sln-service-info // END -->
                    </div>
            </div>
        </div>
        <?php if ($serviceErrors) : ?>
            <div class="col-md-12 alert alert-warning">
                <?php foreach ($serviceErrors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
   <div class="row sln-service">
        <div class="col-md-12">
            <div class="row sln-steps-info sln-service-info">
                <div class="col-md-1 sln-checkbox sln-steps-check sln-service-check">
                    <span class="service-checkbox <?php echo  $bb->hasService($service) ? 'is-checked' : '' ?>">
                    <?php
                    $serviceErrors   = $ah->validateService($service);
                    $settings = array('attrs' => array(
                        'data-price' => $service->getPrice(),
                        'data-duration' => SLN_Func::getMinutesFromDuration($service->getDuration())
                    ));
                    if ($serviceErrors) {
                        $settings['attrs']['disabled'] = 'disabled';
                    }
                    ?>

                    <?php SLN_Form::fieldCheckbox(
                        'sln[services][' . $service->getId() . ']',
                        $bb->hasService($service),
                        $settings
                    ) ?>
                    </span>
                <!-- .sln-service-check // END -->
                </div>
                <div class="col-md-8">
                    <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                        <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName(); ?></h3>
                    </label>
                <!-- .sln-service-info // END -->
                </div>
                <h3 class="col-md-3 sln-steps-price sln-service-price">
                    <?php echo $plugin->format()->money($service->getPrice())?>
                <!-- .sln-service-price // END -->
                </h3>
            </div>
            <div class="row sln-steps-description sln-service-description">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-9">
                        <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                            <p><?php echo $service->getContent() ?></p>
                            <?php if ($service->getDuration()->format('H:i') != '00:00'): ?>
                                <span class="sln-steps-duration sln-service-duration"><?php echo __('Duration', 'salon-booking-system')?>: <?php echo $service->getDuration()->format(
                                        'H:i'
                                    ) ?></span>
                            <?php endif ?>
                        </label>
                    <!-- .sln-service-info // END -->
                    </div>
            </div>
        </div>
        <?php if ($serviceErrors) : ?>
            <div class="col-md-12 alert alert-warning">
                <?php foreach ($serviceErrors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>

    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
    <div class="row sln-service">
        <div class="col-md-12">
            <div class="row sln-steps-info sln-service-info">
                <div class="col-md-1 sln-checkbox sln-steps-check sln-service-check">
                    <span class="service-checkbox <?php echo  $bb->hasService($service) ? 'is-checked' : '' ?>">
                    <?php
                    $serviceErrors   = $ah->validateService($service);
                    $settings = array('attrs' => array(
                        'data-price' => $service->getPrice(),
                        'data-duration' => SLN_Func::getMinutesFromDuration($service->getDuration())
                    ));
                    if ($serviceErrors) {
                        $settings['attrs']['disabled'] = 'disabled';
                    }
                    ?>

                    <?php SLN_Form::fieldCheckbox(
                        'sln[services][' . $service->getId() . ']',
                        $bb->hasService($service),
                        $settings
                    ) ?>
                    </span>
                <!-- .sln-service-check // END -->
                </div>
                <div class="col-md-8">
                    <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                        <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName(); ?></h3>
                    </label>
                <!-- .sln-service-info // END -->
                </div>
                <h3 class="col-md-3 sln-steps-price sln-service-price">
                    <?php echo $plugin->format()->money($service->getPrice())?>
                <!-- .sln-service-price // END -->
                </h3>
            </div>
            <div class="row sln-steps-description sln-service-description">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-9">
                        <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                            <p><?php echo $service->getContent() ?></p>
                            <?php if ($service->getDuration()->format('H:i') != '00:00'): ?>
                                <span class="sln-steps-duration sln-service-duration"><?php echo __('Duration', 'salon-booking-system')?>: <?php echo $service->getDuration()->format(
                                        'H:i'
                                    ) ?></span>
                            <?php endif ?>
                        </label>
                    <!-- .sln-service-info // END -->
                    </div>
            </div>
        </div>
        <?php if ($serviceErrors) : ?>
            <div class="col-md-12 alert alert-warning">
                <?php foreach ($serviceErrors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
    <?php
    // IF SIZE 400 // END
    } else  { ?>

    <?php
    // ELSE // END
    }  ?>
        
    <?php endforeach ?>
    <?php if($group['term'] !== false): ?>
    <!-- panel END -->
    </div>
    </div>
    <!-- panel END -->
    <?php endif ?>
    <?php endforeach ?>
    <div class="alert alert-danger" style="display: none" id="availabilityerror"><p><?php echo __('not enough time for this service','salon-booking-system') ?></p></div>
	<?php if ($showPrices){ ?>
    <div class="row sln-total">
    <?php
    $size = $_SESSION["size"];
    if ($size == '900') { ?>
        <div class="col-md-1">&nbsp;</div>
        <h3 class="col-xs-5 sln-total-label">
            <?php _e('Subtotal', 'salon-booking-system') ?>
        </h3>
        <h3 class="col-xs-6 sln-total-price" id="services-total" 
              data-minutes="<?php echo $minutes ?>"
              data-symbol-left="<?php echo $symbolLeft ?>"
              data-symbol-right="<?php echo $symbolRight ?>">
            <?php echo $plugin->format()->money(0, false) ?>
        </h3>
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
        <h3 class="col-xs-6 sln-total-label">
            <?php _e('Subtotal', 'salon-booking-system') ?>
        </h3>
        <h3 class="col-xs-6 sln-total-price" id="services-total" 
              data-minutes="<?php echo $minutes ?>"
              data-symbol-left="<?php echo $symbolLeft ?>"
              data-symbol-right="<?php echo $symbolRight ?>">
            <?php echo $plugin->format()->money(0, false) ?>
        </h3>
    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
        <h3 class="col-xs-6 sln-total-label">
            <?php _e('Subtotal', 'salon-booking-system') ?>
        </h3>
        <h3 class="col-xs-6 sln-total-price" id="services-total" 
              data-minutes="<?php echo $minutes ?>"
              data-symbol-left="<?php echo $symbolLeft ?>"
              data-symbol-right="<?php echo $symbolRight ?>">
            <?php echo $plugin->format()->money(0, false) ?>
        </h3>
    <?php
    // IF SIZE 400 // END
    } else  { ?>
    <div class="col-md-1">&nbsp;</div>
        <h3 class="col-xs-5 sln-total-label">
            <?php _e('Subtotal', 'salon-booking-system') ?>
        </h3>
        <h3 class="col-xs-6 sln-total-price" id="services-total" 
              data-minutes="<?php echo $minutes ?>"
              data-symbol-left="<?php echo $symbolLeft ?>"
              data-symbol-right="<?php echo $symbolRight ?>">
            <?php echo $plugin->format()->money(0, false) ?>
        </h3>
    <?php
    // ELSE // END
    }
?>
    </div>
	<?php } ?>
</div>
