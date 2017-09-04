<?php
/**
 * @var SLN_Plugin $plugin
 * @var SLN_Settings $settings
 * @var SLN_Metabox_Helper $helper
 * @var SLB_Discount_Wrapper_Discount $discount
 * @var string $postType
 *
 */
$helper->showNonce($postType);
?>

<div class="row">
<!-- default settings -->
    <div class="col-sm-6 col-md-4 form-group sln-input--simple">
        <label><?php echo __('Amount', 'salon-booking-system') ?></label>
        <?php SLN_Form::fieldText($helper->getFieldName($postType, 'amount'), $discount->getAmount()); ?>
    </div>
    <div class="col-sm-6 col-md-4 form-group sln-select">
        <label><?php _e('Type', 'salon-booking-system'); ?></label>
        <?php SLN_Form::fieldSelect(
            $helper->getFieldName($postType, 'amount_type'),
            array(
                'fixed'      => $settings->getCurrency() . ' (' . $settings->getCurrencySymbol() . ')',
                'percentage' => __('%', 'salon-booking-system'),
            ),
            $discount->getAmountType(),
            array(),
            true
        ); ?>
        <p><?php _e('Type the amount of this discount','salon-booking-system'); ?></p>
    </div>
    <div class="col-sm-6 col-md-4 form-group sln-input--simple">
        <label><?php _e('Maximum uses limit', 'salon-booking-system'); ?></label>
        <?php SLN_Form::fieldText($helper->getFieldName($postType, 'usages_limit_total'), $discount->getTotalUsagesLimit()); ?>
        <p><?php _e('Leave it blank for an unlimited times of usage','salon-booking-system'); ?></p>
    </div>
    <div class="sln-clear"></div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-4 form-group sln-slider-wrapper">
        <label><?php echo __('Valid from', 'salon-booking-system') ?></label>
        <div class="sln_datepicker">
            <?php SLN_Form::fieldJSDate(
                $helper->getFieldName($postType, 'from'),
                $discount->getStartsAt()
            ) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 form-group sln-slider-wrapper">
        <label><?php _e('To', 'salon-booking-system'); ?></label>
        <div class="sln_datepicker">
            <?php SLN_Form::fieldJSDate(
                $helper->getFieldName($postType, 'to'),
                $discount->getEndsAt()
            ) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 form-group sln-input--simple">
        <label><?php _e('Per single user limit', 'salon-booking-system'); ?></label>
        <?php SLN_Form::fieldText($helper->getFieldName($postType, 'usages_limit'), $discount->getUsagesLimit()); ?>
        <p><?php _e('Leave it blank for an unlimited times of usage','salon-booking-system'); ?></p>
    </div>
    <div class="sln-clear"></div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-8 form-group sln-select">
        <label><?php echo __('Limit this discount to the following services', 'salon-booking-system') ?></label>
        <?php
        /** @var SLN_Wrapper_Service[] $services */
        $services = $plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE)->getAll();
        $items    = array();
        foreach($services as $s) {
            $items[$s->getId()] = $s->getName();
        }
        SLN_Form::fieldSelect(
            $helper->getFieldName($postType, 'services[]'),
            $items,
            (array)$discount->getMeta('services'),
            array('attrs' => array('multiple' => true, 'data-containerCssClass' => 'sln-select-wrapper-no-search')),
            true
        ); ?>
        <p><?php _e('Leave it blank if you want to be applied to all services','salon-booking-system'); ?></p>
    </div>
    <div class="sln-clear"></div>
</div>

<div class="sln-clear"></div>
<?php do_action('sln.template.discount_details.metabox', $discount); ?>