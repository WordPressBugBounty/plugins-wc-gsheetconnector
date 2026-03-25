<?php
/*
 * Google Sheet configuration and settings page
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe: used only for UI tab switching
$wcgsc_active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'integration';

// Set active tab name (translated)
$wcgsc_active_tab_name = '';
if ( $wcgsc_active_tab === 'integration' ) {
  $wcgsc_active_tab_name = esc_html__( 'Integration', 'wc-gsheetconnector' );
} elseif ( $wcgsc_active_tab === 'settings' ) {
  $wcgsc_active_tab_name = esc_html__( 'WooCommerce Data Settings', 'wc-gsheetconnector' );
} elseif ( $wcgsc_active_tab === 'wc_settings' ) {
  $wcgsc_active_tab_name = esc_html__( 'Settings', 'wc-gsheetconnector' );
} elseif ( $wcgsc_active_tab === 'product_sheet_to_woocommerce' ) {
  $wcgsc_active_tab_name = esc_html__( '2 Way Sync', 'wc-gsheetconnector' );
} elseif ( $wcgsc_active_tab === 'extension' ) {
  $wcgsc_active_tab_name = esc_html__( 'Extension', 'wc-gsheetconnector' );
} elseif ( $wcgsc_active_tab === 'form_feed_settings' ) {
  $wcgsc_active_tab_name = esc_html__( 'Form Feed Settings', 'wc-gsheetconnector' );
}

// Plugin version
$wcgsc_plugin_version = defined( 'WC_GSHEETCONNECTOR_VERSION' ) ? WC_GSHEETCONNECTOR_VERSION : 'N/A';
?>

<div class="gsheet-header">
  <div class="gsheet-logo">
    <a href="<?php echo esc_url( 'https://www.gsheetconnector.com/' ); ?>"><i></i></a>
  </div>

  <h1 class="gsheet-logo-text">
    <span><?php echo esc_html__( 'GSheetConnector for WooCommerce', 'wc-gsheetconnector' ); ?></span>
    <small>
      <?php echo esc_html__( 'Version :', 'wc-gsheetconnector' ); ?>
      <?php echo esc_html( $wcgsc_plugin_version ); ?>
    </small>
  </h1>

  <ul> 
    <li>
      <a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-gsheetconnector-config&tab=extension' ) ); ?>" title="<?php echo esc_attr__( 'Extensions', 'wc-gsheetconnector' ); ?>">
        <i class="fa-solid fa-puzzle-piece"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://www.gsheetconnector.com/docs/woocommerce-gsheetconnector/installation-process-free-version' ); ?>" title="<?php echo esc_attr__( 'Document', 'wc-gsheetconnector' ); ?>" target="_blank" rel="noopener">
        <i class="fa-regular fa-file-lines"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://www.gsheetconnector.com/support' ); ?>" title="<?php echo esc_attr__( 'Support', 'wc-gsheetconnector' ); ?>" target="_blank" rel="noopener">
        <i class="fa-regular fa-life-ring"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://wordpress.org/plugins/wc-gsheetconnector/#developers' ); ?>" title="<?php echo esc_attr__( 'Changelog', 'wc-gsheetconnector' ); ?>" target="_blank" rel="noopener">
        <i class="fa-solid fa-bullhorn"></i>
      </a>
    </li>
  </ul>
</div>

<div class="breadcrumb">
 <span class="wcgsc-dashboard"><?php echo esc_html__( 'DASHBOARD', 'wc-gsheetconnector' ); ?></span>
 <span class="wcgsc-divider"> / </span>
 <span class="wcgsc-modules"><?php echo esc_html( $wcgsc_active_tab_name ); ?></span>
</div>

<?php
$wcgsc_tabs = array(
  'integration'                   => esc_html__( 'Integration', 'wc-gsheetconnector' ),
  'settings'                      => esc_html__( 'WooCommerce Data Settings', 'wc-gsheetconnector' ),
  'wc_settings'                   => esc_html__( 'Settings', 'wc-gsheetconnector' ),
  'form_feed_settings'            => esc_html__( 'Feed Settings', 'wc-gsheetconnector' ),
  'product_sheet_to_woocommerce'  => esc_html__( 'Sync', 'wc-gsheetconnector' ),
  'extension'                     => esc_html__( 'Extension', 'wc-gsheetconnector' ),
);

echo '<div id="icon-themes" class="icon32"><br></div>';
echo '<div class="nav-tab-wrapper">';

foreach ( $wcgsc_tabs as $wcgsc_tab => $wcgsc_name ) {
  $wcgsc_class = ( $wcgsc_tab === $wcgsc_active_tab ) ? ' nav-tab-active' : '';

  echo '<a class="nav-tab' . esc_attr( $wcgsc_class ) . '" href="' . esc_url( admin_url( 'admin.php?page=wc-gsheetconnector-config&tab=' . $wcgsc_tab ) ) . '">' . esc_html( $wcgsc_name ) . '</a>';
}

echo '</div><div class="wrap-gsc">';

switch ( $wcgsc_active_tab ) {
  case 'integration':
  include_once WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-gsheetconnector-integration.php';
  break;

  case 'settings':
  include_once WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-gsheetconnector-setting.php';
  break;

  case 'form_feed_settings':
  include_once WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-gsheetconnector-feed-settings.php';
  break;

  case 'wc_settings':
  include_once WC_GSHEETCONNECTOR_PATH . 'includes/pages/google-sheet-inner-settings.php';
  break;

  case 'product_sheet_to_woocommerce':
  include_once WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-product-sheet.php';
  break;

  case 'extension':
  include_once WC_GSHEETCONNECTOR_PATH . 'includes/pages/wc-extension.php';
  break;
}
?>
</div>

<?php include_once WC_GSHEETCONNECTOR_PATH . 'includes/pages/admin-footer.php'; ?>