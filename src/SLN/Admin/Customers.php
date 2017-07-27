<?php

class SLN_Admin_Customers extends SLN_Admin_AbstractPage {

	const PAGE = 'salon-customers';
    const PRIORITY = 10;

    public function admin_menu()
    {
        $this->classicAdminMenu(__('Salon Customers', 'salon-booking-system'), __('Customers', 'salon-booking-system'));
        if ( ! isset($_REQUEST['id'])) {
            add_filter(
                'manage_'.get_plugin_page_hookname('salon-customers', 'salon').'_columns',
                array($this, 'users_columns')
            );
        }
    }

	public function show() {
		if (isset($_REQUEST['id'])) {
			$this->show_customer_page($_REQUEST['id']);
		}
		else {
			$this->show_customers();
		}
	}

	public function show_customer_page($user_id) {

		$customer = new SLN_Wrapper_Customer(new WP_User($user_id));

		if (!empty($user_id) && $customer->isEmpty()) {
			wp_redirect(get_edit_user_link($user_id));
			exit;
		}

		if (isset($_POST['save'])) {
            $error = $this->save_customer($user_id);
		}

		$customer = new SLN_Wrapper_Customer(new WP_User($user_id));

        echo $this->plugin->loadView(
            'admin/_customer',
            array(
                'customer' => $customer,
                'new_link' => self::get_edit_customer_link(0),
                'error'   => isset($error) ? $error : null,
            )
        );
	}

	private function save_customer($user_id) {
        if (empty($_POST['id'])) {
            if (email_exists($_POST['sln_customer']['user_email'])) {
                $error = new WP_Error();
                $error->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.', 'salon-booking-system'));
                return $error;
            }
            $user_id = wp_create_user($_POST['sln_customer']['user_email'], wp_generate_password(), $_POST['sln_customer']['user_email']);
        }
        else {
            $user_id = $_POST['id'];
        }

        $_POST['sln_customer']['ID'] = $user_id;
        $_POST['sln_customer']['role'] = SLN_Plugin::USER_ROLE_CUSTOMER;
        wp_update_user($_POST['sln_customer']);

        foreach ($_POST['sln_customer_meta'] as $k => $value) {
            update_user_meta($user_id, $k, $value);
        }

        wp_redirect(self::get_edit_customer_link($user_id));
        exit;
    }

	public function show_customers() {
		if (empty($_REQUEST)) {
			$referer = '<input type="hidden" name="wp_http_referer" value="'. esc_attr(wp_unslash($_SERVER['REQUEST_URI'])) . '" />';
		} elseif (isset($_REQUEST['wp_http_referer'])) {
			$redirect = remove_query_arg(array('wp_http_referer', 'updated', 'delete_count'), wp_unslash($_REQUEST['wp_http_referer']));
			$referer = '<input type="hidden" name="wp_http_referer" value="' . esc_attr($redirect) . '" />';
		} else {
			$redirect = 'admin.php';
			$referer = '';
		}

		$table = new SLN_Admin_Customers_List();

		switch ($table->current_action()) {
			case 'delete':
				if (!is_multisite() && !empty($_REQUEST['users'])) {
//					$redirect = wp_unslash(str_replace('/admin.php?', '/users.php?', $_SERVER['REQUEST_URI']));
//					wp_redirect(remove_query_arg('page', $redirect));
//					exit;
					foreach($_REQUEST['users'] as $userId) {
						wp_delete_user( $userId );
					}
				}
				break;
//			default:
//				if (!empty($table->current_action())) {
//					if (empty($_REQUEST['users'])) {
//						wp_redirect($redirect);
//						exit();
//					}
//					$update = '';
//					wp_redirect(add_query_arg('update', $update, $redirect));
//					exit();
//				}
//				else {
//					if (!empty($_GET['_wp_http_referer'])) {
//						wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI'])));
//						exit;
//					}
//				}
		}

		if (!empty($_GET['_wp_http_referer'])) {
			wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI'])));
			exit;
		}

		$table->prepare_items();

		echo $this->plugin->loadView(
				'admin/customers',
				array(
						'new_link' => self::get_edit_customer_link(0),
						'table'    => $table,
				)
		);
	}

	public function users_columns($users_columns) {
		$table = new SLN_Admin_Customers_List();

		return $table->get_columns();
	}



	public static function get_edit_customer_link($user_id) {

		return get_admin_url() . "admin.php?page=salon-customers&id=$user_id";
	}
}