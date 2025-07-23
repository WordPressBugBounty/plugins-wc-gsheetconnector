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
        <span class="wcgsc-clear-loading-sign-logs">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <span class="wcgsc-clear-content-logs-msg"></span>
        <input type="hidden" name="wcgsc-ajax-nonce" id="wcgsc-ajax-nonce" value="<?php echo esc_attr( wp_create_nonce('wcgsc-ajax-nonce') ); ?>" />
        <div class="copy-message" style="display: none;"><?php echo esc_html__( "Copied", "wc-gsheetconnector" ); ?></div>
        <?php echo wp_kses_post( $wc_free_system_log->display_error_log() ); ?>
    </div>
</div>
<!-- popup file include herre -->
<?php include( WC_GSHEETCONNECTOR_PATH . "includes/pages/pro-popup.php" ) ;?>
