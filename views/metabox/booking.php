<?php
/**
 * @var SLN_Metabox_Helper $helper
 */
$helper->showNonce($postType);
?>

<div class="sln-bootstrap">

<div class="sln_booking-topbuttons">
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-6">
            <h2><?php _e('Re-send email notification to ','sln') ?></h2>
            <div class="row">
            <div class="col-lg-7 col-md-8 col-sm-8"><input type="text" class="form-control" name="emailto"/></div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <button class="btn btn-success" type="submit" name="emailto_submit" value="submit"><?php echo __('Send', 'sln')?></button>

            </div>
            </div>
        </div>

        <?php if ($plugin->getSettings()->get('confirmation') && $booking->getStatus() == SLN_Enum_BookingStatus::PENDING){ ?>
        <div class="col-lg-5 col-md-5 col-sm-6 sln_accept-refuse">
            <h2><?php _e('This booking waits for confirmation!','sln')?></h2>
            <div class="row">
            <div class="col-lg-5 col-md-6 col-sm-6">
               <button id="booking-refuse" class="btn btn-success" data-status="<?php echo SLN_Enum_BookingStatus::CONFIRMED ?>">
                <?php _e('Accept', 'sln') ?></button>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-6"> <button id="booking-accept" class="btn btn-danger" data-status="<?php echo SLN_Enum_BookingStatus::CANCELED ?>">
                <?php _e('Refuse', 'sln') ?></button>
            </div>
            </div>
        </div>
        <?php } ?>
    </div>
    
</div>


    <div class="row">
        <div class="col-md-3 col-sm-6">
            <?php
            $helper->showFieldtext(
                $helper->getFieldName($postType, 'firstname'),
                __('Firstname', 'sln'),
                $booking->getFirstname()
            );
            ?>
        </div>
        <div class="col-md-3 col-sm-6">
            <?php
            $helper->showFieldtext(
                $helper->getFieldName($postType, 'lastname'),
                __('Lastname', 'sln'),
                $booking->getLastname()
            );
            ?>
        </div>
        <div class="col-md-3 col-sm-6">
            <?php
            $helper->showFieldtext(
                $helper->getFieldName($postType, 'email'),
                __('E-mail', 'sln'),
                $booking->getEmail()
            ); ?>
        </div>
        <div class="col-md-3 col-sm-6">

            <?php
            $helper->showFieldtext(
                $helper->getFieldName($postType, 'phone'),
                __('Phone', 'sln'),
                $booking->getPhone()
            );
            ?>
        </div>
        <div class="col-md-6 col-sm-12">

            <?php
            $helper->showFieldtext(
                $helper->getFieldName($postType, 'phone'),
                __('Address', 'sln'),
                $booking->getAddress()
            );
            ?>
        </div>
 
    </div>
    <div class="row form-inline">
        <div class="col-md-6 col-sm-8">
            <div class="form-group sln_meta_field">
                <label><?php _e('Date', 'sln'); ?>
                    <?php SLN_Form::fieldDate($helper->getFieldName($postType, 'date'), $booking->getDate()); ?>
                    <?php SLN_Form::fieldTime($helper->getFieldName($postType, 'time'), $booking->getTime()); ?>
                </label>
            </div>
        </div>
        <div class="col-md-6 col-sm-4">
            <div class="form-group sln_meta_field ">
                <label><?php _e('Status', 'sln'); ?></label>
                <?php SLN_Form::fieldSelect(
                    $helper->getFieldName($postType, 'status'),
                    SLN_Enum_BookingStatus::toArray(),
                    $booking->getStatus(),
                    array('map' => true)
                ); ?>
            </div>
        </div>
    </div>
    <div class="sln-separator"></div>
    <div class="form-group sln_meta_field row">
        <div class="col-xs-12 col-sm-6 col-md-6">
        <h3><?php _e('Attendant', 'sln'); ?></h3>
            <select class="sln-select">
                <?php foreach ($plugin->getAttendants() as $attendant) : ?>
                   <option value="<?php echo SLN_Form::makeID('sln[services][' . $attendant->getId() . ']') ?>"><strong class="service-name"><?php echo $attendant->getName(); ?></option>
                <?php endforeach ?>
            </select>
        </div>
    <!-- .row // END -->
    </div>
    <div class="sln-separator"></div>
    <div class="form-group sln_meta_field row">
        <div class="col-xs-12 col-sm-6 col-md-6">
        <h3><?php _e('Services', 'sln'); ?></h3>
            <select class="sln-select" multiple="multiple" data-placeholder="Select one or more services">
                <?php foreach ($plugin->getServices() as $service) : ?>
                   <option value="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>"><?php echo $service->getName(); ?></option>
                <?php endforeach ?>
            </select>
        </div>
    <!-- .row // END -->
    </div>
    <div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group sln_meta_field ">
                <label><?php _e('Personal message', 'sln'); ?></label>
                <?php SLN_Form::fieldTextarea(
                    $helper->getFieldName($postType, 'note'),
                    $booking->getNote()
                ); ?>
            </div>
        </div>
    </div>
    <div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="form-group sln_meta_field ">
                <label><?php _e('Duration', 'sln'); ?></label>
                <?php SLN_Form::fieldTime(
                    $helper->getFieldName($postType, 'duration'),
                    $booking->getDuration(),
                    array('interval' => 10, 'maxItems' => 61)
                ); ?>
            </div>
        </div>
        <div class="col-md-3 col-sm-4">
            <?php
            $helper->showFieldtext(
                $helper->getFieldName($postType, 'amount'),
                __('Amount', 'sln') . ' (' . $settings->getCurrencySymbol() . ')',
                $booking->getAmount()
            );
            ?>
        </div>
        <div class="col-md-3 col-sm-4">
            <?php
            $helper->showFieldtext(
                $helper->getFieldName($postType, 'deposit'),
                __('Deposit', 'sln') . ' (' . $settings->getCurrencySymbol() . ')',
                $booking->getDeposit()
            );
            ?>
        </div>

        <div class="col-md-3 col-sm-4">
            <div class="form-group">
                <label for="">Transaction</label>

                <p><?php echo $booking->getTransactionId() ? $booking->getTransactionId() : __('n.a.', 'sln') ?></p>
            </div>
        </div>
    </div>
    <div class="sln-clear"></div>
</div>
