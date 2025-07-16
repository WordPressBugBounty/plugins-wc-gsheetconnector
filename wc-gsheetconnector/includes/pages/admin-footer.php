<?php
// Custom footer text with review link
function wc_gsheetconnector_admin_footer_text() {
    $review_url  = 'https://wordpress.org/support/plugin/wc-gsheetconnector/reviews/';
    $plugin_name = 'GSheetConnector for WC';

    $text = sprintf(
        /* translators: %1$s: plugin name, %2$s: link to reviews */
        esc_html__(
            'Enjoy using %1$s? Check out our reviews or leave your own on %2$s.',
            'wc-gsheetconnector'
        ),
        '<strong>' . esc_html( $plugin_name ) . '</strong>',
        '<a href="' . esc_url( $review_url ) . '" target="_blank" rel="noopener">' . esc_html__( 'WordPress.org', 'wc-gsheetconnector' ) . '</a>'
    );

    echo wp_kses_post( '<span id="footer-left" class="alignleft">' . $text . '</span>' );
}
add_filter( 'admin_footer_text', 'wc_gsheetconnector_admin_footer_text' );
?>

<div class="wcgsc-footer-promotion">
  <p><?php echo esc_html__( 'Made with ♥ by the GSheetConnector Team', 'wc-gsheetconnector' ); ?></p>

  <ul class="wcgsc-footer-promotion-links">
    <li><a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro" target="_blank" rel="noopener"><?php esc_html_e( 'Support', 'wc-gsheetconnector' ); ?></a></li>
    <li><a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro" target="_blank" rel="noopener"><?php esc_html_e( 'Docs', 'wc-gsheetconnector' ); ?></a></li>
    <li><a href="https://www.facebook.com/gsheetconnectorofficial" target="_blank" rel="noopener"><?php esc_html_e( 'VIP Circle', 'wc-gsheetconnector' ); ?></a></li>
    <li><a href="https://profiles.wordpress.org/westerndeal/#content-plugins" target="_blank" rel="noopener"><?php esc_html_e( 'Free Plugins', 'wc-gsheetconnector' ); ?></a></li>
  </ul>

  <ul class="wcgsc-footer-promotion-social">
    <li><a href="https://www.facebook.com/gsheetconnectorofficial" target="_blank" rel="noopener"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
    <li><a href="https://www.instagram.com/gsheetconnector/" target="_blank" rel="noopener"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
    <li><a href="https://www.linkedin.com/in/abdullah17/" target="_blank" rel="noopener"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
    <li><a href="https://twitter.com/gsheetconnector?lang=en" target="_blank" rel="noopener"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
    <li><a href="https://www.youtube.com/@GSheetConnector" target="_blank" rel="noopener"><i class="fa fa-youtube-square" aria-hidden="true"></i></a></li>
  </ul>
</div>
