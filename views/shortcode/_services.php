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
$bookingOffsetEnabled = boolval(SLN_Plugin::getInstance()->getSettings()->get('reservation_interval_enabled'));
$bookingOffset = intval(SLN_Plugin::getInstance()->getSettings()->get('minutes_between_reservation'));
$endsAt = $bb->getEndsAt();
//if ($bookingOffsetEnabled) {
//    $endsAt = $endsAt->modify('+'.$bookingOffset.' minutes');
//}
$ah->setDate($bb->getDateTime());
$isSymbolLeft = $plugin->getSettings()->get('pay_currency_pos') == 'left';
$symbolLeft = $isSymbolLeft ? $plugin->getSettings()->getCurrencySymbol() : '';
$symbolRight = $isSymbolLeft ? '' : $plugin->getSettings()->getCurrencySymbol();
$showPrices = ($plugin->getSettings()->get('hide_prices') != '1')? true : false;
$grouped = SLN_Func::groupServicesByCategory($services);

$servicesErrors = $ah->checkEachOfNewServicesForExistOrder($bb->getServicesIds(), $services);

 ?>
<?php SLN_Form::fieldText(
    'sln[date]',
    $bb->getDate(),
    array('type' => 'hidden')
) ?>
<?php SLN_Form::fieldText(
    'sln[time]',
    $bb->getTime(),
    array('type' => 'hidden')
) ?>
<div class="sln-service-list">
    <?php foreach ($grouped as $group): ?>
        <?php if($group['term'] !== false): ?>
        <div class="panel panel-salon">
            <h3 class="panel-heading"><a class="collapsed" role="button" data-toggle="collapse" href="#collapse<?php echo $group['term']->slug ?>" aria-expanded="false" aria-controls="collapse<?php echo $group['term']->slug ?>">
            <?php echo $group['term']->name ?>
            <span class="icon icon-plus"></span></a></h3>
        <div id="collapse<?php echo $group['term']->slug ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse<?php echo $group['term']->slug ?>Heading" aria-expanded="false" style="height: 0px;">

        <?php endif ?>
    <?php /** @var SLN_Wrapper_Service $service */
        foreach ( $group['services'] as $service) : ?>
        <div class="row">
            <div class="col-xs-1 col-lg-1">
            <span class="service-checkbox <?php echo  $bb->hasService($service) ? 'is-checked' : '' ?>">
            <?php
            $serviceErrors = isset($servicesErrors[$service->getId()]) ? $servicesErrors[$service->getId()] : array();
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
            </div>
            <div class="col-lg-<?php echo $showPrices?'8':'11'?> col-xs-<?php echo $showPrices?'8':'11'?>">
                <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                    <strong class="service-name"><?php echo $service->getName(); ?></strong>
                    <span class="service-description"><?php echo $service->getContent() ?></span>
                    <?php if ($service->getDuration()->format('H:i') != '00:00'): ?>
                        <span class="service-duration"><?php echo __('Duration', 'salon-booking-system')?>: <?php echo $service->getDuration()->format(
                                'H:i'
                            ) ?></span>
                    <?php endif ?>
                </label>
            </div>
		<?php if ($showPrices){  ?>
            <div class="col-lg-3 col-xs-3 service-price">
                <?php echo $plugin->format()->money($service->getPrice())?>
            </div>
		<?php }	?>
        </div>
        <div class="clearfix"></div>
        <?php if ($serviceErrors) : ?>
            <div class="alert alert-warning">
                <?php foreach ($serviceErrors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
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
    <div class="row row-total">
        <div class="col-xs-6 services-total-label"><?php _e('Subtotal', 'salon-booking-system') ?></div>
        <div class="col-xs-6 services-total">
        <span id="services-total" 
              data-symbol-left="<?php echo $symbolLeft ?>"
              data-symbol-right="<?php echo $symbolRight ?>">
            <?php echo $plugin->format()->money(0, false) ?>
        </span>
        </div>
    </div>
	<?php } ?>
</div>
