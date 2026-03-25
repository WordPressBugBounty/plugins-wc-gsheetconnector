<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Custom footer text with review link
function wcgsc_gsheetconnector_admin_footer_text() {
  $review_url  = 'https://wordpress.org/support/plugin/wc-gsheetconnector/reviews/';
  $plugin_name = 'GSheetConnector for WooCommerce';

  $text = sprintf(
    /* translators: %1$s: plugin name, %2$s: link to reviews */
    esc_html__(
      'Enjoy using %1$s? Check out our reviews or leave your own on %2$s.',
      'wc-gsheetconnector'
    ),
    '<strong>' . esc_html( $plugin_name ) . '</strong>',
    '<a href="' . esc_url( $review_url ) . '" target="_blank" rel="noopener">' . esc_html__( 'WordPress.org', 'wc-gsheetconnector' ) . '</a>'
  );

    // Allowed HTML for strict escaping
  $allowed_html = array(
    'span' => array(
      'id'    => array(),
      'class' => array(),
    ),
    'strong' => array(),
    'a' => array(
      'href'   => array(),
      'target' => array(),
      'rel'    => array(),
    ),
  );

  echo wp_kses( '<span id="footer-left" class="alignleft">' . $text . '</span>', $allowed_html );
}
add_filter( 'admin_footer_text', 'wcgsc_gsheetconnector_admin_footer_text' );
?>

<div class="wcgsc-footer-promotion">
  <p><?php echo esc_html__( 'Made with love by the GSheetConnector Team', 'wc-gsheetconnector' ); ?></p>

  <ul class="wcgsc-footer-promotion-links">
    <li>
      <a href="<?php echo esc_url( 'https://www.gsheetconnector.com/support' ); ?>" target="_blank" rel="noopener">
        <?php esc_html_e( 'Support', 'wc-gsheetconnector' ); ?>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://www.gsheetconnector.com/docs/woocommerce-gsheetconnector' ); ?>" target="_blank" rel="noopener">
        <?php esc_html_e( 'Docs', 'wc-gsheetconnector' ); ?>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://profiles.wordpress.org/westerndeal/#content-plugins' ); ?>" target="_blank" rel="noopener">
        <?php esc_html_e( 'Free Plugins', 'wc-gsheetconnector' ); ?>
      </a>
    </li>
  </ul>

  <ul class="wcgsc-footer-promotion-social">
    <li>
      <a href="<?php echo esc_url( 'https://www.facebook.com/gsheetconnectorofficial' ); ?>" target="_blank" rel="noopener">
        <i class="fa-brands fa-square-facebook"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://www.instagram.com/gsheetconnector/' ); ?>" target="_blank" rel="noopener">
        <i class="fa-brands fa-square-instagram"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://www.linkedin.com/company/gsheetconnector/' ); ?>" target="_blank" rel="noopener">
        <i class="fa-brands fa-linkedin"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://twitter.com/gsheetconnector?lang=en' ); ?>" target="_blank" rel="noopener">
        <i class="fa-brands fa-square-x-twitter"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( 'https://www.youtube.com/@GSheetConnector?sub_confirmation=1' ); ?>" target="_blank" rel="noopener">
        <i class="fa-brands fa-square-youtube"></i>
      </a>
    </li>
  </ul>
</div>