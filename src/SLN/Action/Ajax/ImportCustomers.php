<?php

class SLN_Action_Ajax_ImportCustomers extends SLN_Action_Ajax_AbstractImport
{
    protected $fields = array(
        'first_name',
        'last_name',
        'email',
        'mobile_phone',
        'address',
        'personal_note',
    );

    protected $required = array(
        'first_name',
        'email',
    );

    /**
     * SLN_Action_Ajax_ImportCustomers constructor.
     *
     * @param SLN_Plugin $plugin
     */
    public function __construct($plugin)
    {
        parent::__construct($plugin);

        $this->type = $plugin::USER_ROLE_CUSTOMER;
    }

    protected function processRow($data)
    {
        if (empty($data['email'])) {
            return true;
        }
        $errors = wp_create_user($data['email'], wp_generate_password( 8, false ), $data['email']);
        if (is_wp_error($errors)) {
            return true;
        }

        $errors = wp_update_user(
            array(
                'ID'         => $errors,
                'first_name' => (string)$data['first_name'],
                'last_name'  => (string)$data['last_name'],
                'role'       => SLN_Plugin::USER_ROLE_CUSTOMER,
            )
        );
        if (is_wp_error($errors)) {
            return true;
        }

        add_user_meta($errors, '_sln_phone', $data['mobile_phone']);
        add_user_meta($errors, '_sln_address', $data['address']);
        add_user_meta($errors, '_sln_personal_note', $data['personal_note']);

	    wp_send_new_user_notifications($errors, 'user');

        return true;
    }

}
