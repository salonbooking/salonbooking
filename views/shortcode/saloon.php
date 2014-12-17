<?php
/**
 * @var string               $content
 * @var SLN_Shortcode_Saloon $saloon
 */
$labels = array(
    'date'      => __('date', 'sln'),
    'services'  => __('services', 'sln'),
    'secondary' => __('secondary', 'sln'),
    'details'   => __('details', 'sln'),
    'summary'   => __('summary', 'sln'),
    'thankyou'  => __('thankyou', 'sln'),
);
?>
<div id="sln-saloon">
    <div>
    <h1>Make a booking <i class="glyphicon glyphicon-calendar"></i></h1>
    <ul class="saloon-bar nav nav-pills nav-justified thumbnail">
        <?php $i = 0;
        foreach ($saloon->getSteps() as $step) : $i++; ?>
            <li <?php echo $step == $saloon->getCurrentStep() ? 'class="active"' : ''?>>
                <?php echo $i ?>. <?php echo $labels[$step] ?>
            </li>
        <?php endforeach ?>
    </ul>
    <?php echo $content ?>
    </div>
</div>