<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Saloon_ServicesStep $step
 * @var SLN_Wrapper_Service[]             $services
 */
?>
<div class="sln-service-list">
    <?php foreach ($services as $service) : ?>
        <div class="row">
            <div class="col-md-1">
            <span class="service-checkbox">
            <?php SLN_Form::fieldCheckbox(
                'sln[services][' . $service->getId() . ']',
                $bb->hasService($service),
                array('attrs' => array('data-price' => $service->getPrice()))
            ) ?>
            </span>
            </div>
            <div class="col-md-8">
                <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                    <strong class="service-name"><?php echo $service->getName(); ?></strong>
                    <span class="service-description"><?php echo $service->getContent() ?></span>
                    <span class="service-duration">Duration: <?php echo $service->getDuration()->format('H:i') ?></span>
                </label>
            </div>
            <div class="col-md-3">
                <?php echo $plugin->format()->money($service->getPrice()) ?>
            </div>
        </div>
    <?php endforeach ?>
    <div class="sln-separator"></div>
    <div class="row row-total">
        <div class="col-md-9 text-right">sub-total:</div>
        <div class="col-md-3 services-total">
        <span id="services-total" data-symbol="<?php echo $plugin->getSettings()->getCurrencySymbol() ?>">
            <?php echo $plugin->format()->money(0, false) ?>
        </span>
        </div>
    </div>
</div>