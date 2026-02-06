<?php
/*
 * Google Sheet configuration and settings page
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe: tab selection used for UI only, no sensitive action
$active_tab = ( isset($_GET['tab']) && sanitize_text_field(wp_unslash($_GET['tab'])) ) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'integration';

$active_tab_name = '';
if ($active_tab === 'integration') {
  $active_tab_name = 'Integration';
} elseif ($active_tab === 'settings') {
  $active_tab_name = 'WooCommerce Data Settings';
} elseif ($active_tab === 'wc_settings') {
  $active_tab_name = 'Settings';
} elseif ($active_tab === 'product_sheet_to_woocommerce') {
  $active_tab_name = '2 Way Sync';
} elseif ($active_tab === 'extension') {
  $active_tab_name = 'Extension';
}
elseif ($active_tab === 'form_feed_settings') {
  $active_tab_name = 'Form Feed Settings';
}

// Check plugin version and subscription plan
$plugin_version = defined('WC_GSHEETCONNECTOR_VERSION') ? WC_GSHEETCONNECTOR_VERSION : 'N/A';

?>
<div class="gsheet-header">
  <div class="gsheet-logo">
    <a href="https://www.gsheetconnector.com/"><i></i></a>
  </div>
  <h1 class="gsheet-logo-text"><span><?php echo esc_html(__('GSheetConnector for WC', 'wc-gsheetconnector')); ?></span>
    <small><?php echo esc_html(__('Version :', 'wc-gsheetconnector')); ?>
      <?php echo esc_html($plugin_version, 'wc-gsheetconnector'); ?> </small>
  </h1>
   
	 
	<ul> 
		<li><a href="<?php echo admin_url( 'admin.php?page=wc-gsheetconnector-config&tab=extension', 'wc-gsheetconnector' ); ?>" title="Extensions">
          <i class="fa-solid fa-puzzle-piece"></i></a></li>
		<li><a href="https://www.gsheetconnector.com/docs/woocommerce-gsheetconnector/installation-process-free-version" title="Document" target="_blank"><i class="fa-regular fa-file-lines"></i></a></li>
		<li><a href="https://www.gsheetconnector.com/support" title="Support" target="_blank"><i class="fa-regular fa-life-ring"></i></a></li>
		<li><a href="https://wordpress.org/plugins/wc-gsheetconnector/#developers" title="Changelog" target="_blank"><i class="fa-solid fa-bullhorn"></i></a></li>
	</ul>
	
</div>

<div class="breadcrumb">
	<span class="wcgsc-dashboard"><?php echo esc_html(__('DASHBOARD', 'wc-gsheetconnector')); ?></span>
	<span class="wcgsc-divider"> / </span>
	<span class="wcgsc-modules"> <?php echo esc_html($active_tab_name); ?></span>
</div>

  <?php
 $tabs = array(
    'integration'               => esc_html__( 'Integration', 'wc-gsheetconnector' ),
    'settings'                  => esc_html__( 'WooCommerce Data Settings', 'wc-gsheetconnector' ),
    'wc_settings'               => esc_html__( 'Settings', 'wc-gsheetconnector' ),
    'form_feed_settings'        => esc_html__( 'Feed Settings', 'wc-gsheetconnector' ),
    'product_sheet_to_woocommerce' => esc_html__( 'Sync', 'wc-gsheetconnector' ),
    'extension'                 => esc_html__( 'Extension', 'wc-gsheetconnector' ),
);

  echo '<div id="icon-themes" class="icon32"><br></div>';
  echo '<div class="nav-tab-wrapper">';
  foreach ($tabs as $tab => $name) {
    // FILTER_SANITIZE_STRING
    $class = ($tab === $active_tab) ? ' nav-tab-active' : '';
    echo '<a class="nav-tab' . esc_attr($class) . '" href="' . esc_url('?page=wc-gsheetconnector-config&tab=' . $tab) . '">' . esc_html($name) . '</a>';

  }
  echo '</div><div class="wrap-gsc">';
  switch ($active_tab) {
    case 'integration':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-gsheetconnector-integration.php');
      break;
    case 'settings':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-gsheetconnector-setting.php');
      break;
    case 'form_feed_settings':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-gsheetconnector-feed-settings.php');
      break;
    case 'wc_settings':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/google-sheet-inner-settings.php');
      break;
    case 'product_sheet_to_woocommerce':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-product-sheet.php');
      break;
    case 'extension':
      include(WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-extension.php');
      break;
  }
  ?>
</div>

<?php include(WC_GSHEETCONNECTOR_PATH . "includes/pages/admin-footer.php"); ?>