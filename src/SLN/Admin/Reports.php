<?php

class SLN_Admin_Reports extends SLN_Admin_AbstractPage
{

    const PAGE = 'salon-reports';


    public function admin_menu()
    {
        $this->classicAdminMenu(
            __('Salon Reports', 'salon-booking-system'),
            __('Reports', 'salon-booking-system')
        );
    }

    public function show()
    {

        echo $this->plugin->loadView(
            'admin/reports',
            array(
                'plugin' => $this->plugin,
            )
        );
    }
}
