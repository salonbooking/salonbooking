<?php
if (!isset($_SESSION))
    session_start();

function _pre($m) {
    echo "<pre>";
    print_r($m);
    echo "</pre>";
}

//Add to htaccess RewriteRule ^wp-admin/salon-settings/(.*)/$ /wp-admin/admin.php?page=salon-settings&tab=$1 [L]

require SLN_PLUGIN_DIR . '/src/SLN/Third/google-api-php-client/src/Google/autoload.php';
require_once SLN_PLUGIN_DIR . '/src/SLN/Third/google-api-php-client/src/Google/Client.php';
require_once SLN_PLUGIN_DIR . '/src/SLN/Third/google-api-php-client/src/Google/Service/Calendar.php';

class SLN_GoogleScope {

    static public $date_offset = 0;
    static public $client_id = '102246196260-so9c267umku08brmrgder71ige08t3nm.apps.googleusercontent.com'; //change this
    static public $email_address = '102246196260-so9c267umku08brmrgder71ige08t3nm@developer.gserviceaccount.com'; //change this    
    static public $scopes = "https://www.googleapis.com/auth/calendar";
    static public $key_file_location = 'google-api-php-client/gc.p12'; //change this           
    static public $outh2_client_id = "102246196260-hjpu1fs2rh5b9mesa9l5urelno396vc0.apps.googleusercontent.com";
    static public $outh2_client_secret = "AJzLfWtRDz53JLT5fYp5gLqZ";
    static public $outh2_redirect_uri;
    static public $client;
    static public $service;
    static public $settings;

    /**
     * wp_init
     * @param type $plugin
     */
    static public function wp_init() {
        if (isset(self::$settings)) {
            self::$outh2_client_id = self::$settings->get('google_outh2_client_id');
            self::$outh2_client_secret = self::$settings->get('google_outh2_client_secret');
            self::$outh2_redirect_uri = self::$settings->get('google_outh2_redirect_uri');
            if (
                    !empty(self::$outh2_client_id) &&
                    !empty(self::$outh2_client_secret) &&
                    !empty(self::$outh2_redirect_uri)
            ) {
                self::init();
            }
        }
    }

    static public function set_settings_by_plugin($plugin) {
        self::$settings = $plugin->getSettings();
    }

    /**
     * constructor
     * @param type $params
     */
    static public function init($params = array()) {
        //Init params
        if (!empty($params))
            foreach ($params as $key => $value) {
                self::${$key} = $value;
            }

        //Get Google Client
        //$this->client = $this->get_google_client();
        self::$client = self::get_client();
        self::$service = self::get_google_service();
        //Start Google Auth service2service
        //self::start_auth(); 
        return array(self::$client, self::$service);
    }

    /**
     * get_client
     * @return \Google_Client
     */
    static public function get_client() {
        $client = new Google_Client();
        $client->setClientId(self::$outh2_client_id);
        $client->setClientSecret(self::$outh2_client_secret);
        $client->setRedirectUri(isset(self::$outh2_redirect_uri) ? self::$outh2_redirect_uri : admin_url('/'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->addScope(self::$scopes);

        if ((isset($_GET['revoketoken']) && $_GET['revoketoken'] == 1)/* || $client->isAccessTokenExpired() */) {
            $client->revokeToken();
            self::$settings->set('sln_refresh_token', '');
            self::$settings->set('sln_access_token', '');
            self::$settings->save();
            $_SESSION['access_token'] = "";

            echo 'Access Token Expired'; // Debug
            header("Location: " . admin_url('/'));
        }

        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']); // Authenticate
            $_SESSION['access_token'] = $client->getAccessToken();
            $token = $client->getAccessToken();
            $refresh_token = json_decode($token);
            $refresh_token = $refresh_token->refresh_token;

            //update_option('sln_access_token', $token);
            self::$settings->set('sln_refresh_token', $refresh_token);
            self::$settings->set('sln_access_token', $token);
            self::$settings->save();
            header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        }

        $access_token = isset($_SESSION['access_token']) ? $_SESSION['access_token'] : null;
        if (!isset($access_token))
            $access_token = self::$settings->get('sln_access_token');

        if (isset($access_token) && $access_token) {
            $client->setAccessToken($access_token);
        } else {
            $authUrl = $client->createAuthUrl();
            header("Location: $authUrl");
        }
        return $client;
    }

    /**
     * date3339 transform timestamp into google calendar date compliant
     * @param type $timestamp
     * @param type $offset
     * @return string
     */
    static public function date3339($timestamp = 0, $offset = 0) {
        if (!$timestamp)
            return "error";
        $date = date('Y-m-d\TH:i:s', $timestamp);
        $date .= sprintf(".000%+03d:%02d", intval($offset), abs($offset - intval($offset)) * 60);
        return $date;
    }

    /**
     * get_google_client creates and return the google client
     * @return \Google_Client
     */
    static public function get_google_client() {
        $client = new Google_Client();
        $client->setApplicationName("GoogleClient");
        $client->setAccessType('offline');
        return $client;
    }

    /**
     * get_google_service creates and return the google service
     * @return \Google_Service_Calendar
     */
    static public function get_google_service() {
        return new Google_Service_Calendar(self::$client);
    }

    /**
     * start_auth init the login to google services
     */
    static public function start_auth() {
        $key = file_get_contents(self::$key_file_location);
        $cred = new Google_Auth_AssertionCredentials(
                self::$email_address, array(self::$scopes), $key
        );
        self::$client->setAssertionCredentials($cred);
        if (self::$client->getAuth()->isAccessTokenExpired()) {
            self::$client->getAuth()->refreshTokenWithAssertion($cred);
        }
    }

    static public function is_connected() {
        $ret = (isset(self::$client) && !self::$client->getAuth()->isAccessTokenExpired());
        if (!$ret) {
            $refresh_token = self::$settings->get('sln_refresh_token');
            try {
                if (isset(self::$client))
                    self::$client->refreshToken($refresh_token);
                else
                    return false;
            } catch (Exception $e) {
                return false;
            }
            return true;
        }

//        $access_token = self::$settings->get('sln_access_token');
//        _pre($access_token);
//        $google_token= json_decode($access_token);
//        _pre($google_token);
//        _pre(self::$outh2_client_id);
//        _pre(self::$outh2_client_secret);
//        //self::$client->setAccessToken($google_token->access_token);
//        if (!$ret) {            
//            self::$client->refreshToken($access_token['refresh_token']);            
//            $ret = true;
//        }
//        $access_token = self::$settings->get('sln_access_token');
//        _pre($access_token);
//        $google_token= json_decode($access_token);
//        $new_token = self::$client->refreshToken($google_token->access_token);
//        _pre($new_token);
//        $client->setAccessToken($new_token);
//        $new_token = self::$client->getAccessToken();
//        self::$settings->set('sln_access_token', $new_token);
        return $ret;
    }

    /**
     * get_calendar_list
     * @return type
     */
    static public function get_calendar_list() {
        $cal_list = null;
        if (self::is_connected()) {
            $calendarList = self::$service->calendarList->listCalendarList();
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
                    $calendarList = self::$service->calendarList->listCalendarList($optParams);
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
    static public function create_event($params) {
        extract($params);

        $catId = isset($params['catId']) && !empty($params['catId']) ? $params['catId'] : "primary";

        $event = new Google_Service_Calendar_Event();
        $event->setSummary($title);
        $event->setLocation($location);
        $start = new Google_Service_Calendar_EventDateTime();
        //$dateTimeS = gtime(set_data($date_start), $time_start);
        //$dateTimeS = date(DATE_ATOM, strtotime($date_start . " " . $time_start));
        $str_date = strtotime($date_start . " " . $time_start);
        $dateTimeS = self::date3339($str_date, self::$date_offset);

        $start->setDateTime($dateTimeS);
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        //$dateTimeE = gtime(set_data($date_end), $time_end);
        //$dateTimeE = date(DATE_ATOM, strtotime($date_end . " " . $time_end));
        $str_date = strtotime($date_end . " " . $time_end);
        $dateTimeE = self::date3339($str_date, self::$date_offset);

        $end->setDateTime($dateTimeE);
        $event->setEnd($end);

        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail($email);
        $attendees = array($attendee1);

        $event->attendees = $attendees;
        $createdEvent = self::$service->events->insert($catId, $event);

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
    static public function delete_event($event_id, $catId = 'primary') {
        //return $this->service->events->delete('primary', $_SESSION['eventID']);
        return self::$service->events->delete($catId, $event_id);
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
    static public function update_event($params) {
        extract($params);

        $catId = isset($params['catId']) && !empty($params['catId']) ? $params['catId'] : "primary";

        $rule = self::$service->events->get($catId, $event_id);

        $event = new Google_Service_Calendar_Event();
        $event->setSummary($title);
        $event->setLocation($location);
        $start = new Google_Service_Calendar_EventDateTime();
        //$dateTimeS = gtime(set_data($date_start), $time_start);
        //$dateTimeS = date(DATE_ATOM, strtotime($date_start . " " . $time_start));
        $str_date = strtotime($date_start . " " . $time_start);
        $dateTimeS = self::date3339($str_date, self::$date_offset);

        $start->setDateTime($dateTimeS);
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        //$dateTimeE = gtime(set_data($date_end), $time_end);
        //$dateTimeE = date(DATE_ATOM, strtotime($date_end . " " . $time_end));
        $str_date = strtotime($date_end . " " . $time_end);
        $dateTimeE = self::date3339($str_date, self::$date_offset);

        $end->setDateTime($dateTimeE);
        $event->setEnd($end);
        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail($email); //change this
        $attendees = array($attendee1);
        $event->attendees = $attendees;

        $updatedRule = self::$service->events->update($catId, $rule->getId(), $event);
        return $updatedRule;
    }

    /**
     * get_list_event return all the event for primary calendar
     */
    static public function get_list_event($calId = 'primary') {
        $events = self::$service->events->listEvents($calId);
        while (true) {
            foreach ($events->getItems() as $event) {
                echo "<br>" . $event->getId() . " " . $event->getSummary();
                //$this->delete_event($event->getId());
            }
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = self::$service->events->listEvents($calId, $optParams);
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
    static public function print_calendar_by_calendar_id($params) {
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

}

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

add_action('wp_loaded', function() {
    if (isset($_GET['code']))
        do_action('onchangeapi', SLN_GoogleScope::init());
});
?>