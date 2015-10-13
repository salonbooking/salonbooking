<?php
if (!isset($_SESSION))
    session_start();

function _pre($m) {
    echo "<pre>";
    print_r($m);
    echo "</pre>";
}

function sln_my_wp_log($message, $file = null, $level = 1) {
    // full path to log file
    if ($file == null) {
        $file = 'debug.log';
    }

    $file = SLN_PLUGIN_DIR . DIRECTORY_SEPARATOR . "src/SLN/Third/" . $file;

    /* backtrace */
    $bTrace = debug_backtrace(); // assoc array

    /* Build the string containing the complete log line. */
    $line = PHP_EOL . sprintf('[%s, <%s>, (%d)]==> %s', date("Y/m/d h:i:s" /* ,time() */), basename($bTrace[0]['file']), $bTrace[0]['line'], print_r($message, true));

    if ($level > 1) {
        $i = 0;
        $line.=PHP_EOL . sprintf('Call Stack : ');
        while (++$i < $level && isset($bTrace[$i])) {
            $line.=PHP_EOL . sprintf("\tfile: %s, function: %s, line: %d" . PHP_EOL . "\targs : %s", isset($bTrace[$i]['file']) ? basename($bTrace[$i]['file']) : '(same as previous)', isset($bTrace[$i]['function']) ? $bTrace[$i]['function'] : '(anonymous)', isset($bTrace[$i]['line']) ? $bTrace[$i]['line'] : 'UNKNOWN', print_r($bTrace[$i]['args'], true));
        }
        $line.=PHP_EOL . sprintf('End Call Stack') . PHP_EOL;
    }
    // log to file
    file_put_contents($file, $line, FILE_APPEND);

    return true;
}

sln_my_wp_log("init googlescope class");

//Add to htaccess RewriteRule ^wp-admin/salon-settings/(.*)/$ /wp-admin/admin.php?page=salon-settings&tab=$1 [L]
//if (!file_exists(SLN_PLUGIN_DIR . '/src/SLN/Third/google-api-php-client/src/Google/autoload.php')) die('error');
require SLN_PLUGIN_DIR . '/src/SLN/Third/google-api-php-client/src/Google/autoload.php';
require_once SLN_PLUGIN_DIR . '/src/SLN/Third/google-api-php-client/src/Google/Client.php';
require_once SLN_PLUGIN_DIR . '/src/SLN/Third/google-api-php-client/src/Google/Service/Calendar.php';

class SLN_GoogleScope {

    public $date_offset = 0;
    public $client_id = '102246196260-so9c267umku08brmrgder71ige08t3nm.apps.googleusercontent.com'; //change this
    public $email_address = '102246196260-so9c267umku08brmrgder71ige08t3nm@developer.gserviceaccount.com'; //change this    
    public $scopes = "https://www.googleapis.com/auth/calendar";
    public $key_file_location = 'google-api-php-client/gc.p12'; //change this           
    public $outh2_client_id = "102246196260-hjpu1fs2rh5b9mesa9l5urelno396vc0.apps.googleusercontent.com";
    public $outh2_client_secret = "AJzLfWtRDz53JLT5fYp5gLqZ";
    public $outh2_redirect_uri;
    public $google_calendar_enabled = false;
    public $google_client_calendar;
    public $client;
    public $service;
    public $settings;

    /**
     * __construct
     */
    public function __construct() {
        if (is_admin()) {
            add_action('wp_ajax_googleoauth-callback', array($this, 'get_client'));
            add_action('wp_ajax_nopriv_googleoauth-callback', array($this, 'get_client'));
        }
    }

    /**
     * wp_init
     * @param type $plugin
     */
    public function wp_init() {

        $this->google_calendar_enabled = $this->settings->get('google_calendar_enabled');
        $this->google_client_calendar = $this->settings->get('google_client_calendar');

        if (isset($this->settings)) {
            $this->outh2_client_id = $this->settings->get('google_outh2_client_id');
            $this->outh2_client_secret = $this->settings->get('google_outh2_client_secret');
            $this->outh2_redirect_uri = $this->settings->get('google_outh2_redirect_uri');
            if (/* $this->google_calendar_enabled && */
                    (!empty($this->outh2_client_id) &&
                    !empty($this->outh2_client_secret) &&
                    !empty($this->outh2_redirect_uri))
            ) {
                if ($this->google_calendar_enabled)
                    $this->start_auth();
                else
                    $this->start_auth(true);
            }
        }
    }

    /**
     * start_auth
     */
    public function start_auth($force_revoke_token = false) {
        $access_token = (isset($_SESSION['access_token']) && !empty($_SESSION['access_token']));
        if (!isset($access_token) || empty($access_token)) {
            $access_token = $this->settings->get('sln_access_token');
        }
        if (isset($access_token) && !empty($access_token)) {
            if ($force_revoke_token || (isset($_GET['revoketoken']) && $_GET['revoketoken'] == 1)/* || $client->isAccessTokenExpired() */) {
                $res = wp_remote_get("https://accounts.google.com/o/oauth2/revoke?token={$access_token}");
                $this->settings->set('sln_refresh_token', '');
                $this->settings->set('sln_access_token', '');
                $this->settings->save();
                unset($_SESSION['access_token']);
                header("Location: " . admin_url('admin.php?page=salon-settings&tab=gcalendar'));
            }

            $client = new Google_Client();
            $client->setClientId($this->outh2_client_id);
            $client->setClientSecret($this->outh2_client_secret);
            $client->setRedirectUri(isset($this->outh2_redirect_uri) ? $this->outh2_redirect_uri : admin_url('admin-ajax.php?action=googleoauth-callback'));
            $client->setAccessType('offline');
            $client->addScope($this->scopes);
            if ($this->is_connected())
                $client->setAccessToken($access_token);

            $this->client = $client;
            $this->service = $this->get_google_service();
        } else {
            if (!$force_revoke_token) {
                $loginUrl = 'https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=' . $this->outh2_client_id . '&redirect_uri=' . $this->outh2_redirect_uri . '&scope=' . $this->scopes . '&&access_type=offline&approval_prompt=force';
                header("Location: " . $loginUrl);
            }
        }
    }

    /**
     * get_client
     */
    public function get_client() {
        if (isset($_GET['error'])) {
            header("Location: " . admin_url('admin.php?page=salon-settings&tab=gcalendar'));
        }

        $code = isset($_GET['code']) ? $_GET['code'] : null;

        if (isset($code)) {
            $oauth_result = wp_remote_post("https://accounts.google.com/o/oauth2/token", array(
                'body' => array(
                    'code' => $code,
                    'client_id' => $this->outh2_client_id,
                    'client_secret' => $this->outh2_client_secret,
                    'redirect_uri' => isset($this->outh2_redirect_uri) ? $this->outh2_redirect_uri : admin_url('admin-ajax.php?action=googleoauth-callback'),
                    'grant_type' => 'authorization_code'
                )
            ));

            if (!is_wp_error($oauth_result)) {
                $oauth_response = json_decode($oauth_result['body'], true);
            } else {
                _pre($oauth_result);
                die();
            }

            if (isset($oauth_response['access_token'])) {
                //refresh_token is present with only login setting approval_prompt=force
                $oauth_refresh_token = $oauth_response['refresh_token'];
                $oauth_token_type = $oauth_response['token_type'];
                $oauth_access_token = $oauth_response['access_token'];
                $oauth_expiry = $oauth_response['expires_in'] + current_time('timestamp', true);
                $idtoken_validation_result = wp_remote_get('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $oauth_access_token);

                $_SESSION['access_token'] = $oauth_access_token;
                $_SESSION['refresh_token'] = $oauth_refresh_token;
                $this->settings->set('sln_access_token', $oauth_access_token);
                $this->settings->set('sln_refresh_token', $oauth_refresh_token);
                $this->settings->save();

                if (!is_wp_error($idtoken_validation_result)) {
                    $idtoken_response = json_decode($idtoken_validation_result['body'], true);
                    setcookie('google_oauth_id_token', $oauth_access_token, $oauth_expiry, COOKIEPATH, COOKIE_DOMAIN);
                    //setcookie('google_oauth_username', $oauth_username, (time() + ( 86400 * 7)), COOKIEPATH, COOKIE_DOMAIN);
                } else {
                    _pre($idtoken_validation_result);
                    die();
                }
            } else {
                _pre($oauth_response);
                die();
            }
        } else {
            $this->start_auth();
        }
        header("Location: " . admin_url('admin.php?page=salon-settings&tab=gcalendar'));
    }

    /**
     * set_settings_by_plugin
     * @param type $plugin
     */
    public function set_settings_by_plugin($plugin) {
        $this->settings = $plugin->getSettings();
    }

    /**
     * get_google_service creates and return the google service
     * @return \Google_Service_Calendar
     */
    public function get_google_service() {
        return new Google_Service_Calendar($this->client);
    }

    /**
     * start_auth init the login to google services
     */
    public function start_auth_assertion() {
        $key = file_get_contents($this->key_file_location);
        $cred = new Google_Auth_AssertionCredentials(
                $this->email_address, array($this->scopes), $key
        );
        $this->client->setAssertionCredentials($cred);
        if ($this->client->getAuth()->isAccessTokenExpired()) {
            $this->client->getAuth()->refreshTokenWithAssertion($cred);
        }
    }

    /**
     * is_connected
     * @return boolean
     */
    public function is_connected() {
        $ret = (isset($this->client) && !$this->client->getAuth()->isAccessTokenExpired());
        if (!$ret) {
            $refresh_token = isset($_SESSION['refresh_token']) ? $_SESSION['refresh_token'] : "";
            if (!isset($refresh_token) || empty($refresh_token))
                $refresh_token = $this->settings->get('sln_refresh_token');

            try {
                if (isset($this->client))
                    $this->client->refreshToken($refresh_token);
                else
                    return false;
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
        return $ret;
    }

    /**
     * get_calendar_list
     * @return type
     */
    public function get_calendar_list() {
        $cal_list = null;

        if ($this->is_connected()) {

            $calendarList = $this->service->calendarList->listCalendarList();
            $cal_list = array();
            while (true) {
                foreach ($calendarList->getItems() as $calendarListEntry) {
                    if (!isset($cal_list[$calendarListEntry->getId()]))
                        $cal_list[$calendarListEntry->getId()] = array();
                    $cal_list[$calendarListEntry->getId()]['id'] = $calendarListEntry->getId();
                    $cal_list[$calendarListEntry->getId()]['label'] = $calendarListEntry->getSummary();
                }
                $pageToken = $calendarList->getNextPageToken();
                if ($pageToken) {
                    $optParams = array('pageToken' => $pageToken);
                    $calendarList = $this->service->calendarList->listCalendarList($optParams);
                } else {
                    break;
                }
            }
        }

        return $cal_list;
    }

    /**
     * create_event
     * @param type $params
      array(
      'email' => $email,
      'title' => $title,
      'location' => $location,
      'date_start' => $date_start,
      'time_start' => $time_start,
      'date_end' => $date_end,
      'time_end' => $time_end,
      );
     * @return type
     */
    public function create_event($params) {
        extract($params);

        $catId = isset($params['catId']) && !empty($params['catId']) ? $params['catId'] : "primary";

        $event = new Google_Service_Calendar_Event();
        $event->setSummary($title);
        $event->setLocation($location);
        $start = new Google_Service_Calendar_EventDateTime();
        //$dateTimeS = gtime(set_data($date_start), $time_start);
        //$dateTimeS = date(DATE_ATOM, strtotime($date_start . " " . $time_start));
        $str_date = strtotime($date_start . " " . $time_start);
        $dateTimeS = self::date3339($str_date, $this->date_offset);

        $start->setDateTime($dateTimeS);
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        //$dateTimeE = gtime(set_data($date_end), $time_end);
        //$dateTimeE = date(DATE_ATOM, strtotime($date_end . " " . $time_end));
        $str_date = strtotime($date_end . " " . $time_end);
        $dateTimeE = self::date3339($str_date, $this->date_offset);

        $end->setDateTime($dateTimeE);
        $event->setEnd($end);

        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail($email);
        $attendees = array($attendee1);

        $event->attendees = $attendees;
        $createdEvent = $this->service->events->insert($catId, $event);

        return $createdEvent->getId();
    }

//    function addAttachment($calendarService, $driveService, $calendarId, $eventId, $fileId) {
//        $file = $driveService->files->get($fileId);
//        $event = $calendarService->events->get($calendarId, $eventId);
//        $attachments = $event->attachments;
//
//        $attachments[] = array(
//            'fileUrl' => $file->alternateLink,
//            'mimeType' => $file->mimeType,
//            'title' => $file->title
//        );
//        $changes = new Google_Service_Calendar_Event(array(
//            'attachments' => $attachments
//        ));
//
//        $calendarService->events->patch($calendarId, $eventId, $changes, array(
//            'supportsAttachments' => TRUE
//        ));
//    }

    /**
     * delete_event
     * @param type $event_id
     * @return type
     */
    public function delete_event($event_id, $catId = 'primary') {
        //return $this->service->events->delete('primary', $_SESSION['eventID']);
        return $this->service->events->delete($catId, $event_id);
    }

    /**
     * 
     * @param type $params
      array(
      'email' => $email,
      'title' => $title,
      'location' => $location,
      'date_start' => $date_start,
      'time_start' => $time_start,
      'date_end' => $date_end,
      'time_end' => $time_end,
      );
     * @return type
     */
    public function update_event($params) {
        extract($params);

        $catId = isset($params['catId']) && !empty($params['catId']) ? $params['catId'] : "primary";

        $rule = $this->service->events->get($catId, $event_id);

        $event = new Google_Service_Calendar_Event();
        $event->setSummary($title);
        $event->setLocation($location);
        $start = new Google_Service_Calendar_EventDateTime();
        //$dateTimeS = gtime(set_data($date_start), $time_start);
        //$dateTimeS = date(DATE_ATOM, strtotime($date_start . " " . $time_start));
        $str_date = strtotime($date_start . " " . $time_start);
        $dateTimeS = self::date3339($str_date, $this->date_offset);

        $start->setDateTime($dateTimeS);
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        //$dateTimeE = gtime(set_data($date_end), $time_end);
        //$dateTimeE = date(DATE_ATOM, strtotime($date_end . " " . $time_end));
        $str_date = strtotime($date_end . " " . $time_end);
        $dateTimeE = self::date3339($str_date, $this->date_offset);

        $end->setDateTime($dateTimeE);
        $event->setEnd($end);
        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail($email); //change this
        $attendees = array($attendee1);
        $event->attendees = $attendees;

        $updatedRule = $this->service->events->update($catId, $rule->getId(), $event);
        return $updatedRule;
    }

    /**
     * get_list_event return all the event for primary calendar
     */
    public function get_list_event($calId = 'primary') {
        $events = $this->service->events->listEvents($calId);
        while (true) {
            foreach ($events->getItems() as $event) {
                echo "<br>" . $event->getId() . " " . $event->getSummary();
                //$this->delete_event($event->getId());
            }
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = $this->service->events->listEvents($calId, $optParams);
            } else {
                break;
            }
        }
    }

    /**
     * 
     * @param type $params
     *         $params = array(
      'height' => 600,
      'width' => 800,
      'wkst' => 1,
      'bgcolor' => '#FFFFFF',
      'color' => '#29527A',
      'src' => 'magikboo23@gmail.com',
      'ctz' => 'Europe/Rome'
      );
     */
    public function print_calendar_by_calendar_id($params) {
        $str = "?";
        $i = 0;
        foreach ($params as $k => $v) {
            if ($i <= 0)
                $str .= "$k=" . urlencode($v);
            else
                $str .= "&amp;$k=" . urlencode($v);
            $i++;
        }
        ?>
        <iframe src="https://www.google.com/calendar/embed?<?php echo $str; ?>" style=" border-width:0 " width="800" height="600" frameborder="0" scrolling="no"></iframe>
        <?php
    }

    /**
     * date3339 transform timestamp into google calendar date compliant
     * @param type $timestamp
     * @param type $offset
     * @return string
     */
    public static function date3339($timestamp = 0, $offset = 0) {
        if (!$timestamp)
            return "error";
        $date = date('Y-m-d\TH:i:s', $timestamp);
        $date .= sprintf(".000%+03d:%02d", intval($offset), abs($offset - intval($offset)) * 60);
        return $date;
    }

    /**
     * create_event_from_booking
     * @param type $booking
     * @return type
     */
    public function create_event_from_booking($booking) {
        if (!$this->is_connected())
            return;

        $gc_event = new SLN_GoogleCalendarEventFactory();
        $event = $gc_event->get_event($booking);

        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail($this->google_client_calendar);
        $attendees = array($attendee1);

        $event->attendees = $attendees;
        $createdEvent = $this->service->events->insert($this->google_client_calendar, $event);

        return $createdEvent->getId();
    }

}

class SLN_GoogleCalendarEventFactory extends Google_Service_Calendar_Event {

    public function get_event($booking) {
        $event = new Google_Service_Calendar_Event();
        $event->setSummary($booking->getTitle());
        $event->setDescription($booking->getNote() . " " . $booking->getPhone());
        $event->setLocation($booking->getAddress());

        $start = new Google_Service_Calendar_EventDateTime();
        $str_date = strtotime($booking->getStartsAt()->format('Y-m-d H:i:s'));
        $dateTimeS = SLN_GoogleScope::date3339($str_date);
        $start->setDateTime($dateTimeS);

        $event->setStart($start);

        $end = new Google_Service_Calendar_EventDateTime();
        $str_date = strtotime($booking->getEndsAt()->format('Y-m-d H:i:s'));
        $dateTimeE = SLN_GoogleScope::date3339($str_date);

        $end->setDateTime($dateTimeE);

        $event->setEnd($end);

        return $event;
    }

}

function test_booking($post_id, $post) {
    sln_my_wp_log("save_post => test_booking");
    sln_my_wp_log($post);
    // Make sure the post obj is present and complete. If not, bail.
    if (!is_object($post) || !isset($post->post_type)) {
        return;
    }

    switch ($post->post_type) { // Do different things based on the post type
        case "sln_booking":
            sln_my_wp_log($post);
            $booking = new SLN_Wrapper_Booking($post);
            sln_my_wp_log($booking);
            $ret = $GLOBALS['sln_googlescope']->create_event_from_booking($booking);
            sln_my_wp_log($ret);
            break;

        default:
            break;
    }
}

add_action('save_post', 'test_booking', 1, 2);

//SLN_GoogleScope::init();
//echo "-->".SLN_GoogleScope::$outh2_client_id;
//
//$cal_list = SLN_GoogleScope::get_calendar_list();
//
//$params = array(
//    'email' => $cal_list[0],
//    'title' => "mio evento",
//    'location' => "fiumicino",
//    'date_start' => "20-09-2015",
//    'time_start' => "20:00",
//    'date_end' => "21-09-2015",
//    'time_end' => "20:00",
//);
//$i = SLN_GoogleScope::create_event($params);
////SLN_GoogleScope::delete_event($i);
//
//$cparams = array(
//    'height' => 600,
//    'width' => 800,
//    'wkst' => 1,
//    'bgcolor' => '#FFFFFF',
//    'color' => '#29527A',
//    'src' => $cal_list[0],
//    'ctz' => 'Europe/Rome'
//);
//SLN_GoogleScope::print_calendar_by_calendar_id($cparams);
//SLN_GoogleScope::get_list_event($cal_list[0]);
//add_action('wp_loaded', function() {
//    if (isset($_GET['code']))
//        do_action('onchangeapi', SLN_GoogleScope::init());
//});
?>