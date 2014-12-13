<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Saloon_ServicesStep $step
 */
$bb = $plugin->getBookingBuilder();
$currencySymbol = $plugin->getSettings()->getCurrencySymbol();
?>
<h1>Something more?</h1>
<form method="post" action="<?php echo $formAction ?>" role="form">
    <?php foreach($step->getServices() as $service) : ?>
        <label>
            <?php SLN_Form::fieldCheckbox(
                'sln[services][' . $service->getId() . ']',
                $bb->hasService($service)
            ) ?>
            <strong><?php echo $service->getName(); ?></strong>
            <?php echo $service->getDuration()->format('H:i') ?>
            <?php echo $service->getPrice() ? (number_format($service->getPrice(),2) . $currencySymbol) : 'free' ?>
            <br/>
            <?php echo $service->getContent() ?>
        </label><br/>
    <?php endforeach ?>
    <button type="submit" class="btn btn-success" name="<?php echo $submitName ?>" value="next">Next</button>

    <a class="btn btn-danger" href="<?php echo $backUrl ?> ">Back</a>
</form>