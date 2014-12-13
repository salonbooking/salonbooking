<?php
/**
 * @var SLN_Plugin                $plugin
 * @var string                    $formAction
 * @var string                    $submitName
 * @var SLN_Shortcode_Saloon_Step $step
 */
$bb             = $plugin->getBookingBuilder();
$currencySymbol = $plugin->getSettings()->getCurrencySymbol();
?>
<h1>Summary</h1>
<form method="post" action="<?php echo $formAction ?>" role="form">
    Dear <?php $bb->get('firstname') . ' ' . $bb->get('lastname'); ?><br/>
    You have booked:
    <ul>
        <?php foreach ($bb->getServices() as $service): ?>
            <li>
                <?php echo $service->getName() ?> -
                <?php echo $service->getPrice() ? (number_format($service->getPrice(),2) . $currencySymbol) : 'free' ?>
            </li>
        <?php endforeach ?>
    </ul>
    <h2>Total: <?php echo number_format($service->getPrice(), 2) . $currencySymbol ?></h2>
    <em>for <?php echo date_i18n(__('M j, Y @ G:i', 'sln'), strtotime($bb->getDate() . ' ' . $bb->getTime())); ?></em>
    <div class="form-group">
        <label>Do you have a message for us?</label>
        <?php SLN_Form::fieldTextarea('sln[note]', $bb->get('note')); ?>
    </div>
    <button type="submit" class="btn btn-success" name="<?php echo $submitName ?>" value="next">End</button>
    <a class="btn btn-danger" href="<?php echo $backUrl ?> ">Back</a>
</form>