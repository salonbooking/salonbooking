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
    $hasAttendants = false;
?>
<div class="sln-attendant-list sln-attendant-list--multiple">
    <div class="row">
        <div class="col-md-12">
            <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName() ?></h3>
        </div>
    </div>
    <?php foreach ($attendants as $attendant) : ?>
        <?php
        
        if ( $plugin->getSettings()->isChangeFormSteps() ) {
            $errors = false;
        }
        else {
            $validateAttServiceErrors = $ah->validateAttendantService($attendant, $service);
            if (!empty($validateAttServiceErrors)) {
                continue;
            }

            $errors   = $ah->validateAttendant($attendant, $bookingService->getStartsAt(), $bookingService->getDuration());
        }
        
        $settings = array();
        if ($errors) {
            $settings['attrs']['disabled'] = 'disabled';
        }
        if ($size == '900') {
            include '_m_attendants_item_900.php';
        } elseif ($size == '600') {
            include '_m_attendants_item_600.php';
        } elseif ($size == '400') {
            include '_m_attendants_item_400.php';
        } else {
            throw new Exception('size not supported');
        }
        ?>
        
        <div class="clearfix"></div>
        <?php $hasAttendants = true ?>
    <?php endforeach ?>
    <?php if(!$hasAttendants) : ?>
        <div class="alert alert-warning">
            <p><?php echo __('No assistants available for the selected time/slot - please choose another one', 'salon-booking-system') ?></p>
        </div>
    <?php endif ?> 
</div>
<?php endforeach ?>
