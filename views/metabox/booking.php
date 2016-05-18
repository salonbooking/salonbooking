<?php
/**
 * @var SLN_Metabox_Helper $helper
 * @var SLN_Plugin $plugin
 * @var SLN_Wrapper_Booking $booking
 */
$helper->showNonce($postType);
/** @var SLN_Repository_ServiceRepository $sRepo */
$sRepo =  $plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
$allServices = $sRepo->getAll();
/** @var SLN_Repository_AttendantRepository $sRepo */
$sRepo =  $plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
$allAttendants = $sRepo->getAll();
SLN_Action_InitScripts::enqueueCustomBookingUser()
?>
<?php if(isset($_SESSION['_sln_booking_user_errors'])): ?>
    <div class="error">
    <?php foreach($_SESSION['_sln_booking_user_errors'] as $error): ?>
        <p><?php echo $error ?></p>
    <?php endforeach ?>
    </div>
    <?php unset($_SESSION['_sln_booking_user_errors']); ?>
<?php endif ?>

<div class="sln-bootstrap">
    <?php
    $intervals = $plugin->getIntervals($booking->getDate());
    $date = $intervals->getSuggestedDate();
    ?>
<span id="salon-step-date"
      data-intervals="<?php echo esc_attr(json_encode($intervals->toArray())); ?>"
      data-isnew="<?php echo $booking->isNew() ? 1 : 0 ?>"
      data-deposit="<?php echo $settings->get('pay_deposit') ?>"
      data-m_attendant_enabled="<?php echo $settings->get('m_attendant_enabled') ?>">
    <div class="row form-inline">
        <div class="col-md-3 col-sm-6">
            <div class="form-group sln-input--simple">
                <label for="<?php echo SLN_Form::makeID($helper->getFieldName($postType, 'date')) ?>"><?php _e(
                        'Select a day',
                        'salon-booking-system'
                    ) ?></label>
                <?php SLN_Form::fieldJSDate($helper->getFieldName($postType, 'date'), $booking->getDate()) ?>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="form-group sln-input--simple">
                <label for="<?php echo SLN_Form::makeID($helper->getFieldName($postType, 'time')) ?>"><?php _e(
                        'Select an hour',
                        'salon-booking-system'
                    ) ?></label>
                <?php SLN_Form::fieldJSTime(
                    $helper->getFieldName($postType, 'time'),
                    $booking->getTime(),
                    array('interval' => $plugin->getSettings()->get('interval'))
                ) ?>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="form-group sln_meta_field sln-select">
                <label><?php _e('Status', 'salon-booking-system'); ?></label>
                <?php SLN_Form::fieldSelect(
                    $helper->getFieldName($postType, 'status'),
                    SLN_Enum_BookingStatus::toArray(),
                    $booking->getStatus(),
                    array('map' => true)
                ); ?>
            </div>
        </div>
       
    </div>

 <div class="row form-inline">

     <div class="col-md-6 col-sm-6" id="sln-notifications"  data-valid-message="<?php _e('OK! the date and time slot you selected is available','salon-booking-system'); ?>"></div>

 </div>

</span>

    <div class="sln_booking-topbuttons">
        <div class="row">
            <?php if ($plugin->getSettings()->get('confirmation') && $booking->getStatus(
                ) == SLN_Enum_BookingStatus::PENDING
            ) { ?>
                <div class="col-lg-5 col-md-5 col-sm-6 sln_accept-refuse">
                    <h2><?php _e('This booking waits for confirmation!', 'salon-booking-system') ?></h2>

                    <div class="row">
                        <div class="col-lg-5 col-md-6 col-sm-6">
                            <button id="booking-refuse" class="btn btn-success"
                                    data-status="<?php echo SLN_Enum_BookingStatus::CONFIRMED ?>">
                                <?php _e('Accept', 'salon-booking-system') ?></button>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6">
                            <button id="booking-accept" class="btn btn-danger"
                                    data-status="<?php echo SLN_Enum_BookingStatus::CANCELED ?>">
                                <?php _e('Refuse', 'salon-booking-system') ?></button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<div class="row">
        <div class="col-md-6 col-sm-6">
        <label for="sln-update-user-field"><?php _e('Search for existing users', 'salon-booking-system') ?></label>
            <select id="sln-update-user-field"
                 data-nomatches="<?php _e('no users found','salon-booking-system')?>"
                 data-placeholder="<?php _e('Start typing the name or email')?>"
                 class="form-control">
            </select>
        </div>
        <div class="col-md-6 col-sm-6" id="sln-update-user-message">
        </div>
        </div>
        <div class="clearfix"></div>
<div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-3 col-sm-6 form-group sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'firstname'),
                __('Firstname', 'salon-booking-system'),
                $booking->getFirstname()
            );
            ?>
        </div>
        <div class="col-md-3 col-sm-6 sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'lastname'),
                __('Lastname', 'salon-booking-system'),
                $booking->getLastname()
            );
            ?>
        </div>
        <div class="col-md-3 col-sm-6 sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'email'),
                __('E-mail', 'salon-booking-system'),
                $booking->getEmail()
            ); ?>
        </div>
        <div class="col-md-3 col-sm-6 sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'phone'),
                __('Phone', 'salon-booking-system'),
                $booking->getPhone()
            );
            ?>
        </div>
        <div class="col-md-6 col-sm-12 sln-input--simple">
            <?php
            $helper->showFieldTextArea(
                $helper->getFieldName($postType, 'address'),
                __('Address', 'salon-booking-system'),
                $booking->getAddress()
            );
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 col-sm-12">
        <div class="sln-checkbox">
            <input type="checkbox" id="_sln_booking_createuser" name="_sln_booking_createuser" <?php if($booking->isNew()){ ?>checked="checked"<?php } ?>/>
            <label for="_sln_booking_createuser"><?php _e('Create a new user') ?></label>
        </div>
        </div>
    </div>

    <div class="sln-separator"></div>
    <div id="sln_booking_services" class="form-group sln_meta_field row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h3><?php _e('Services & Attendants', 'salon-booking-system'); ?></h3>
        </div>

        <?php ob_start(); ?>
        <div class="row col-xs-12 col-sm-12 col-md-12 sln-booking-service-line">
            <?php if ($settings->get('m_attendant_enabled')): ?>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"></label>
                </div>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"></label>
                </div>
            <?php endif; ?>

            <?php if ($settings->get('m_attendant_enabled')): ?>
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
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2 sln-select">
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[attendants][__service_id__]',
                    array('__attendant_id__' => '__attendant_name__'),
                    '__attendant_id__',
                    array('attrs' => array('data-service' => '__service_id__', 'data-attendant' => '')),
                    true
                ) ?>
            </div>
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
            <?php if ($settings->get('m_attendant_enabled')): ?>
                <div class="col-xs-6 col-sm-1 col-md-1"><h4><?php _e('Start at', 'salon-booking-system') ?></h4></div>
                <div class="col-xs-6 col-sm-1 col-md-1"><h4><?php _e('End at', 'salon-booking-system') ?></h4></div>
            <?php endif; ?>
            <div class="col-xs-12 col-sm-4 col-md-4"><h4><?php _e('Service', 'salon-booking-system') ?></h4></div>
            <div class="col-xs-12 col-sm-3 col-md-3"><h4><?php _e('Attendant', 'salon-booking-system') ?></h4></div>
            <div class="col-xs-12 col-sm-2 col-md-2"><h4></h4></div>
        </div>
        <?php

        $servicesData = array();
        foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
        <div class="row col-xs-12 col-sm-12 col-md-12 sln-booking-service-line">
            <?php if ($settings->get('m_attendant_enabled')): ?>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"><?php echo SLN_Plugin::getInstance()->format()->time($bookingService->getStartsAt()) ?></label>
                </div>
                <div class="col-xs-6 col-sm-1 col-md-1">
                    <label class="time"><?php echo SLN_Plugin::getInstance()->format()->time($bookingService->getEndsAt()) ?></label>
                </div>
            <?php endif; ?>

            <?php if ($settings->get('m_attendant_enabled')): ?>
                <div class="col-xs-12 col-sm-4 col-md-4 sln-select">
            <?php else: ?>
                <div class="col-xs-12 col-sm-4 col-md-4 sln-select">
            <?php endif; ?>
                <?php
                $servicesData[ $bookingService->getService()->getId()] = array(
                    'old_price'    => $bookingService->getPrice(),
                    'old_duration' => 60*$bookingService->getDuration()->format('H') + intval($bookingService->getDuration()->format('i')),
                );
                ?>
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[services][]',
                    array(
                        $bookingService->getService()->getId() => $bookingService->getService()->getName() . ' (' .
                                                                  $plugin->format()->money($bookingService->getPrice()) . ') - ' .
                                                                  $bookingService->getDuration()->format('H:i')
                    ),
                    $bookingService->getService()->getId(),
                    array(
                        'attrs' => array(
                            'disabled'      => 'disabled',
                            'data-price'    => $servicesData[ $bookingService->getService()->getId()]['old_price'],
                            'data-duration' => $servicesData[ $bookingService->getService()->getId()]['old_duration'],
                        )
                    ),
                    true
                    )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[service]['.$bookingService->getService()->getId().']',
                    $bookingService->getService()->getId(),
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[price]['.$bookingService->getService()->getId().']',
                    $servicesData[ $bookingService->getService()->getId()]['old_price'],
                    array('type' => 'hidden')
                )
                ?>
                <?php SLN_Form::fieldText(
                    '_sln_booking[duration]['.$bookingService->getService()->getId().']',
                    $servicesData[ $bookingService->getService()->getId()]['old_duration'],
                    array('type' => 'hidden')
                )
                ?>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 sln-select">
                <?php SLN_Form::fieldSelect(
                    '_sln_booking[attendants][' . $bookingService->getService()->getId() . ']',
                    array($bookingService->getAttendant()->getId() => $bookingService->getAttendant()->getName()),
                    $bookingService->getAttendant()->getId(),
                    array('attrs' => array('data-service' => $bookingService->getService()->getId(), 'data-attendant' => '')),
                    true
                ) ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div>
                    <button class="sln-btn sln-btn--problem sln-btn--big sln-btn--icon sln-icon--trash" data-collection="remove"><?php echo __('Remove', 'salon-booking-system')?></button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php endforeach ?>
        <div class="row col-xs-12 col-sm-12 col-md-12 sln-booking-service-action">
            <?php if ($settings->get('m_attendant_enabled')): ?>
                <div class="col-xs-12 col-sm-4 col-md-4 col-sm-offset-2 col-md-offset-2 sln-select">
            <?php else: ?>
                <div class="col-xs-12 col-sm-4 col-md-4 sln-select">
            <?php endif; ?>
                <select class="sln-select" name="_sln_booking_service_select" id="_sln_booking_service_select">
                    <option value=""><?php _e('Select a service','salon-booking-system') ?></option>
                <?php

                foreach ($allServices as $service) {
                    $servicesData[ $service->getId()] = array_merge(
                        isset($servicesData[ $service->getId() ]) ? $servicesData[ $service->getId() ] : array(),
                        array(
                            'title'      => $service->getName() . ' (' . $plugin->format()->money($service->getPrice()) . ') - ' . $service->getDuration()->format('H:i'),
                            'name'       => $service->getName(),
                            'price'      => $service->getPrice(),
                            'duration'   => 60*$service->getDuration()->format('H') + intval($service->getDuration()->format('i')),
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
                <?php
                $attendantsData = array();
                foreach ($allAttendants as $attendant) {
                    $attendantsData[ $attendant->getId()] = array($attendant->getName());
                }
                ?>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 sln-select">
                <select class="sln-select" name="_sln_booking_attendant_select" id="_sln_booking_attendant_select">
                    <option value=""><?php _e('Select an assistant','salon-booking-system') ?></option>
                </select>
            </div>
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

    <div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="form-group sln_meta_field sln-select">
                <label><?php _e('Duration', 'salon-booking-system'); ?></label>
                <input type="text" value="<?php echo $booking->getDuration()->format('H:i') ?>" class="form-control" readonly="readonly"/>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'amount'),
                __('Amount', 'salon-booking-system').' ('.$settings->getCurrencySymbol().')',
                $booking->getAmount()
            );
            ?>
            <button class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--settings" id="calculate-total"><?php _e('Calculate total', 'salon-booking-system') ?></button>
        </div>
        <div class="col-md-3 col-sm-4 sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'deposit'),
                __('Deposit', 'salon-booking-system').' '.$settings->get('pay_deposit').'% ('.$settings->getCurrencySymbol().')',
                $booking->getDeposit()
            );
            ?>
        </div>

        <div class="col-md-3 col-sm-4">
            <div class="form-group sln-input--simple">
                <label for="">Transaction</label>

                <p><?php echo $booking->getTransactionId() ? $booking->getTransactionId() : __(
                        'n.a.',
                        'salon-booking-system'
                    ) ?></p>
            </div>
        </div>
    </div>
    <div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group sln_meta_field sln-input--simple">
                <label><?php _e('Personal message', 'salon-booking-system'); ?></label>
                <?php SLN_Form::fieldTextarea(
                    $helper->getFieldName($postType, 'note'),
                    $booking->getNote()
                ); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group sln_meta_field sln-input--simple">
                <label><?php _e('Administration notes', 'salon-booking-system'); ?></label>
                <?php SLN_Form::fieldTextarea(
                    $helper->getFieldName($postType, 'admin_note'),
                    $booking->getAdminNote()
                ); ?>
            </div>
        </div>
    </div>

</div>
