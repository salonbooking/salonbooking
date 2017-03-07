<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_AttendantStep $step
 * @var SLN_Wrapper_Attendant[]           $attendants
 */

$ah = $plugin->getAvailabilityHelper();
$ah->setDate($plugin->getBookingBuilder()->getDateTime());
$bookingServices = SLN_Wrapper_Booking_Services::build($bb->getAttendantsIds(), $bb->getDateTime());

foreach ($bookingServices->getItems() as $bookingService) :
    $service = $bookingService->getService();
    if ($service->isAttendantsEnabled()) {
        $tmp = '';
        foreach ($attendants as $attendant) {
            if ($attendant->hasServices(array($service))) {
                $errors = SLN_Shortcode_Salon_AttendantHelper::validateItem(array($bookingService), $ah, $attendant);
                $tmp .= SLN_Shortcode_Salon_AttendantHelper::renderItem($size, $errors, $attendant, $service);
            }
        }
        if ($tmp) {
            $tmp = SLN_Shortcode_Salon_AttendantHelper::renderItem($size, $errors, null, $service).$tmp;
        }
    }
    ?>
    <div class="sln-attendant-list sln-attendant-list--multiple">
        <div class="row">
            <div class="col-md-12">
                <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName() ?></h3>
            </div>
        </div>
        <?php if ($service->isAttendantsEnabled()) : ?>
            <?php if ($tmp) : ?>
                <?php echo $tmp ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    <p><?php echo __(
                            'No assistants available for the selected time/slot - please choose another one',
                            'salon-booking-system'
                        ) ?></p>
                </div>
            <?php endif ?>
        <?php else: ?>
            <div class="row sln-attendant">
                <?php SLN_Form::fieldText('sln[attendants]['.$service->getId().']', 0, array('type' => 'hidden')) ?>
                <p><?php echo __(
                        'The choice of assistant is not provided for this service',
                        'salon-booking-system'
                    ) ?></p>
            </div>
        <?php endif ?>
    </div>
<?php endforeach ?>

