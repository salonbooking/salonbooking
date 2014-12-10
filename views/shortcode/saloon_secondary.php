<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 */
?>
<h1>Something more?</h1>
<form method="post" action="<?php echo $formAction ?>">
    <?php foreach ($plugin->getServices() as $service) {
        if ($service->isSecondary()) {
            ?>
            <label>
                <?php SLN_Form::fieldCheckbox('services[' . $service->getId() . ']') ?>
                <strong><?php echo $service->getName(); ?></strong>
                <?php echo $service->getDuration()->format('H:i') ?>
                <?php echo number_format($service->getPrice()) . $plugin->getSettings()->getCurrencySymbol() ?>
                <br/>
                <?php echo $service->getContent() ?>
            </label><br/>
        <?php
        }
    } ?>
    <input type="submit" name="<?php echo $submitName ?>" value="Next"/>
</form>