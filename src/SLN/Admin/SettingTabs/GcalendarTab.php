<?php 	
class SLN_Admin_SettingTabs_GcalendarTab extends SLN_Admin_SettingTabs_AbstractTab
{
	protected $fields = array(
        'google_calendar_enabled',
        'google_outh2_client_id',
        'google_outh2_client_secret',
        'google_outh2_redirect_uri',
        'google_client_calendar',
    );

	protected function validate(){
		
        if ($this->needsGCalendarRevokeToken()) {
            header("Location: ".admin_url('admin.php?page=salon-settings&tab=gcalendar&revoketoken=1'));
        }

        if (isset($_GET['revoketoken']) && $_GET['revoketoken'] == 1) {
            header("Location: ".admin_url('admin.php?page=salon-settings&tab=gcalendar'));
        }
	}
	
	protected function needsGCalendarRevokeToken()
    {
        
        $s = $this->submitted;
        $ret = false;
        $keys = array('google_calendar_enabled','google_outh2_client_id','google_outh2_client_secret');
        foreach ($keys as $k) {
        	$old = $this->settings->get($k);
        	if(isset($s[$k]) && $old != $s[$k]){
        		$ret = true;
        		break;
        	}
        }

        return $ret;
    }
}
 ?>