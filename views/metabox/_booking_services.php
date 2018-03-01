<?php

$servicesData = array();
$formatter = $plugin->format();
$isAttendants = $plugin->getSettings()->isAttendantsEnabled();
$isMultipleAttendants = $plugin->getSettings()->isMultipleAttendantsEnabled();
$isAttendants = $isAttendants || $booking->getAttendant();
$isMultipleAttendants = $isAttendants && ($isMultipleAttendants || (count($booking->getAttendants(true)) > 1));
/** @var SLN_Repository_ServiceRepository $sRepo */
$sRepo =  $plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
$allServices = $sRepo->getAll();
$allServices = $sRepo->sortByExecAndTitleDESC($allServices);
$allServices = apply_filters('sln.shortcode.salon.ServicesStep.getServices', $allServices);

/** @var SLN_Repository_AttendantRepository $sRepo */
$sRepo =  $plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
$allAttendants = $sRepo->getAll();
$allAttendants = apply_filters('sln.shortcode.salon.AttendantStep.getAttendants', $allAttendants);
$attendantsData = array();
foreach ($allAttendants as $attendant) {
    $attendantsData[ $attendant->getId()] = $attendant->getName();
}
?>
    <div id="sln_booking_services" class="form-group sln_meta_field row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <?php if($isMultipleAttendants): ?>
            <h3><?php _e('Services & Attendants', 'salon-booking-system'); ?></h3>
            <?php else: ?>
            <h3><?php _e('Services', 'salon-booking-system'); ?></h3>
            <?php endif ?>
        </div>

        <?php ob_start(); ?>
        <div class="row col-xs-12 col-sm-12 col-md-12 sln-booking-service-line">
            <?php if ($isMultipleAttendants): ?>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"></label>
                </div>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"></label>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4  sln-select">
            <?php else: ?>
                <div class="col-xs-12 col-sm-6 col-md-6  sln-select">
            <?php endif; ?>
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[services][]',
                    array('__service_id__' => '__service_title__'),
                    '__service_id__',
                    array(
                        'attrs' => array(
                            'disabled'      => 'disabled',
                            'data-price'    => '__service_price__',
                            'data-duration' => '__service_duration__',
                            'data-break'    => '__service_break_duration__',
                        )
                    ),
                    true
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[service][__service_id__]',
                    '__service_id__',
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[price][__service_id__]',
                    '__service_price__',
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[duration][__service_id__]',
                    '__service_duration__',
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[break_duration][__service_id__]',
                    '__service_break_duration__',
                    array('type' => 'hidden')
                )
                ?>
            </div>
            <?php if($isMultipleAttendants || $isAttendants): ?>
            <div class="col-xs-12 col-sm-2 col-md-2 sln-select">
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[attendants][__service_id__]',
                    array('__attendant_id__' => '__attendant_name__'),
                    '__attendant_id__',
                    array('attrs' => array('data-service' => '__service_id__', 'data-attendant' => '')),
                    true
                ) ?>
            </div>
            <?php endif ?>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div>
                    <button class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash" data-collection="remove"><?php echo __('Remove', 'salon-booking-system')?></button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php
        $lineItem = ob_get_clean();
        $lineItem = preg_replace("/\r\n|\n/", ' ', $lineItem);
        ?>
        <div class="row col-xs-12 col-sm-12 col-md-12">
            <?php if ($isMultipleAttendants): ?>
                <div class="col-xs-6 col-sm-1 col-md-1"><h4><?php _e('Start at', 'salon-booking-system') ?></h4></div>
                <div class="col-xs-6 col-sm-1 col-md-1"><h4><?php _e('End at', 'salon-booking-system') ?></h4></div>
            <?php endif; ?>
            <div class="col-xs-12 col-sm-4 col-md-4"><h4><?php _e('Service', 'salon-booking-system') ?></h4></div>
            <?php if ($isMultipleAttendants): ?>
            <div class="col-xs-12 col-sm-3 col-md-3"><h4><?php _e('Attendant', 'salon-booking-system') ?></h4></div>
            <?php endif; ?>
            <div class="col-xs-12 col-sm-2 col-md-2"><h4></h4></div>
        </div>

<?php  foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
                <?php
                $serviceName = $bookingService->getService()->getName();
                $serviceId = $bookingService->getService()->getId();
 
                $servicesData[ $serviceId] = array(
                    'old_price'    => $bookingService->getPrice(),
                    'old_duration' => SLN_Func::getMinutesFromDuration($bookingService->getDuration()),
                    'old_break_duration' => SLN_Func::getMinutesFromDuration($bookingService->getBreakDuration()),
                );
               ?>

        <div class="col-xs-12 col-sm-12 col-md-12 sln-booking-service-line">
            <div class="row">
                
            <?php if ($isMultipleAttendants): ?>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"><?php echo $formatter->time($bookingService->getStartsAt()) ?></label>
                </div>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"><?php echo $formatter->time($bookingService->getEndsAt()) ?></label>
                </div>
            <?php endif; ?>
                <div class="col-xs-12 col-sm-6 col-md-6 sln-select">
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[services][]',
                    array(
                        $serviceId => $serviceName . ' (' .
                                                                  $formatter->money($bookingService->getPrice()) . ') - ' .
                                                                  $bookingService->getDuration()->format('H:i')
                    ),
                    $bookingService->getService()->getId(),
                    array(
                        'attrs' => array(
                            'disabled'      => 'disabled',
                            'data-price'    => $servicesData[ $serviceId]['old_price'],
                            'data-duration' => $servicesData[ $serviceId]['old_duration'],
                        )
                    ),
                    true
                    )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[service]['.$serviceId.']',
                    $serviceId,
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[price]['.$serviceId.']',
                    $servicesData[ $serviceId]['old_price'],
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[duration]['.$serviceId.']',
                    $servicesData[ $serviceId]['old_duration'],
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[break_duration]['.$serviceId.']',
                    $servicesData[ $serviceId]['old_break_duration'],
                    array('type' => 'hidden')
                )
                ?>
            </div>
            <?php if ($isMultipleAttendants || $isAttendants): ?>
            <div class="col-xs-12 col-sm-3 col-md-3 sln-select">
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[attendants][' . $serviceId . ']',
                    ($bookingService->getAttendant() ?
                        array($bookingService->getAttendant()->getId() => $bookingService->getAttendant()->getName()) : array('')),
                    ($bookingService->getAttendant() ? $bookingService->getAttendant()->getId() : ''),
                    array('attrs' => array('data-service' => $serviceId, 'data-attendant' => '')),
                    true
                ) ?>
            </div>
            <?php endif ?>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div>
                    <button class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash" data-collection="remove"><?php echo __('Remove', 'salon-booking-system')?></button>
                </div>
            </div>
        
            </div>
        </div>
        <div class="clearfix"></div>
<?php endforeach ?>
 
        <div class="col-xs-12 col-sm-12 col-md-12 sln-booking-service-action">
            <div class="row">
                
            <?php if ($isMultipleAttendants): ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-sm-offset-2 col-md-offset-2 sln-select">
            <?php else: ?>
                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6 sln-select">
            <?php endif; ?>
                <select class="sln-select" name="_sln_booking_service_select" id="_sln_booking_service_select">
                    <option value=""><?php _e('Select a service','salon-booking-system') ?></option>
                <?php

                foreach ($allServices as $service) {
                    $servicesData[ $service->getId()] = array_merge(
                        isset($servicesData[ $service->getId() ]) ? $servicesData[ $service->getId() ] : array(),
                        array(
                            'title'      => $service->getName() . ' (' . $formatter->money($service->getPrice()) . ') - ' . $service->getDuration()->format('H:i'),
                            'name'       => $service->getName(),
                            'price'      => $service->getPrice(),
                            'duration'   => SLN_Func::getMinutesFromDuration($service->getDuration()),
                            'break_duration' => SLN_Func::getMinutesFromDuration($service->getBreakDuration()),
                            'exec_order' => $service->getExecOrder(),
                            'attendants' => $service->getAttendantsIds()
                        )
                    );
                    ?>
                    <option data-id="<?php echo SLN_Form::makeID('sln[service]['.$service->getId().']') ?>"
                            value="<?php echo $service->getId();?>"
                    ><strong class="service-name"><?php echo $servicesData[ $service->getId()]['title']; ?></option>
                    <?php
                }
                ?>
                </select>
            </div>

            <?php if ($isMultipleAttendants || $isAttendants): ?>
            <div class="col-xs-12 col-sm-3 col-md-3 sln-select">
                <select class="sln-select" name="_sln_booking_attendant_select" id="_sln_booking_attendant_select">
                    <option value=""><?php _e('Select an assistant','salon-booking-system') ?></option>
                </select>
            </div>
                <?php /*
            <?php elseif($isAttendants) : ?>
            <div class="col-xs-12 col-sm-3 col-md-3 sln-select">
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[attendant]',
                    $attendantsData,
                    $booking->getAttendant() ? $booking->getAttendant()->getId() : '',
                    array('empty_value' => __('Select an assistant','salon-booking-system')),
                    true
                ) ?>
            </div> */ ?>
            <?php endif ?>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <button data-collection="addnewserviceline"class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--file">
                    <?php _e('Add service','salon-booking-system') ?>
                </button>
            </div>
        </div>
        <script>
            var servicesData = '<?php echo addslashes(json_encode($servicesData)); ?>';
            var attendantsData = '<?php echo addslashes(json_encode($attendantsData)); ?>';
            var lineItem = '<?php echo $lineItem; ?>';
        </script>
    
            </div>
        </div>

