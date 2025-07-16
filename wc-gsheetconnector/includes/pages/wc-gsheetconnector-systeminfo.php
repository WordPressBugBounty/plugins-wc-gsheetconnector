<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}
$wc_free_system_log = new wc_gsheetconnector_Init();
?>
<div class="wcgsc-system-status">
  <div class="info-container">
    <h2 class="systemifo"><?php echo esc_html(__('System Info', 'wc-gsheetconnector')); ?></h2>
    <button onclick="copySystemInfo()" class="copy-system-info"><?php echo esc_html(__('Copy System Info to Clipboard', 'wc-gsheetconnector')); ?></button>
    <?php echo wp_kses_post($wc_free_system_log->get_wcfree_system_info()); ?>
  </div>
</div>

<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}

?>
<div class="system-debug-logs" id="opener" >
   <div class="info-container">
      <h2 class="systemifo"><span><?php echo esc_html(__('Debug Constants', 'wc-gsheetconnector')); ?></span>
    <span class="pro-ver"><?php echo esc_html(__('PRO', 'wc-gsheetconnector')); ?></span>
      
</h2>
<form method="post" style="pointer-events: none;">
<table>
  <tr>
    <th><?php echo esc_html__( 'Key', 'wc-gsheetconnector' ); ?></th>
    <th><?php echo esc_html__( 'Info', 'wc-gsheetconnector' ); ?></th>
    <th><?php echo esc_html__( 'Status', 'wc-gsheetconnector' ); ?></th>
  </tr>
  <tr>
    <th><?php echo esc_html__( 'WP_DEBUG', 'wc-gsheetconnector' ); ?></th>
    <td><?php echo esc_html__( 'Enable WP_DEBUG mode', 'wc-gsheetconnector' ); ?></td>
    <td>
      <label class="switch">
        <input type="checkbox" name="wpgsc-debug" value="">
        <span class="slider round"></span>
      </label>
    </td>
  </tr>
  <tr>
    <th><?php echo esc_html__( 'WP_DEBUG_LOG', 'wc-gsheetconnector' ); ?></th>
    <td><?php echo esc_html__( 'Enable Debug logging to the /wp-content/debug.log file', 'wc-gsheetconnector' ); ?></td>
    <td>
      <label class="switch">
        <input type="checkbox" name="wpgsc-debug-log" value="">
        <span class="slider round"></span>
      </label>
    </td>
  </tr>
  <tr>
    <th><?php echo esc_html__( 'SCRIPT_DEBUG', 'wc-gsheetconnector' ); ?></th>
    <td><?php echo esc_html__( 'Use the “dev” versions of core CSS and JavaScript files', 'wc-gsheetconnector' ); ?></td>
    <td>
      <label class="switch">
        <input type="checkbox" name="wpgsc-script-debug" value="">
        <span class="slider round"></span>
      </label>
    </td>
  </tr>
  <tr>
    <th><?php echo esc_html__( 'SAVEQUERIES', 'wc-gsheetconnector' ); ?></th>
    <td><?php echo esc_html__( 'Enable database query logging, turn it off when not debugging because it will affect site performance. The array is stored in the global $wpdb->queries.', 'wc-gsheetconnector' ); ?></td>
    <td>
      <label class="switch">
        <input type="checkbox" name="wpgsc-savequeries" value="">
        <span class="slider round"></span>
      </label>
    </td>
  </tr>
</table>

<h2>
  <input type="submit" class="button button-primary button-large debug-logs-save" name="gs_woo_debug_settings" value="<?php echo esc_attr__( "Save", "wc-gsheetconnector" ); ?>" />
  <span class="beta-loading-sign-woogsc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
</h2>

           </form>

            </div>

  </div>

<div class="system-Error">
    <div class="error-container">
        <h2 class="systemerror"><?php echo esc_html__( "Error Log", "wc-gsheetconnector" ); ?> </h2>
        <p>
          <?php echo esc_html__( "If you have", "wc-gsheetconnector" ); ?>
          <a href="https://www.gsheetconnector.com/how-to-enable-debugging-in-wordpress" target="_blank">
            <?php echo esc_html__( "WP_DEBUG_LOG", "wc-gsheetconnector" ); ?>
          </a>
          <?php echo esc_html__( "enabled, errors are stored in a log file. Here you can find the last 100 lines in reversed order so that you or the GSheetConnector support team can view it easily. The file cannot be edited here.", "wc-gsheetconnector" ); ?>
        </p>
        <button onclick="copyErrorLog()" class="copy-error-log"><?php echo esc_html__( "Copy Error Log to Clipboard", "wc-gsheetconnector" ); ?></button>
        <button class="wcgsc-clear-content-logs"><?php echo esc_html__( "Clear", "wc-gsheetconnector" ); ?></button>
        <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce" value="<?php echo esc_attr( wp_create_nonce('gs-ajax-nonce') ); ?>" />
        <div class="copy-message" style="display: none;"><?php echo esc_html__( "Copied", "wc-gsheetconnector" ); ?></div>
        <?php echo wp_kses_post( $wc_free_system_log->display_error_log() ); ?>
    </div>
</div>
<!-- popup file include herre -->
<?php include( WC_GSHEETCONNECTOR_PATH . "includes/pages/pro-popup.php" ) ;?>
