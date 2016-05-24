<?php
/**
 * @var SLN_Plugin $plugin
 * @var string $formAction
 * @var string $submitName
 * @var SLN_Shortcode_Salon_ServicesStep $step
 * @var SLN_Wrapper_Service[] $services
 */

$ah = $plugin->getAvailabilityHelper();
$bb = $plugin->getBookingBuilder();
$ah->setDate($bb->getDateTime());
$isSymbolLeft = $plugin->getSettings()->get('pay_currency_pos') == 'left';
$symbolLeft = $isSymbolLeft ? $plugin->getSettings()->getCurrencySymbol() : '';
$symbolRight = $isSymbolLeft ? '' : $plugin->getSettings()->getCurrencySymbol();
$showPrices = ($plugin->getSettings()->get('hide_prices') != '1') ? true : false;
$grouped = SLN_Func::groupServicesByCategory($services);

if ( $plugin->getSettings()->isChangeFormSteps() ) {

    $servicesErrors = array();
}
else {
    $servicesErrors = $ah->checkEachOfNewServicesForExistOrder($bb->getServicesIds(), $services);
} 
    
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
?>
<?php SLN_Form::fieldText('sln[date]', $bb->getDate(), array('type' => 'hidden')) ?>
<?php SLN_Form::fieldText('sln[time]', $bb->getTime(), array('type' => 'hidden')) ?>
<div class="sln-service-list">
    <?php foreach ($grouped as $group): ?>
        <?php if ($group['term'] !== false): ?>
            <div class="row sln-panel">
            <a class="col-xs-12 sln-panel-heading collapsed" role="button"
               data-toggle="collapse" href="#collapse<?php echo $group['term']->slug ?>"
               aria-expanded="false" aria-controls="collapse<?php echo $group['term']->slug ?>">
                <h2 class="sln-btn sln-btn--nobkg sln-btn--noheight sln-btn--icon sln-btn--icon--left sln-btn--fullwidth">
                    <?php echo $group['term']->name ?></h2>
            </a>
            <div id="collapse<?php echo $group['term']->slug ?>"
            class="col-xs-12 sln-panel-content panel-collapse collapse" role="tabpanel"
            aria-labelledby="collapse<?php echo $group['term']->slug ?>Heading"
            aria-expanded="false" style="height: 0px;">
        <?php endif ?>
        <?php foreach ($group['services'] as $service) {
            $serviceErrors = isset($servicesErrors[$service->getId()]) ? $servicesErrors[$service->getId()] : array();
            $settings = array(
                'attrs' => array(
                    'data-price' => $service->getPrice(),
                    'data-duration' => SLN_Func::getMinutesFromDuration($service->getDuration()),
                ),
            );
            if ($serviceErrors) {
                $settings['attrs']['disabled'] = 'disabled';
            }
            if ($size == '900') {
                include '_services_item_900.php';
            } elseif ($size == '600') {
                include '_services_item_600.php';
            } elseif ($size == '400') {
                include '_services_item_400.php';
            } else {
                throw new Exception('size not supported');
            }
        } ?>
        <?php if ($group['term'] !== false): ?>
            <!-- panel END -->
            </div>
            </div>
            <!-- panel END -->
        <?php endif ?>
    <?php endforeach ?>
    <!-- .sln-service-list // END -->
</div>
<?php if ($showPrices) { ?>
    <div class="row sln-total">
        <div class="col-md-12">
            <hr>
        </div>
        <?php if ($size == '900'): ?>
            <h3 class="col-xs-6 col-sm-6 col-md-6 sln-total-label">
                <?php _e('Subtotal', 'salon-booking-system') ?>
            </h3>
            <h3 class="col-xs-6 col-sm-6 col-md-6 sln-total-price" id="services-total"
                data-minutes="<?php echo $minutes ?>"
                data-symbol-left="<?php echo $symbolLeft ?>"
                data-symbol-right="<?php echo $symbolRight ?>">
                <?php echo $plugin->format()->money(0, false) ?>
            </h3>
        <?php elseif ($size == '600'): ?>
            <h3 class="col-xs-6 sln-total-label">
                <?php _e('Subtotal', 'salon-booking-system') ?>
            </h3>
            <h3 class="col-xs-6 sln-total-price" id="services-total"
                data-minutes="<?php echo $minutes ?>"
                data-symbol-left="<?php echo $symbolLeft ?>"
                data-symbol-right="<?php echo $symbolRight ?>">
                <?php echo $plugin->format()->money(0, false) ?>
            </h3>
        <?php elseif ($size == '400'): ?>
            <h3 class="col-xs-6 sln-total-label">
                <?php _e('Subtotal', 'salon-booking-system') ?>
            </h3>
            <h3 class="col-xs-6 sln-total-price" id="services-total"
                data-minutes="<?php echo $minutes ?>"
                data-symbol-left="<?php echo $symbolLeft ?>"
                data-symbol-right="<?php echo $symbolRight ?>">
                <?php echo $plugin->format()->money(0, false) ?>
            </h3>
        <?php else: throw new Exception('size not supported'); ?>
        <?php endif ?>
    </div>
<?php } ?>
