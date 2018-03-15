<?php 	
class SLN_Admin_SettingTabs_GeneralTab extends SLN_Admin_SettingTabs_AbstractTab
{
	protected $fields = array(
        'gen_name',
        'gen_email',
        'gen_phone',
        'gen_address',
        'gen_logo',
        'gen_timetable',
        'ajax_enabled',
        'editors_manage_cap',
        'attendant_enabled',
        'm_attendant_enabled',
        'attendant_email',
        'sms_enabled',
        'sms_account',
        'sms_password',
        'sms_prefix',
        'sms_provider',
        'sms_from',
        'sms_new',
        'sms_new_number',
        'sms_new_attendant',
        'sms_remind',
        'sms_remind_interval',
        'sms_trunk_prefix',
        'email_remind',
        'email_remind_interval',
        'email_subject',
        'booking_update_message',
        'follow_up_email',
        'follow_up_sms',
        'follow_up_interval',
        'follow_up_message',
        'feedback_reminder',
        'soc_facebook',
        'soc_twitter',
        'soc_google',
        'date_format',
        'time_format',
        'week_start',
        'calendar_view',
        'no_bootstrap',
        'no_bootstrap_js',
        'sms_test_number',
        'sms_test_message',
    );

	protected function validate(){

		if (!empty($this->submitted['gen_email']) && !filter_var($this->submitted['gen_email'], FILTER_VALIDATE_EMAIL)) {
            $this->showAlert('error', __('Invalid Email in Salon contact e-mail field', 'salon-booking-system'));
            return;
        }


        if (empty($this->submitted['gen_logo']) && $this->getOpt('gen_logo')) {
            wp_delete_attachment($this->getOpt('gen_logo'), true);
        }

        if (isset($_FILES['gen_logo']) && !empty($_FILES['gen_logo']['size'])) {
            $_FILES['gen_logo']['name'] = 'gen_logo.png';

            $imageSize = 'sln_gen_logo';
            if (!has_image_size($imageSize)) {
                add_image_size($imageSize, 160, 70);
            }
            $attId = media_handle_upload('gen_logo', 0);

            if (!is_wp_error($attId)) {
                $this->submitted['gen_logo'] = $attId;
            }
        }

        $this->submitted['email_subject'] = !empty($this->submitted['email_subject']) ?
            $this->submitted['email_subject'] :
            'Your booking reminder for [DATE] at [TIME] at [SALON NAME]';
        $this->submitted['booking_update_message'] = !empty($this->submitted['booking_update_message']) ?
            $this->submitted['booking_update_message'] :
            'Hi [NAME],\r\ntake note of the details of your reservation at [SALON NAME]';
        $this->submitted['follow_up_message'] = !empty($this->submitted['follow_up_message']) ?
            $this->submitted['follow_up_message'] :
            'Hi [NAME],\r\nIt\'s been a while since your last visit, would you like to book a new appointment with us?\r\n\r\nWe look forward to seeing you again.';
        $this->submitted['follow_up_message'] = substr($this->submitted['follow_up_message'], 0, 150);
        if ($this->submitted['sms_test_number'] && $this->submitted['sms_test_message']) {
            $this->sendTestSms(
                $this->submitted['sms_test_number'],
                $this->submitted['sms_test_message']
            );
        }
        $this->submitted['sms_test_number'] = '';
        $this->submitted['sms_test_message'] = '';
	}

	protected function postProcess(){
		wp_clear_scheduled_hook('sln_sms_reminder');
        if (isset($this->submitted['sms_remind']) && $this->submitted['sms_remind']) {
            wp_schedule_event(time(), 'hourly', 'sln_sms_reminder');
            wp_schedule_event(time() + 1800, 'hourly', 'sln_sms_reminder');
        }
        wp_clear_scheduled_hook('sln_email_reminder');
        if (isset($this->submitted['email_remind']) && $this->submitted['email_remind']) {
            wp_schedule_event(time(), 'hourly', 'sln_email_reminder');
            wp_schedule_event(time() + 1800, 'hourly', 'sln_email_reminder');
        }
        if (isset($this->submitted['follow_up_sms']) && $this->submitted['follow_up_sms']) {
            if (!wp_get_schedule('sln_sms_followup')) {
                wp_schedule_event(time(), 'daily', 'sln_sms_followup');
            }
        } else {
            wp_clear_scheduled_hook('sln_sms_followup');
        }
        if (isset($this->submitted['follow_up_email']) && $this->submitted['follow_up_email']) {
            if (!wp_get_schedule('sln_email_followup')) {
                wp_schedule_event(time(), 'daily', 'sln_email_followup');
            }
        } else {
            wp_clear_scheduled_hook('sln_email_followup');
        }

        if (isset($this->submitted['feedback_reminder']) && $this->submitted['feedback_reminder']) {
            if (!wp_get_schedule('sln_email_feedback')) {
                wp_schedule_event(time(), 'daily', 'sln_email_feedback');
            }
        } else {
            wp_clear_scheduled_hook('sln_email_feedback');
        }

        
        if (isset($this->submitted['editors_manage_cap']) && $this->submitted['editors_manage_cap']) {
            SLN_UserRole_SalonStaff::addCapabilitiesForRole('editor');
        }
        else {
            SLN_UserRole_SalonStaff::removeCapabilitiesFoRole('editor');
        }
        
	}

    protected function sendTestSms($number, $message)
    {
        $sms = $this->plugin->sms();
        $sms->send($number, $message);
        if ($sms->hasError()) {
            $this->showAlert('error', $sms->getError());
        } else {
            $this->showAlert(
                'success',
                __('Test sms sent with success', 'salon-booking-system'),
                ''
            );
        }
    }
}
 ?>