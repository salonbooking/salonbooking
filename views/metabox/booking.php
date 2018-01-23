<?php
/**
 * @var SLN_Metabox_Helper $helper
 * @var SLN_Plugin $plugin
 * @var SLN_Settings $settings
 * @var SLN_Wrapper_Booking $booking
 * @var string $mode
 * @var SLN_DateTime|null $date
 * @var SLN_DateTime|null $time
 */
$helper->showNonce($postType);
SLN_Action_InitScripts::enqueueCustomBookingUser();

$checkoutFields = SLN_Enum_CheckoutFields::getRequiredFields();
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
    do_action('sln.template.booking.metabox',$booking);

    $selectedDate = !empty($date) ? $date : $booking->getDate();
    $selectedTime = !empty($time) ? $time : $booking->getTime();

    $intervalDate = clone $selectedDate;
    $intervals    = $plugin->getIntervals($intervalDate);
    ?>
<span id="salon-step-date"
      data-intervals="<?php echo esc_attr(json_encode($intervals->toArray())); ?>"
      data-isnew="<?php echo $booking->isNew() ? 1 : 0 ?>"
      data-deposit_amount="<?php echo $settings->getPaymentDepositAmount() ?>"
      data-deposit_is_fixed="<?php echo (int) $settings->isPaymentDepositFixedAmount() ?>"
      data-m_attendant_enabled="<?php echo $settings->get('m_attendant_enabled') ?>"
      data-mode="<?php echo $mode ?>"
      data-required_user_fields="<?php echo implode(',', $checkoutFields) ?>">
    <div class="row form-inline">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="form-group sln-input--simple">
                <label for="<?php echo SLN_Form::makeID($helper->getFieldName($postType, 'date')) ?>"><?php _e(
                        'Select a day',
                        'salon-booking-system'
                    ) ?></label>
                <?php SLN_Form::fieldJSDate($helper->getFieldName($postType, 'date'), $selectedDate, array('popup-class' => ($mode === 'sln_editor' ? 'off-sm-md-support' : ''))) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="form-group sln-input--simple">
                <label for="<?php echo SLN_Form::makeID($helper->getFieldName($postType, 'time')) ?>"><?php _e(
                        'Select an hour',
                        'salon-booking-system'
                    ) ?></label>
                <?php SLN_Form::fieldJSTime(
                    $helper->getFieldName($postType, 'time'),
                    $selectedTime,
                    array('interval' => $plugin->getSettings()->get('interval'),
                          'popup-class' => ($mode === 'sln_editor' ? 'off-sm-md-support' : ''))
                ) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
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
        <?php 
        $additional_fields = SLN_Enum_CheckoutFields::toArray('additional');
        if($additional_fields){
             foreach ($additional_fields as $field => $label) {
              $value = $booking->getMeta($field) ;
              $method_name= 'field'.ucfirst(SLN_Enum_CheckoutFields::$additional_fields_types[$field]);
              ?>
                <div class="col-sm-12 sln-input--simple <?php echo isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field]) ? 'sln-'.SLN_Enum_CheckoutFields::$additional_fields_types[$field] : '' ?>">
                    <div class="form-group sln_meta_field">
                        <label for="<?php echo $field ?>"><?php echo $label ?></label>
                        <?php 
                            if(isset(SLN_Enum_CheckoutFields::$additional_fields_types[$field])){
                                $additional_opts = array( 
                                    $helper->getFieldName($postType, $field), $value, 
                                    array('required' => SLN_Enum_CheckoutFields::isRequired($field)) 
                                );
                                if(SLN_Enum_CheckoutFields::$additional_fields_types[$field] === 'checkbox'){
                                    

                                   $additional_opts = array_merge(array_slice($additional_opts, 0, 2), array(''), array_slice($additional_opts, 2));
                                    $method_name = $method_name .'Button';
                                }
                                if(SLN_Enum_CheckoutFields::$additional_fields_types[$field] === 'select') $additional_opts = array_merge(array_slice($additional_opts, 0, 1), array(SLN_Enum_CheckoutFields::$fields_select_options[$field]), array_slice($additional_opts, 1));
                                call_user_func_array(array('SLN_Form',$method_name), $additional_opts );
                                
                            }
                        ?>
                    </div>
                </div>
              <?php 
             }
        } ?> 
        <div class="clearfix"></div>
        <div class="col-md-6 col-sm-12">
        <div class="sln-checkbox">
            <input type="checkbox" id="_sln_booking_createuser" name="_sln_booking_createuser"/>
            <label for="_sln_booking_createuser"><?php _e('Create a new user') ?></label>
        </div>
        </div>
    </div>

    <div class="sln-separator"></div>
    <?php echo $plugin->loadView('metabox/_booking_services', compact('booking')); ?>
   <div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="form-group sln_meta_field sln-select">
                <label><?php _e('Duration', 'salon-booking-system'); ?></label>
                <input type="text" id="sln-duration" value="<?php echo $booking->getDuration()->format('H:i') ?>" class="form-control" readonly="readonly"/>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'amount'),
                apply_filters('sln.template.metabox.booking.total_amount_label', __('Amount', 'salon-booking-system').' ('.$settings->getCurrencySymbol().')', $booking),
                $booking->getAmount()
            );
            ?>
            <button class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--settings" id="calculate-total"><?php _e('Calculate total', 'salon-booking-system') ?></button>
        </div>
        <div class="col-md-2 col-sm-4 sln-input--simple">
            <?php
            $helper->showFieldText(
                $helper->getFieldName($postType, 'deposit'),
                __('Deposit', 'salon-booking-system').' '.SLN_Enum_PaymentDepositType::getLabel($settings->getPaymentDepositValue()).' ('.$settings->getCurrencySymbol().')',
                $booking->getDeposit()
            );
            ?>
        </div>

        <div class="col-md-2 col-sm-4">
            <div class="form-group sln-input--simple">
                <label for="">Transaction</label>

                <p><?php echo $booking->getTransactionId() ? $booking->getTransactionId() : __(
                        'n.a.',
                        'salon-booking-system'
                    ) ?></p>
            </div>
        </div>

        <?php do_action('sln.template.metabox.booking.total_amount_row', $booking); ?>
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
