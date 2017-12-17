<?php class SLN_Admin_SettingTabs_HomepageTab extends SLN_Admin_SettingTabs_AbstractTab {
  public function process(){
  	if (isset($_POST['reset-settings']) && $_POST['reset-settings'] == 'reset') {
            $this->settings->clear();
            SLN_Action_Install::execute(true);
            $this->showAlert(
                'success',
                __('remember to customize your settings', 'salon-booking-system'),
                __('Reset completed with success', 'salon-booking-system')
            );
    }
  }
} ?>