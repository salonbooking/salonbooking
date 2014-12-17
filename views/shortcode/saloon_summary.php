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
<h2><?php _e('Summary', 'sln') ?></h2>
<form method="post" action="<?php echo $formAction ?>" role="form">
    <?php _e('Dear', 'sln') ?> <?php echo esc_attr($bb->get('firstname')) . ' ' . esc_attr($bb->get('lastname')); ?>
    <br/>
    <?php _e('You have booked:', 'sln') ?>
    <?php foreach ($bb->getServices() as $service): ?>
        <div class="row">
            <div class="col-md-1"></div>
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

    <h2><?php _e('Total:', 'sln') ?> <?php echo $plugin->format()->money(
            $plugin->getBookingBuilder()->getTotal()
        ) ?></h2>
    <em>for <?php echo $plugin->format()->datetime($bb->getDateTime()); ?></em>

    <div class="form-group">
        <label><?php _e('Do you have a message for us?', 'sln') ?></label>
        <?php SLN_Form::fieldTextarea(
            'sln[note]',
            $bb->get('note'),
            array('attrs' => array('placeholder' => __('Leave a message', 'sln')))
        ); ?>
    </div>
    <?php $nextLabel = __('Finalize', 'sln');
    include "_form_actions.php" ?>
</form>