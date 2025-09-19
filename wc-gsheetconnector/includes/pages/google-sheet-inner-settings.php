
<?php

/*
 * Google Sheet configuration and settings page
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit();
}
$active_tab =  ( isset($_GET['tab']) && sanitize_text_field(wp_unslash($_GET['tab'])) ) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'integration';


$active_tab_name = '';
if ($active_tab == 'wc_settings') {
    $active_tab_name = 'WooCommerce Settings';
}

$sub_tab = isset($_GET['sub_tab']) ? sanitize_text_field(wp_unslash($_GET['sub_tab'])) : 'role_settings';

switch ($active_tab) {
    case 'wc_settings':
        // Render sub-navigation
        echo '</div><div class="sub-nav-bar">';
        $sub_tabs = array(
            // 'reset-settings' => esc_html('General Settings', 'wc-gsheetconnector'),
            'role_settings' => esc_html('Role Settings', 'wc-gsheetconnector'),
             'beta_version' => esc_html('Beta Version Controls', 'wc-gsheetconnector'),
            'system_status' => esc_html('System Status', 'wc-gsheetconnector'),
        );

        foreach ($sub_tabs as $sub => $label) {
            $class = ($sub === $sub_tab) ? 'sub-nav-tab sub-nav-tab-active' : 'sub-nav-tab';
            echo '<a class="' . esc_attr($class) . '" href="' . esc_url(admin_url('admin.php?page=wc-gsheetconnector-config&tab=wc_gsheetconnector_settings&tab=wc_settings&sub_tab=' . urlencode($sub))) . '">' . esc_html($label) . '</a>';
        }
        echo '</div> <div class="wrap-gsc">';

        // Load correct sub-tab content
        switch ($sub_tab) {
          case 'role_settings':
      $role_settings = new wc_gsheetconnector_role_settings_free();
      $role_settings->add_role_setting_page_free();
      break;
    case 'beta_version':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-beta-version.php');
      break;
    case 'system_status':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-gsheetconnector-systeminfo.php');
      break;

        }
        break;

}


	?>