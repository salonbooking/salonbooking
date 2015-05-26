<?php


class SLN_Update_Init {
    public function __construct(){
        add_action( 'admin_init', array($this,'plugin_updater'), 0 );
        add_action('admin_menu', array($this,'license_menu'));
        add_action('admin_init', array($this,'register_option'));
        add_action('admin_init', array($this,'activate_license'));
        add_action('admin_init', array($this,'deactivate_license'));

    }
    function plugin_updater() {

        // retrieve our license key from the DB
        $license_key = trim( get_option( 'sln_license_key' ) );

        // setup the updater
        $edd_updater = new SLN_Update_Processor( SLN_STORE_URL, __FILE__, array(
                'version' 	=> '1.0.1', 				// current version number
                'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
                'item_name' => SLN_ITEM_NAME, 	// name of this plugin
                'author' 	=> 'Worddpress chef'  // author of this plugin
            )
        );
    }


    /************************************
     * the code below is just a standard
     * options page. Substitute with
     * your own.
     *************************************/

    function license_menu() {
        add_plugins_page( 'Plugin License', 'Plugin License', 'manage_options', 'pluginname-license', array($this,'license_page') );
    }

    function license_page() {
        $license 	= get_option( 'sln_license_key' );
        $status 	= get_option( 'sln_license_status' );
        ?>
        <div class="wrap">
        <h2><?php _e('Plugin License Options'); ?></h2>
        <form method="post" action="options.php">

            <?php settings_fields('sln_license'); ?>

            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row" valign="top">
                        <?php _e('License Key'); ?>
                    </th>
                    <td>
                        <input id="sln_license_key" name="sln_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                        <label class="description" for="sln_license_key"><?php _e('Enter your license key'); ?></label>
                    </td>
                </tr>
                <?php if( false !== $license ) { ?>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('Activate License'); ?>
                        </th>
                        <td>
                            <?php if( $status !== false && $status == 'valid' ) { ?>
                                <span style="color:green;"><?php _e('active'); ?></span>
                                <?php wp_nonce_field( 'sln_nonce', 'sln_nonce' ); ?>
                                <input type="submit" class="button-secondary" name="sln_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
                            <?php } else {
                                wp_nonce_field( 'sln_nonce', 'sln_nonce' ); ?>
                                <input type="submit" class="button-secondary" name="sln_license_activate" value="<?php _e('Activate License'); ?>"/>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php submit_button(); ?>

        </form>
        <?php
    }

    function register_option() {
        // creates our settings in the options table
        register_setting('sln_license', 'sln_license_key', array($this, 'sln_sanitize_license') );
    }

    function sln_sanitize_license( $new ) {
        $old = get_option( 'sln_license_key' );
        if( $old && $old != $new ) {
            delete_option( 'sln_license_status' ); // new license has been entered, so must reactivate
        }
        return $new;
    }



    /************************************
     * this illustrates how to activate
     * a license key
     *************************************/

    function activate_license() {

        // listen for our activate button to be clicked
        if( isset( $_POST['sln_license_activate'] ) ) {

            // run a quick security check
            if( ! check_admin_referer( 'sln_nonce', 'sln_nonce' ) )
                return; // get out if we didn't click the Activate button

            // retrieve the license from the database
            $license = trim( get_option( 'sln_license_key' ) );


            // data to send in our API request
            $api_params = array(
                'sln_action'=> 'activate_license',
                'license' 	=> $license,
                'item_name' => urlencode( SLN_ITEM_NAME ), // the name of our product in EDD
                'url'       => home_url()
            );

            // Call the custom API.
            $response = wp_remote_get( add_query_arg( $api_params, SLN_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                return false;

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "valid" or "invalid"

            update_option( 'sln_license_status', $license_data->license );

        }
    }


    /***********************************************
     * Illustrates how to deactivate a license key.
     * This will descrease the site count
     ***********************************************/

    function deactivate_license() {

        // listen for our activate button to be clicked
        if( isset( $_POST['sln_license_deactivate'] ) ) {

            // run a quick security check
            if( ! check_admin_referer( 'sln_nonce', 'sln_nonce' ) )
                return; // get out if we didn't click the Activate button

            // retrieve the license from the database
            $license = trim( get_option( 'sln_license_key' ) );


            // data to send in our API request
            $api_params = array(
                'edd_action'=> 'deactivate_license',
                'license' 	=> $license,
                'item_name' => urlencode( SLN_ITEM_NAME ), // the name of our product in EDD
                'url'       => home_url()
            );

            // Call the custom API.
            $response = wp_remote_get( add_query_arg( $api_params, SLN_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                return false;

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "deactivated" or "failed"
            if( $license_data->license == 'deactivated' )
                delete_option( 'sln_license_status' );

        }
    }


    /************************************
     * this illustrates how to check if
     * a license key is still valid
     * the updater does this for you,
     * so this is only needed if you
     * want to do something custom
     *************************************/

    function check_license() {

        global $wp_version;

        $license = trim( get_option( 'sln_license_key' ) );

        $api_params = array(
            'edd_action' => 'check_license',
            'license' => $license,
            'item_name' => urlencode( SLN_ITEM_NAME ),
            'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_get( add_query_arg( $api_params, SLN_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );


        if ( is_wp_error( $response ) )
            return false;

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if( $license_data->license == 'valid' ) {
            echo 'valid'; exit;
            // this license is still valid
        } else {
            echo 'invalid'; exit;
            // this license is no longer valid
        }
    }

}
