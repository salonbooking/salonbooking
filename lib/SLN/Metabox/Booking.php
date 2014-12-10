<?php

class SLN_Metabox_Booking extends SLN_Metabox_Abstract
{
    public function add_meta_boxes()
    {
        $pt = $this->getPostType();
        add_meta_box(
            $pt . '-details',
            __('Booking Details', 'sln'),
            array($this, 'details_meta_box'),
            $pt,
            'normal',
            'high'
        );
    }


    public function details_meta_box($object, $box)
    {
        $settings = $this->getPlugin()->getSettings();
        $booking  = $this->getPlugin()->createBooking($object);
        $pt       = $this->getPostType();
        $h        = new SLN_Metabox_Helper();
        $h->showNonce($pt);
        $h->showFieldtext(
            $h->getFieldName($pt, 'firstname'),
            __('Firstname', 'sln'),
            $booking->getFirstname(),
            'sln-col-x4'
        );
        $h->showFieldtext(
            $h->getFieldName($pt, 'lastname'),
            __('Lastname', 'sln'),
            $booking->getLastname(),
            'sln-col-x4'
        );
        $h->showFieldtext(
            $h->getFieldName($pt, 'email'),
            __('E-mail', 'sln'),
            $booking->getEmail(),
            'sln-col-x4'
        );
        $h->showFieldtext(
            $h->getFieldName($pt, 'phone'),
            __('Phone', 'sln'),
            $booking->getPhone(),
            'sln-col-x4'
        );
        $h->showFieldtext(
            $h->getFieldName($pt, 'amount'),
            __('Amount', 'sln') . ' (' . $settings->getCurrencySymbol() . ')',
            $booking->getAmount(),
            'sln-col-x4'
        );
        ?>
        <div class="sln_meta_field sln-col-x4">
            <label><?php _e('Status', 'sln'); ?></label>
            <?php SLN_Form::fieldSelect(
                $h->getFieldName($pt, 'status'),
                SLN_Enum_BookingStatus::toArray(),
                $booking->getStatus(),
                array('map' => true)
            ); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label><?php _e('Duration', 'sln'); ?></label>
            <?php SLN_Form::fieldTime(
                $h->getFieldName($pt, 'duration'),
                $booking->getDuration(),
                array('interval' => 10, 'maxItems' => 61)
            ); ?>
        </div>
        <div class="sln_meta_field sln-col-x2">
            <label><?php _e('Date', 'sln'); ?>
                <?php SLN_Form::fieldDate($h->getFieldName($pt, 'date'), $booking->getDate()); ?>
                <?php SLN_Form::fieldTime($h->getFieldName($pt, 'time'), $booking->getTime()); ?>
            </label>
        </div>
        <div class="sln-clear"></div>
        <div class="sln_meta_field">
            <label><?php _e('Services', 'sln'); ?></label><br/>
            <?php foreach ($this->getPlugin()->getServices() as $service) { ?>
                <label>
                    <?php SLN_Form::fieldCheckbox(
                        $h->getFieldName($pt, 'services[' . $service->getId() . ']'),
                        $booking->hasService($service)
                    ) ?>
                    <strong><?php echo $service->getName(); ?></strong>
                    <?php echo $service->getDuration()->format('H:i') ?>
                    <?php echo number_format($service->getPrice()) . ' ' . $settings->getCurrencySymbol() ?>
                    <?php echo $service->getContent() ?>
                </label><br/>
            <?php } ?>
        </div>
        <div class="sln-clear"></div>
        <?php do_action($pt . '_details_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        return array(
            'amount'    => 'money',
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'phone'     => '',
            'duration'  => 'time',
            'date'      => 'date',
            'time'      => 'time',
            'services'  => 'set',
            'status'  => ''
        );
    }


}
