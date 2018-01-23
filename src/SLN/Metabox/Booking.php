<?php

class SLN_Metabox_Booking extends SLN_Metabox_Abstract
{
    /** @var  SLN_Wrapper_Booking */
    private $booking;
    /** @var string */
    private $prevStatus;

    protected $fields = array(
            'amount' => 'float',
            'deposit' => 'float',
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'date' => 'date',
            'time' => 'time',
            'services' => 'nofilter',
            'note' => '',
            'admin_note' => '',
            '_sln_calendar_event_id' => '',
        );

    public function add_meta_boxes()
    {
        $pt = $this->getPostType();
        add_meta_box(
            $pt.'-details',
            __('Booking details', 'salon-booking-system'),
            array($this, 'details_meta_box'),
            $pt,
            'normal',
            'high'
        );
    }


    protected function init()
    {
        parent::init();
        add_action('load-post.php', array($this, 'hookLoadPost'));
    }

    public function hookLoadPost()
    {
        if (
            (isset($_GET['post_type']) && $_GET['post_type'] == $this->getPostType())
            || (isset($_POST['post_type']) && $_POST['post_type'] == $this->getPostType())
        ) {
            $this->getPlugin()->messages()->setDisabled(true);
            if (isset($_GET['post'])) {
                $this->booking = $this->getPlugin()->createFromPost($_GET['post']);
                $this->prevStatus = $this->booking->getStatus();
            }
            if (isset($_POST['post_ID'])) {
                $this->booking = $this->getPlugin()->createFromPost($_POST['post_ID']);
                $this->prevStatus = $this->booking->getStatus();
            }

            return;
        }
    }

    public function details_meta_box($object, $box)
    {

        echo $this->getPlugin()->loadView(
            'metabox/booking',
            array(
                'metabox' => $this,
                'settings' => $this->getPlugin()->getSettings(),
                'booking' => $this->getPlugin()->createBooking($object),
                'postType' => $this->getPostType(),
                'helper' => new SLN_Metabox_Helper(),
                'mode' => isset($_GET['mode']) ? $_GET['mode'] : '',
                'date' => isset($_GET['date']) ? new SLN_DateTime($_GET['date']) : null,
                'time' => isset($_GET['time']) ? new SLN_DateTime($_GET['time']) : null,
            )
        );
        do_action($this->getPostType().'_details_meta_box', $object, $box);
    }

    protected function getFieldList()
    {
        return apply_filters('sln.metabox.booking.getFieldList',$this->fields);
    }

    private $disabledSavePost = false;

    public function save_post($post_id, $post)
    {
        if (
            get_post_field('post_type', $post_id) !== SLN_Plugin::POST_TYPE_BOOKING
            || $this->disabledSavePost
            || !isset($_POST['_sln_booking_status'])
        ) {
            return;
        }

        $_POST['_sln_booking_services'] = $this->processServicesSubmission($_POST['_sln_booking']);
        parent::save_post($post_id, $post);
        $this->validate($_POST);
        if (isset($_SESSION['_sln_booking_user_errors'])) {
            return;
        }

        /** @var SLN_Wrapper_Booking $booking */
        $booking = $this->getPlugin()->createFromPost($post_id);
        $booking->evalBookingServices();
        $booking->evalTotal();
        $booking->evalDuration();
        $this->disabledSavePost = true;
        $booking->setStatus($booking->getStatus());
        $this->disabledSavePost = false;
        $s = $booking->getStatus();
        $new = $_POST['_sln_booking_status'];
        if (strpos($new, 'sln-b-') !== 0) {
            $new = SLN_ENUM_BookingStatus::PENDING_PAYMENT;
        }
        $postnew = array(
            'ID' => $post_id,
            'post_status' => $new,
        );
        $createUser = isset($_POST['_sln_booking_createuser']) ? $_POST['_sln_booking_createuser'] : false;
        if ($createUser) {
            $userid = $this->registration($_POST);
            if ($userid instanceof WP_Error) {
                return;
            }
            $postnew = array_merge(
                $postnew,
                array(
                    'ID' => $post_id,
                    'post_author' => $userid,
                )
            );
        }
        if (!empty($postnew)) {
            $this->disabledSavePost = true;
            $booking->setStatus($new);
            wp_update_post($postnew);
            $this->disabledSavePost = false;
        }
        $this->addCustomerRole($booking);
        $booking->reload();
        if ($this->prevStatus != $booking->getStatus()) {
            $m = $this->getPlugin()->messages();
            $m->setDisabled(false);
            $m->sendByStatus($booking, $booking->getStatus());
        }
        $this->getPlugin()
            ->getBookingCache()
            ->processBooking($booking, false);
    }

    private function addCustomerRole($booking)
    {
        $user = new WP_User($booking->getUserId());
        $isNotAdmin = array_search('administrator', $user->roles) === false;
        $isNotSubscriber = array_search('subscriber', $user->roles) !== false;
        if ($isNotAdmin && $isNotSubscriber) {
            wp_update_user(
                array(
                    'ID' => $booking->getUserId(),
                    'role' => SLN_Plugin::USER_ROLE_CUSTOMER,
                )
            );
        }
    }

    private function processServicesSubmission($data)
    {
        $services = array();
        foreach ($data['service'] as $serviceId) {
            $duration      = SLN_Func::convertToHoursMins($data['duration'][$serviceId]);
            $breakDuration = SLN_Func::convertToHoursMins($data['break_duration'][$serviceId]);

            $attendant = isset($data['attendants']) ? $data['attendants'][$serviceId] : (isset($data['attendant']) ? $data['attendant'] : null);
            $services[$serviceId] = array(
                'service' => $serviceId,
                'attendant' => $attendant,
                'price' => $data['price'][$serviceId],
                'duration' => $duration,
                'break_duration' => $breakDuration,
            );
        }
        return $services;
    }

    protected function registration($data)
    {
        $errors = wp_create_user($data['_sln_booking_email'], wp_generate_password(), $data['_sln_booking_email']);
        if (!is_wp_error($errors)) {
            wp_update_user(
                array(
                    'ID' => $errors,
                    'first_name' => $data['_sln_booking_firstname'],
                    'last_name' => $data['_sln_booking_lastname'],
                    'role' => SLN_Plugin::USER_ROLE_CUSTOMER,
                )
            );
            add_user_meta($errors, '_sln_phone', $data['_sln_booking_phone']);
            add_user_meta($errors, '_sln_address', $data['_sln_booking_address']);

            wp_new_user_notification($errors, null, 'both'); //, $values['password']);
        } else {
            $this->addError($errors->get_error_message());
        }

        return $errors;
    }

    private function validate($values)
    {
        if (SLN_Enum_CheckoutFields::isRequired('firstname') && empty($values['_sln_booking_firstname'])) {
            $this->addError(__('First name can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('lastname') && empty($values['_sln_booking_lastname'])) {
            $this->addError(__('Last name can\'t be empty', 'salon-booking-system'));
        }
        if (isset($_POST['_sln_booking_createuser']) && $_POST['_sln_booking_createuser']) {
            if (SLN_Enum_CheckoutFields::isRequired('email') && empty($values['_sln_booking_email'])) {
                $this->addError(__('e-mail can\'t be empty', 'salon-booking-system'));
            }
            if (SLN_Enum_CheckoutFields::isRequired('phone') && empty($values['_sln_booking_phone'])) {
                $this->addError(__('Mobile phone can\'t be empty', 'salon-booking-system'));
            }
            $fields = SLN_Enum_CheckoutFields::toArray('additional');
            foreach ($fields as $field => $label) {
                if (SLN_Enum_CheckoutFields::isRequired($field) && empty($values['_sln_booking_'.$field])){
                    $this->addError(__($label.' can\'t be empty', 'salon-booking-system'));
                }
            }
        }

        if (SLN_Enum_CheckoutFields::isRequired('email') && !empty($values['_sln_booking_email']) && !filter_var(
                $values['_sln_booking_email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $this->addError(__('e-mail is not valid', 'salon-booking-system'));
        }
    }

    protected function addError($message)
    {
        $_SESSION['_sln_booking_user_errors'][] = $message;
    }


    protected function enqueueAssets()
    {
        parent::enqueueAssets();
        wp_enqueue_script(
            'salon-customBookingUser',
            SLN_PLUGIN_URL.'/js/admin/customBookingUser.js',
            array('jquery'),
            SLN_Action_InitScripts::ASSETS_VERSION,
            true
        );
    }

}

