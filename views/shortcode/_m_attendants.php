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
<div class="sln-attendant-list">
    <h3><?php echo $service->getName() ?></h3>
    <?php foreach ($attendants as $attendant) : ?>
        <?php
        $validateAttServiceErrors = $ah->validateAttendantService($attendant, $service);
        if (!empty($validateAttServiceErrors)) {
            continue;
        }

        $errors   = $ah->validateAttendant($attendant, $bookingService->getStartsAt(), $bookingService->getDuration());
        $settings = array();
        if ($errors) {
            $settings['attrs']['disabled'] = 'disabled';
        }
        ?>
        <div class="row">
            <div class="col-lg-1 col-md-3 col-xs-2">
            <span class="attendant-radio <?php echo  $bb->hasAttendant($attendant, $service) ? 'is-checked' : '' ?>">

            <?php SLN_Form::fieldRadiobox(
                'sln[attendants][' . $service->getId() . ']',
                $attendant->getId(),
                $bb->hasAttendant($attendant, $service),
                $settings
            ) ?>
            </span>
            </div>

            <div class="col-lg-3 col-md-3 col-xs-4">

                <div class="attendant_thumb">
                <?php   if ( has_post_thumbnail($attendant->getId())) { 

                echo get_the_post_thumbnail($attendant->getId(), 'thumbnail'); 

                 }
                ?>
                </div>


            </div>




            <div class="col-lg-8 col-md-6 col-xs-6">


                <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                    <strong class="attendant-name"><?php echo $attendant->getName(); ?></strong>
                    <span class="attendant-description"><?php echo $attendant->getContent() ?></span>
                </label>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php if ($errors) : ?>
            <div><div class="col-xs-offset-2 col-lg-offset-1"><div class="alert alert-danger alert-no-spacing">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div></div></div>
        <?php endif ?>
        <?php $hasAttendants = true ?>
    <?php endforeach ?>
    <?php if(!$hasAttendants) : ?>
        <div class="alert alert-warning">
            <p><?php echo __('No assistants available for the selected time/slot - please choose another one', 'salon-booking-system') ?></p>
        </div>
    <?php endif ?> 
</div>
<?php endforeach ?>
