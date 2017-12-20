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
        'no_bootstrap',
        'no_bootstrap_js',
    );

	protected function validate(){

		if (!empty($submitted['gen_email']) && !filter_var($submitted['gen_email'], FILTER_VALIDATE_EMAIL)) {
            $this->showAlert('error', __('Invalid Email in Salon contact e-mail field', 'salon-booking-system'));
            return;
        }


        if (empty($submitted['gen_logo']) && $this->getOpt('gen_logo')) {
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
                $submitted['gen_logo'] = $attId;
            }
        }

        $submitted['email_subject'] = !empty($submitted['email_subject']) ?
            $submitted['email_subject'] :
            'Your booking reminder for [DATE] at [TIME] at [SALON NAME]';
        $submitted['booking_update_message'] = !empty($submitted['booking_update_message']) ?
            $submitted['booking_update_message'] :
            'Hi [NAME],\r\ntake note of the details of your reservation at [SALON NAME]';
        $submitted['follow_up_message'] = !empty($submitted['follow_up_message']) ?
            $submitted['follow_up_message'] :
            'Hi [NAME],\r\nIt\'s been a while since your last visit, would you like to book a new appointment with us?\r\n\r\nWe look forward to seeing you again.';
        $submitted['follow_up_message'] = substr($submitted['follow_up_message'], 0, 150);
	}

	protected function postProcess(){
		wp_clear_scheduled_hook('sln_sms_reminder');
        if (isset($submitted['sms_remind']) && $submitted['sms_remind']) {
            wp_schedule_event(time(), 'hourly', 'sln_sms_reminder');
            wp_schedule_event(time() + 1800, 'hourly', 'sln_sms_reminder');
        }
        wp_clear_scheduled_hook('sln_email_reminder');
        if (isset($submitted['email_remind']) && $submitted['email_remind']) {
            wp_schedule_event(time(), 'hourly', 'sln_email_reminder');
            wp_schedule_event(time() + 1800, 'hourly', 'sln_email_reminder');
        }
        if (isset($submitted['follow_up_sms']) && $submitted['follow_up_sms']) {
            if (!wp_get_schedule('sln_sms_followup')) {
                wp_schedule_event(time(), 'daily', 'sln_sms_followup');
            }
        } else {
            wp_clear_scheduled_hook('sln_sms_followup');
        }
        if (isset($submitted['follow_up_email']) && $submitted['follow_up_email']) {
            if (!wp_get_schedule('sln_email_followup')) {
                wp_schedule_event(time(), 'daily', 'sln_email_followup');
            }
        } else {
            wp_clear_scheduled_hook('sln_email_followup');
        }

        if (isset($submitted['feedback_reminder']) && $submitted['feedback_reminder']) {
            if (!wp_get_schedule('sln_email_feedback')) {
                wp_schedule_event(time(), 'daily', 'sln_email_feedback');
            }
        } else {
            wp_clear_scheduled_hook('sln_email_feedback');
        }

        
        if (isset($submitted['editors_manage_cap']) && $submitted['editors_manage_cap']) {
            SLN_UserRole_SalonStaff::addCapabilitiesForRole('editor');
        }
        else {
            SLN_UserRole_SalonStaff::removeCapabilitiesFoRole('editor');
        }
        if ($submitted['sms_test_number'] && $submitted['sms_test_message']) {
            $this->sendTestSms(
                $submitted['sms_test_number'],
                $submitted['sms_test_message']
            );
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