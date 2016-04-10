<?php
/**
 * @var $plugin SLN_Plugin
 * @var $availabilities array
 */

$label = __('On-line booking available days', 'salon-booking-system');
$block = __(
    'The following rules, should represent your real timetable. <br />Leave blank if you want bookings available everydays at every hour',
    'salon-booking-system'
    );
if (!is_array($availabilities)) {
    $availabilities = array();
}
$n = 0;
?>
<script type="text/javascript" src="<?php echo SLN_PLUGIN_URL ?>/js/customSliderRange.js?20160224"></script>
<script type="text/javascript">
    jQuery(function ($) {
        customSliderRange($, $('.slider-range'));
    });
</script>
<div class="sln-box--sub sln-booking-rules row">
    <div class="col-xs-12">
        <h2 class="sln-box-title"><?php echo $label ?>
            <span class="block"><?php echo $block ?></span></h2>
    </div>
    <div id="sln-booking-rules-wrapper">
        <?php foreach ($availabilities as $k => $row): $n++; ?>
            <?php echo $plugin->loadView(
                'settings/_availability_row',
                array(
                    'prefix' => $base."[$k]",
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
                'prefix' => $base."[__new__]",
            )
        ); ?>
    </div>
</div>
