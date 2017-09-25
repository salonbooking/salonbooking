<?php

class SLN_Plugin
{
    const POST_TYPE_SERVICE = 'sln_service';
    const POST_TYPE_ATTENDANT = 'sln_attendant';
    const POST_TYPE_BOOKING = 'sln_booking';
    const TAXONOMY_SERVICE_CATEGORY = 'sln_service_category';
    const USER_ROLE_STAFF = 'sln_staff';
    const USER_ROLE_CUSTOMER = 'sln_customer';
    const TEXT_DOMAIN = 'salon-booking-system';
    const F = 'slnc';
    const F1 = 100;
    const F2 = 60;
    const DEBUG_ENABLED = 0;
    const CATEGORY_ORDER = 'sln_service_category_order';

    private static $instance;
    private $settings;
    private $repositories;
    private $phpServices = array();

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct()
    {
        $obj = new SLN_Action_Init($this);
    }


    /** @return SLN_Settings */
    public function getSettings()
    {
        if (!isset($this->settings)) {
            $this->settings = new SLN_Settings();
        }

        return $this->settings;
    }

    /**
     * @param $attendant
     * @return SLN_Wrapper_Attendant
     * @throws Exception
     */
    public function createAttendant($attendant)
    {
        return $this->getRepository(self::POST_TYPE_ATTENDANT)->create($attendant);
    }

    /**
     * @param $service
     * @return SLN_Wrapper_Service
     * @throws Exception
     */
    public function createService($service)
    {
        return $this->getRepository(self::POST_TYPE_SERVICE)->create($service);
    }

    public function createBooking($booking)
    {
        if (is_string($booking) && strpos($booking, '-') !== false) {
            $booking = str_replace('?sln_step_page=thankyou', '',$booking);
            $secureId = $booking;
            $booking = intval($booking);
        }
        if (is_int($booking)) {
            $booking = get_post($booking);
        }
        $ret = new SLN_Wrapper_Booking($booking);
        if (isset($secureId) && $ret->getUniqueId() != $secureId) {
            throw new Exception('Not allowed, failing secure id');
        }

        return $ret;
    }

    /**
     * @return SLN_Wrapper_Booking_Builder
     */
    public function getBookingBuilder()
    {
        if(!isset($this->phpServices['bookingBuilder'])){
            $this->phpServices['bookingBuilder'] = new SLN_Wrapper_Booking_Builder($this);
        }
        return $this->phpServices['bookingBuilder'];
    }

    public function getViewFile($view)
    {
        return SLN_PLUGIN_DIR.'/views/'.$view.'.php';
    }

    public function loadView($view, $data = array())
    {
        return $this->templating()->loadView($view, $data);
    }

    public function sendMail($view, $data)
    {
        $data['data'] = $settings = new ArrayObject($data);
        $content = $this->loadView($view, $data);
        if (!function_exists('sln_html_content_type')) {

            function sln_html_content_type()
            {
                return 'text/html';
            }
        }

        add_filter('wp_mail_content_type', 'sln_html_content_type');
        $headers = 'From: '.$this->getSettings()->getSalonName().' <'.$this->getSettings()->getSalonEmail().'>'."\r\n";
        if(empty($settings['to'])){
            remove_filter('wp_mail_content_type', 'sln_html_content_type');
            return;
            //throw new Exception('Receiver not defined');
        }
        wp_mail($settings['to'], $settings['subject'], $content, $headers);
        remove_filter('wp_mail_content_type', 'sln_html_content_type');
    }

    /**
     * @return SLN_Formatter
     */
    public function format()
    {
        if (!isset($this->phpServices['formatter'])) {
            $this->phpServices['formatter'] = new SLN_Formatter($this);
        }

        return $this->phpServices['formatter'];
    }

    /**
     * @return SLN_Service_Templating
     */
    public function templating()
    {
        if ( ! isset($this->phpServices['templating'])) {
            $obj = new SLN_Service_Templating($this);
            $obj->addPath(SLN_PLUGIN_DIR.'/views/%s.php', 10);
            $this->phpServices['templating'] = $obj;
        }

        return $this->phpServices['templating'];
    }

    /**
     * @return SLN_Helper_Availability
     */
    public function getAvailabilityHelper()
    {
        if (!isset($this->phpServices['availabilityHelper'])) {
            $this->phpServices['availabilityHelper'] = new SLN_Helper_Availability($this);
        }

        return $this->phpServices['availabilityHelper'];
    }

    /**
     * @return SLN_Wrapper_Booking_Cache
     */
    public function getBookingCache()
    {
        if (!isset($this->phpServices['bookingCache'])) {
            $this->phpServices['bookingCache'] = new SLN_Wrapper_Booking_Cache($this);
        }

        return $this->phpServices['bookingCache'];
    }

    /**
     * @param Datetime $datetime
     * @return \SLN_Helper_Intervals
     */
    public function getIntervals(DateTime $datetime, $duration = null)
    {
        $obj = new SLN_Helper_Intervals($this->getAvailabilityHelper());
        $obj->setDatetime($datetime, $duration);

        return $obj;
    }

    public function ajax()
    {
        SLN_TimeFunc::startRealTimezone();
        //check_ajax_referer('ajax_post_validation', 'security');
        $method = $_REQUEST['method'];
        $className = 'SLN_Action_Ajax_'.ucwords($method);
        $classAltName = 'SLN_Action_Ajax_'.ucwords($method).'Alt';

        $isAlt = $this->getSettings()->isFormStepsAltOrder() && class_exists($classAltName);

        if ($isAlt || class_exists($className)) {
            if ($isAlt) {
                $className = $classAltName;
            }
            SLN_Plugin::addLog('calling ajax '.$className);
            //SLN_Plugin::addLog(print_r($_POST,true));
            /** @var SLN_Action_Ajax_Abstract $obj */
            $obj = new $className($this);
            $ret = $obj->execute();
            SLN_Plugin::addLog("$className returned:\r\n".json_encode($ret));
            if (is_array($ret)) {
                header('Content-Type: application/json');
                echo json_encode($ret);
            } elseif (is_string($ret)) {
                echo $ret;
            } else {
                throw new Exception("no content returned from $className");
            }
            exit();
        } else {
            throw new Exception("ajax method not found '$method'");
        }
    }

    public static function addLog($txt)
    {
        if (self::DEBUG_ENABLED) {
            file_put_contents(
                SLN_PLUGIN_DIR.'/log.txt',
                '['.date('Y-m-d H:i:s').'] '.$txt."\r\n",
                FILE_APPEND | LOCK_EX
            );
        }
    }

    /**
     * @param $post
     *
     * @return SLN_Wrapper_Abstract
     * @throws Exception
     */
    public function createFromPost($post)
    {
        if (!is_object($post)) {
            $post = get_post($post);
            if (!$post) {
                throw new Exception('post not found');
            }
        }

        return $this->getRepository($post->post_type)->create($post);
    }

    public function addRepository(SLN_Repository_AbstractRepository $repo)
    {
        foreach ($repo->getBindings() as $k) {
            $this->repositories[$k] = $repo;
        }
    }

    /**
     * @param $binding
     * @return SLN_Repository_AbstractRepository
     * @throws \Exception
     */
    public function getRepository($binding)
    {
        $ret = $this->repositories[$binding];
        if (!$ret) {
            throw new Exception(sprintf('repository for "%s" not found', $binding));
        }

        return $ret;
    }

    /**
     * @return SLN_Service_Sms
     */
    public function sms()
    {
        if (!isset($this->phpServices['sms'])) {
            $this->phpServices['sms'] = new SLN_Service_Sms($this);
        }

        return $this->phpServices['sms'];
    }

    /**
     * @return SLN_Service_Messages
     */
    public function messages()
    {
        if (!isset($this->phpServices['messages'])) {
            $this->phpServices['messages'] = new SLN_Service_Messages($this);
        }

        return $this->phpServices['messages'];
    }
}

function sln_sms_reminder()
{
    $obj = new SLN_Action_Reminder(SLN_Plugin::getInstance());
    $obj->executeSms();
}

function sln_email_reminder()
{
    $obj = new SLN_Action_Reminder(SLN_Plugin::getInstance());
    $obj->executeEmail();
}

function sln_sms_followup()
{
    $obj = new SLN_Action_FollowUp(SLN_Plugin::getInstance());
    $obj->executeSms();
}

function sln_email_followup()
{
    $obj = new SLN_Action_FollowUp(SLN_Plugin::getInstance());
    $obj->executeEmail();
}

function sln_email_feedback()
{
    $obj = new SLN_Action_Feedback(SLN_Plugin::getInstance());
    $obj->execute();
}

function sln_cancel_bookings()
{
    $obj = new SLN_Action_CancelBookings(SLN_Plugin::getInstance());
    $obj->execute();
}

function sln_email_weekly_report()
{
    $obj = new SLN_Action_WeeklyReport(SLN_Plugin::getInstance());
    $obj->executeEmail();
}
