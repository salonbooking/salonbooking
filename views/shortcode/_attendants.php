<?php
/**
 * @var SLN_Plugin $plugin
 * @var string $formAction
 * @var string $submitName
 * @var SLN_Shortcode_Salon_AttendantStep $step
 * @var SLN_Wrapper_Attendant[] $attendants
 */

$ah = $plugin->getAvailabilityHelper();
$ah->setDate($plugin->getBookingBuilder()->getDateTime());
$duration = new SLN_DateTime('1970-01-01 '.$bb->getDuration());
$hasAttendants = false;
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);

$bookingServices = SLN_Wrapper_Booking_Services::build($bb->getAttendantsIds(), $bb->getDateTime());

$services = $bb->getServices();
foreach($services as $k => $service) {
    if (!$service->isAttendantsEnabled()) {
        unset($services[$k]);
    }
}
?>
<div class="sln-attendant-list">
    <?php foreach ($attendants as $attendant) {

        if ($plugin->getSettings()->isFormStepsAltOrder()) {
            $validateAttServicesErrors = $ah->validateAttendantServices($attendant, $services);

            if (!empty($validateAttServicesErrors)) {
                continue;
            }
            $errors = false;
        } else {
            $validateAttServicesErrors = $ah->validateAttendantServices($attendant, $services);

            if (!empty($validateAttServicesErrors)) {
                continue;
            }

            foreach ($bookingServices->getItems() as $bookingService) {
                if (!$bookingService->getService()->isAttendantsEnabled()) {
                    continue;
                }
                $validateErrors = $ah->validateAttendant($attendant, $bookingService->getStartsAt(), $bookingService->getTotalDuration(), $bookingService->getBreakStartsAt(), $bookingService->getBreakEndsAt());
                if ($validateErrors) {
                    break;
                }
            }

            if ($validateErrors) {
                $errors = $validateErrors;
            } else {
                $errors = false;
            }
        }

        $settings = array();
        if ($errors) {
            $settings['attrs']['disabled'] = 'disabled';
        }
        if ($size == '900') {
            include '_attendants_item_900.php';
        } elseif ($size == '600') {
            include '_attendants_item_600.php';
        } elseif ($size == '400') {
            include '_attendants_item_400.php';
        } else {
            throw new Exception('size not supported');
        }
        $hasAttendants = true;
    } ?>
    <?php if (!$hasAttendants) : ?>
        <div class="alert alert-warning">
            <p><?php echo __(
                    'No assistants available for the selected time/slot - please choose another one',
                    'salon-booking-system'
                ) ?></p>
        </div>
    <?php endif ?>
</div>
