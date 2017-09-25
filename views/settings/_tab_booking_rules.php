<?php
/**
 * @var $plugin SLN_Plugin
 * @var $availabilities array
 */

$label = __('On-line booking available days', 'salon-booking-system');
$block = __(
    'Create one or more rules to limit online reservation to specific days and time range. <br />Leave blank if you want bookings available everydays at every hour',
    'salon-booking-system'
    );
if (!is_array($availabilities)) {
    $availabilities = array();
}
SLN_Action_InitScripts::enqueueCustomSliderRange();
?>
<div class="sln-box--sub sln-booking-rules row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php echo $label ?>
            <span class="block"><?php echo $block ?></span></h2>
    </div>
    <div class="sln-booking-rules-wrapper">
	<?php $n = 0; ?>
        <?php foreach ($availabilities as $row): $n++; ?>
            <?php echo $plugin->loadView(
                'settings/_availability_row',
                array(
                    'prefix' => $base."[$n]",
                    'row' => $row,
                    'rulenumber' => $n,
                )
            ); ?>
        <?php endforeach ?>
    </div>
    <div class="col-xs-12">
        <button data-collection="addnew"
                class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--file"><?php _e(
                'Add new',
                'salon-booking-system'
            ) ?>
        </button>
    </div>
    <div data-collection="prototype" data-count="<?php echo count($availabilities) ?>">
        <?php echo $plugin->loadView(
            'settings/_availability_row',
            array(
                'row' => array(),
                'rulenumber' => '__new__',
                'prefix' => $base."[__new__]",
            )
        ); ?>
    </div>
</div>
