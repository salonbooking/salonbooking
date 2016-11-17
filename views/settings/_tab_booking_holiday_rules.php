<?php
/**
 * @var $plugin SLN_Plugin
 */

$row = null;
$key = 'holiday';
$label = __('Holidays days', 'salon-booking-system');
$block = __(
    'Set one or more rules for your holidays.<br /> Users will not be able to make reservation during these periods',
    'salon-booking-system'
);
if (!is_array($holidays)) {
    $holidays = array();
}
?>
<div class="sln-box--sub sln-booking-holiday-rules row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php echo $label ?> <span class="block"><?php echo $block ?></span></h2>
    </div>
    <div class="sln-booking-holiday-rules-wrapper">
        <?php $n = 0;
        foreach ($holidays as $k => $row): $n++; ?>
            <?php echo $plugin->loadView(
                'settings/_holiday_row',
                array(
                    'prefix' => $base."[$k]",
                    'row' => $row,
                    'rulenumber' => $n,
                )
            ); ?>
        <?php endforeach ?>
    </div>
    <div class="col-xs-12">
        <button data-collection="addnewholiday"
                class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--file"><?php _e(
                'Add new',
                'salon-booking-system'
            ) ?>
        </button>
    </div>
    <div data-collection="prototype" data-count="<?php echo count($holidays) ?>">
        <?php echo $plugin->loadView(
            'settings/_holiday_row',
            array(
                'prefix' => $base."[__new__]",
                'row' => array(),
                'rulenumber' => 'New',
            )
        ); ?>
    </div>
</div>
