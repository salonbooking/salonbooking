<?php

class SLN_Metabox_Service extends SLN_Metabox_Abstract
{
    public function add_meta_boxes()
    {
        $postType = $this->getPostType();
        add_meta_box(
            $postType . '-details',
            __('Service Details', 'sln'),
            array($this, 'details_meta_box'),
            $postType,
            'normal',
            'high'
        );
        remove_meta_box('postexcerpt', $postType, 'side');
        add_meta_box(
            'postexcerpt',
            __('Service description'),
            array($this, 'post_excerpt_meta_box'),
            $postType,
            'normal',
            'high'
        );
    }

    public function post_excerpt_meta_box($post)
    {
        ?>
        <label class="screen-reader-text" for="excerpt">
            <?php _e('Service Description', 'sln') ?>
        </label>
        <textarea rows="1" cols="40" name="excerpt"
                  id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
        <p><?php _e('something different', 'sln'); ?></p>
    <?php
    }


    public function details_meta_box($object, $box)
    {
        $service  = $this->getPlugin()->createService($object);
        $settings = $this->getPlugin()->getSettings();
        $pt       = $this->getPostType();
        $h        = new SLN_Metabox_Helper();
        $h->showNonce($pt);
        $h->showFieldText(
            $h->getFieldName($pt, 'price'),
            __('Price', 'sln') . ' (' . $settings->getCurrencySymbol() . ')',
            $service->getPrice(),
            'sln-col-x4'
        );
        ?>
        <div class="sln_meta_field sln-col-x4">
            <label><?php _e('Unit per hour', 'sln'); ?></label>
            <?php SLN_Form::fieldNumeric($h->getFieldName($pt, 'unit'), $service->getUnit()); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label><?php _e('Duration', 'sln'); ?></label>
            <?php SLN_Form::fieldTime($h->getFieldName($pt, 'duration'), $service->getDuration()); ?>
        </div>
        <div class="sln_meta_field sln-col-x4">
            <label><?php _e('Secondary', 'sln'); ?></label>
            <?php SLN_Form::fieldCheckbox($h->getFieldName($pt, 'secondary'), $service->isSecondary()) ?>
            <br/><em>Select this if you want this service considered as secondary level service</em>
        </div>
        <div class="sln-clear"></div>
        <h3>Not Available At</h3>
        <?php
        $days = SLN_Func::getDays();
        ?>
        <div class="sln_meta_field  sln-col-x2">
            <?php foreach ($days as $k => $day) { ?>
                <label>
                    <?php SLN_Form::fieldCheckbox(
                        $h->getFieldName($pt, 'notav_' . $k),
                        $service->getNotAvailableOn($k)
                    ) ?>
                    <?php echo substr($day, 0, 3) ?>
                </label>
            <?php } ?>
        </div>
        <div class="sln_meta_field  sln-col-x2">
            <label>
                <?php echo __('From', 'sln') ?>
                <?php SLN_Form::fieldTime($h->getFieldName($pt, 'notav_from'), $service->getNotAvailableFrom()) ?>
            </label>
            <label>
                <?php echo __('To', 'sln') ?>
                <?php SLN_Form::fieldTime($h->getFieldName($pt, 'notav_to'), $service->getNotAvailableTo()) ?>
            </label>
        </div>
        <em>Leave blank if you want this service available everydays at every hour</em>
        <div class="sln-clear"></div>
        <?php do_action($pt . '_details_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        return array(
            'price'      => 'float',
            'duration'   => 'time',
            'secondary'  => 'bool',
            'unit'       => 'int',
            'notav_from' => 'time',
            'notav_to'   => 'time',
            'notav_1'    => 'bool',
            'notav_2'    => 'bool',
            'notav_3'    => 'bool',
            'notav_4'    => 'bool',
            'notav_5'    => 'bool',
            'notav_6'    => 'bool',
            'notav_7'    => 'bool',
        );
    }
}
