<?php

/*
 * Utilities class for woocommerce google sheet connector pro
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * wc_gsheetconnector_utility class - singleton class
 * @since 1.0
 */
class wc_gsheetconnector_utility
{

    private function __construct()
    {
        // Do Nothing
    }

    /**
     * Get the singleton instance of the wc_gsheetconnector_utility class
     *
     * @return singleton instance of wc_gsheetconnector_utility
     */
    public static function instance()
    {

        static $instance = NULL;
        if (is_null($instance)) {
            $instance = new wc_gsheetconnector_utility();
        }
        return $instance;
    }

    /**
     * Prints message (string or array) in the debug.log file
     *
     * @param mixed $message
     */
    public function logger($message)
    {
        if ( defined('WP_DEBUG') && WP_DEBUG === true ) {
            self::gs_debug_log($message);
        }
    }


    /**
     * Display error or success message in the admin section
     *
     * @param array $data containing type and message
     * @return string with html containing the error message
     * 
     * @since 1.0 initial version
     */
    public function admin_notice($data = array())
    {
        $message = isset($data['message']) ? $data['message'] : '';
        $message_type = isset($data['type']) ? $data['type'] : '';
        
        switch ($message_type) {
            case 'error':
            $admin_notice = '<div id="message" class="error notice is-dismissible">';
            break;
            case 'update':
            $admin_notice = '<div id="message" class="updated notice is-dismissible">';
            break;
            case 'update-nag':
            $admin_notice = '<div id="message" class="update-nag">';
            break;
            case 'upgrade':
            $admin_notice = '<div id="message" class="error notice wpforms-gs-upgrade is-dismissible">';
            break;
            default:
            $message = __('There\'s something wrong with your code...', 'wc-gsheetconnector');
            $admin_notice = "<div id=\"message\" class=\"error\">";
            break;
        }

        $admin_notice .= '<p>' . esc_html( $message ) . '</p>';
        $admin_notice .= "</div>\n";

        return $admin_notice;
    }


    /**
     * Utility function to get the current user's role
     *
     * @since 1.0
     */
    public function get_current_user_role()
    {
        $user = wp_get_current_user();

        if ( ! empty( $user->roles ) ) {
            return $user->roles[0];
        }

        return '';
    }

    /**
     * Fetch and save Auto Integration API credentials
     *
     * @since 2.3.19
     */
    public function save_api_credentials()
    {
        // Create a nonce
        $nonce = wp_create_nonce('wcgsc_api_free_creds');

        // Prepare parameters for the API call
        $params = array(
            'action' => 'get_data',
            'nonce' => $nonce,
            'plugin' => 'WOOGSC',
            'method' => 'get',
        );

        // Add nonce and any other security parameters to the API request
        $api_url = esc_url_raw(add_query_arg($params, WC_GSHEETCONNECTOR_API_URL));

        // Make the API call using wp_remote_get
        $response = wp_remote_get($api_url);

        // Check for errors
        if (is_wp_error($response)) {
         self::gs_debug_log($response->get_error_message());
         return;

     } else {
            // API call was successful, process the data
        $response = wp_remote_retrieve_body($response);

        $decoded_response = json_decode($response);

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            self::gs_debug_log('Invalid API JSON response');
            return;
        }

        if (isset($decoded_response->api_creds) && (!empty($decoded_response->api_creds))) {
            $api_creds = wp_parse_args($decoded_response->api_creds);
            if (is_multisite()) {
                    // If it's a multisite, update the site option (network-wide)
                update_site_option('wcgsc_api_free_creds', $api_creds);
            } else {
                    // If it's not a multisite, update the regular option
                update_option('wcgsc_api_free_creds', $api_creds);
            }
        }
    }
}

public static function gs_debug_log( $error ) {

// ===============================
// 🔥 DATABASE LOG (ADD ONLY THIS)
// ===============================
    if (class_exists('wcgsc_error_logs')) {
        wcgsc_error_logs::log_from_debug($error);
    }

}

    /**
     * 
     * @param string $setting_name
     * @param array $selected_roles
     */
    public function wcgsc_checkbox_roles_multi($setting_name, $selected_roles) {
        $selected_row = '';
        $checked = '';
        $roles = array();
        $system_roles = $this->get_system_roles();

        if (!empty($selected_roles)) {
            foreach ($selected_roles as $role => $display_name) {
                array_push($roles, $role);
            }
        }

        

		// Static Administrator role (always enabled)
        $selected_row .= "<div class='gsc-role-card mb-10'>
        <div class='custom-check d-flex justify-between alien-center'>
        <label class='role-label gsc-switch'>" . esc_html__("Administrator", "wc-gsheetconnector") . "</label>
        <input type='checkbox' class='check-toggle' disabled='disabled' checked='checked'>
        <label class='button-toggle'></label>
        </div>
        </div>";

        foreach ($system_roles as $role => $display_name) {

            if ($role === "administrator") {
                continue;
            }

            $checked = '';
            if (!empty($roles) && is_array($roles) && in_array(esc_attr($role), $roles)) {
                $checked = "checked='checked'";
            }

            $selected_row .= "<div class='gsc-role-card mb-10'>
            <div class='custom-check d-flex justify-between alien-center'>
            
            <label class='role-label gsc-switch'>" . esc_html($display_name) . "</label>
            
            <input 
            type='checkbox' 
            class='check-toggle' 
            name='" . esc_attr($setting_name) . "[]' 
            value='" . esc_attr($role) . "' 
            $checked
            >
            
            <label class='button-toggle'></label>
            </div>
            </div>";
        }

        //  Properly escaped final output
        echo wp_kses_post( $selected_row );
    }

    /*
     * Get all editable roles except for subscriber role
     * @return array
     * @since 1.1
     */

    public function get_system_roles()
    {

        $participating_roles = array();
        $editable_roles      = get_editable_roles();

        foreach ($editable_roles as $role => $details) {

            // Remove unwanted roles
            if ('subscriber' === $role || 'customer' === $role || 'shop_manager' === $role) {
                continue;
            }

            $participating_roles[$role] = $details['name'];
        }

        return $participating_roles;
    }


}
