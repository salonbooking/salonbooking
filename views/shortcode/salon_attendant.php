<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_AttendantStep $step
 * @var bool                              $isMultipleAttSelection
 */
$bb                     = $plugin->getBookingBuilder();
$attendants             = $step->getAttendants();
$style                  = $step->getShortcode()->getStyleShortcode();
$size                   = SLN_Enum_ShortcodeStyle::getSize($style);
$isMultipleAttSelection = $plugin->getSettings()->isMultipleAttendantsEnabled();
$includeName = $isMultipleAttSelection ? '_m_attendants.php' : '_attendants.php';
?>
<?php include '_errors.php'; ?>
<form id="salon-step-attendant" method="post" action="<?php echo $formAction ?>" role="form">
    <?php
    if ($isMultipleAttSelection && count($bb->getServices()) > 1) {
        $label = __('Select your assistants', 'salon-booking-system');
    } else {
        $label = __('Select your assistant', 'salon-booking-system');
    }
    $args = array(
        'label'        => $label,
        'tag'          => 'h2',
        'textClasses'  => 'salon-step-title',
        'inputClasses' => '',
        'tagClasses'   => 'salon-step-title',
    );
    echo $plugin->loadView('shortcode/_editable_snippet', $args);
    ?>
    <?php if ($size == '900'): ?>
        <div class="row sln-box--main sln-attendants-wrapper">
            <div class="col-md-8"><?php include $includeName; ?></div>
            <div class="col-md-4 sln-box--formactions">
                <div class="col-md-12"><?php include "_form_actions.php" ?></div>
            </div>
        </div>
    <?php else: ?>
        <div class="row sln-box--main sln-attendants-wrapper">
            <div class="col-md-12"><?php include $includeName; ?></div>
        </div>
        <div class="row sln-box--main sln-box--formactions">
            <div class="col-md-12"><?php include "_form_actions.php" ?></div>
        </div>
    <?php endif ?>
</form>
