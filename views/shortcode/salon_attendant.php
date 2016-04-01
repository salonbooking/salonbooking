<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_AttendantStep $step
 * @var bool                              $isMultipleAttSelection
 */
$bb = $plugin->getBookingBuilder();
$attendants = $step->getAttendants();
?>
<h1><?php if ($isMultipleAttSelection && count($bb->getServices()) > 1) _e('Select your assistants','salon-booking-system'); else _e('Select your assistant','salon-booking-system'); ?></h1>
<form id="salon-step-attendant" method="post" action="<?php echo $formAction ?>" role="form">
    <?php if ($isMultipleAttSelection) {
        include "_m_attendants.php";
    } else {
        include "_attendants.php";
    } ?>
    <?php include "_form_actions.php" ?>
</form>
