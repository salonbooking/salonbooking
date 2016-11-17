<?php

class SLN_Metabox_Attendant extends SLN_Metabox_Abstract
{
    protected $fields = array(
        'availabilities'  => '',
        'holidays'        => '',
        'email'           => 'text',
        'phone'           => 'text',
        'services'        => 'nofilter',
        'google_calendar' => 'nofilter',
    );

    public function add_meta_boxes()
    {
        $postType = $this->getPostType();
        add_meta_box(
//            $postType . '-details',
            'sln_service-details',
            __('Assistant Details', 'salon-booking-system'),
            array($this, 'details_meta_box'),
            $postType,
            'normal',
            'high'
        );
        if ($this->getPlugin()->getSettings()->get('google_calendar_enabled')) {
            add_meta_box(
                'sln_attendant-gcalendar',
                __('Assistant Google Calendar', 'salon-booking-system'),
                array($this, 'gcalendar_meta_box'),
                $postType,
                'side',
                'low'
            );
        }
        remove_meta_box('postexcerpt', $postType, 'side');
        add_meta_box(
            'postexcerpt',
            __('Assistant description'),
            array($this, 'post_excerpt_meta_box'),
            $postType,
            'normal',
            'high'
        );
    }

    public function gcalendar_meta_box($object)
    {
        $attendant = $this->getPlugin()->createAttendant($object);
        echo $this->getPlugin()->loadView('metabox/attendant_gcalendar', compact('attendant'));

    }

    public function post_excerpt_meta_box($post)
    {
        echo $this->getPlugin()->loadView('metabox/attendant_description', compact('post'));

        ?>
        <label class="screen-reader-text" for="excerpt">
            <?php _e('Assistant Description', 'salon-booking-system') ?>
        </label>
        <textarea rows="1" cols="40" name="excerpt"
                  id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
        <p><?php _e('A very short description of this assistant. It is optional', 'salon-booking-system'); ?></p>
    <?php
    }


    public function details_meta_box($object, $box)
    {
        echo $this->getPlugin()->loadView(
            'metabox/attendant',
            array(
                'metabox'  => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'attendant'  => $this->getPlugin()->createAttendant($object),
                'postType' => $this->getPostType(),
                'helper'   => new SLN_Metabox_Helper()
            )
        );
        do_action($this->getPostType() . '_details_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        return apply_filters('sln.metabox.attendant.getFieldList',$this->fields);
    }

    public function save_post($post_id, $post)
    {
        if (!$this->getPlugin()->getSettings()->get('google_calendar_enabled')) {
            unset($this->fields['google_calendar']);
        }
        $k = '_sln_attendant_availabilities';
        if(isset($_POST[$k]))
            $_POST[$k] = SLN_Helper_AvailabilityItems::processSubmission($_POST[$k]); 
        $k = '_sln_attendant_holidays';
        if(isset($_POST[$k]))
            $_POST[$k] = SLN_Helper_HolidayItems::processSubmission($_POST[$k]);
        $k = '_sln_attendant_services';
        if(isset($_POST[$k])) {
            foreach($_POST[$k] as $kk => $vv){
                $_POST[$k][$kk] = str_replace($k.'_','', $vv);
            }
        }
        parent::save_post($post_id, $post);
    }


    protected function enqueueAssets()
    {
        parent::enqueueAssets();
        SLN_Action_InitScripts::enqueueCustomSliderRange();
    }
}
