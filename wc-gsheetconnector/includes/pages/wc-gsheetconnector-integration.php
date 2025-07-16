<?php

// phpcs:disable
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe: tab selection used for UI only, no sensitive action
$Code = "";
$header = admin_url('admin.php?page=wc-gsheetconnector-config');
if (isset($_GET['code'])) {
    update_option('is_new_client_secret_wcgsc', 1);
    if (is_string($_GET['code'])) {
      $Code = sanitize_text_field($_GET["code"]);
    }
}

// phpcs:enable
?>

<!-- save code, alert and css -->
<div class="card-wcgsc dropdownoption-wcgsc">
    <div class="lbl-drop-down-select">
        <label for="wcgsc_dro_option"><?php echo esc_html__('Choose Google API Setting :', 'wc-gsheetconnector'); ?></label>
    </div>
    <div class="drop-down-select-btn">
        <select id="wcgsc_dro_option" name="wcgsc_dro_option">
            <option value="wcgsc_existing" selected><?php echo esc_html__('Use Existing Client/Secret Key (Auto Google API Configuration)', 'wc-gsheetconnector'); ?>
            </option>
            <option value="wcgsc_manual" disabled=""><?php echo esc_html__('Use Manual Client/Secret Key (Use Your Google API Configuration) (Upgrade To PRO)', 'wc-gsheetconnector'); ?></option>
        </select>
        <p class="int-meth-btn-wcgs"><a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank"><input type="button" name="save-method-api-wcgs" id="save-method-api-wcgs"
                value="<?php esc_html_e('Upgrade To PRO', 'wc-gsheetconnector'); ?>" class="button button-primary" />
            </a>
            <span class="tooltip"> <img src="<?php esc_url(WC_GSHEETCONNECTOR_URL); ?>assets/img/help.png"
                        class="help-icon"> <span
                        class="tooltiptext tooltip-right"><?php esc_html_e('Manual Client/Secret Key (Use Your Google API Configuration) method is available in the PRO version of the plugin.', 'wc-gsheetconnector'); ?></span></span>
        </p>
    </div>
</div> 
<input type="hidden" name="redirect_auth" id="redirect_auth"
    value="<?php echo (isset($header)) ? esc_attr($header) : ''; ?>">
<div class="card-wp">
  <div class="wcgsc-in-fields">
    <h2>
      <span class="title1"><?php esc_html_e( 'WooCommerce -', 'wc-gsheetconnector' ); ?></span>
      <span class="title"><?php esc_html_e( 'Google Sheets Integration', 'wc-gsheetconnector' ); ?></span>
    </h2>

    <hr>
    <?php if (empty($Code)) { ?>
    <div class="wcgsc-alert-kk" id="google-drive-msg">
      <p class="wcgsc-alert-heading"> <?php echo esc_html__('Authenticate with your Google account, follow these steps:', 'wc-gsheetconnector'); ?> </p>
      <ol class="wcgsc-alert-steps">
        <li><?php echo esc_html__('Click on the "Sign In With Google" button.', 'wc-gsheetconnector'); ?></li>
        <li><?php echo esc_html__('Grant permissions for the following:', 'wc-gsheetconnector'); ?>
          <ul class="wcgsc-alert-permissions">
            <li><?php echo esc_html__('Google Drive', 'wc-gsheetconnector'); ?></li>
            <li><?php echo esc_html__('Google Sheets', 'wc-gsheetconnector'); ?> <span class="wcgsc-alert-note"> <?php echo esc_html__('Ensure that you enable the checkbox for each of these services.', 'wc-gsheetconnector'); ?> </span></li>
          </ul>
        </li>
        <li><?php echo esc_html__('This will allow the integration to access your Google Drive and Google Sheets.', 'wc-gsheetconnector'); ?> </li>
      </ol>
    </div>
    <?php } ?>
    <p>
      <label style="/* color: #1d9838; *//* font-size: 14px; */color: #242628;font-size: 14px;font-weight: 600;line-height: 2.3;"> <?php echo esc_html__('Google Access Code', 'wc-gsheetconnector'); ?> </label>
      <?php if (!empty(get_option('wcgsc_token')) && get_option('wcgsc_token') !== "") { ?>
      <input type="text" name="wcgsc-code" id="wcgsc-code" value=""
                placeholder="<?php esc_html_e('Currently Active', 'wc-gsheetconnector'); ?>" disabled />
      <input type="button" name="wcgsc-deactivate-log" id="wcgsc-deactivate-log"
                value="<?php esc_html_e('Deactivate', 'wc-gsheetconnector'); ?>" class="button button-primary" />
      <span class="tooltip"> <img src="<?php esc_url (WC_GSHEETCONNECTOR_URL); ?>assets/img/help.png" class="help-icon"> <span class="tooltiptext tooltip-right">
      <?php esc_html_e('On deactivation, all your data saved with authentication will be removed and you need to reauthenticate with your Google account and configure sheet name and tab.', 'wc-gsheetconnector'); ?>
      </span> </span> <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <?php } else {
                $redirct_uri = admin_url('admin.php?page=wc-gsheetconnector-config');
            ?>
      <input type="text" name="wcgsc-code" id="wcgsc-code" value="<?php echo esc_attr($Code); ?>" readonly placeholder="<?php echo esc_html__('Click on Sign In With Google', 'wc-gsheetconnector'); ?>" oncopy="return false;" onpaste="return false;" oncut="return false;" />
      <?php if (empty($Code)) { ?>
        <a href="<?php echo esc_url( 'https://oauth.gsheetconnector.com/index.php?client_admin_url=' . rawurlencode( $redirct_uri ) . '&plugin=woocommercegsheetconnector' ); ?>"
           class="wcgsc_button">
           <img class="wcgsc_button" src="<?php echo esc_url( WC_GSHEETCONNECTOR_URL . '/assets/img/btn_google_signin_dark_pressed_web.gif' ); ?>" />
        </a>

      <?php } ?>
      <?php } ?>
      <br>
      <?php
      // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- OAuth callback, nonce not required
      if ( ! empty( $_GET['code'] ) ) {
      ?>

      <button type="button" name="wcgsc-save-code" class="blinking-button-wc" id="wcgsc-save-code"><?php echo esc_html__('Click here to Save Authentication Code', 'wc-gsheetconnector'); ?></button>
      <?php } ?>
      <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </p>
    <span id="deactivate-msg"></span>
    <input type="hidden" name="wcgsc-ajax-nonce" id="wcgsc-ajax-nonce"
       value="<?php echo esc_attr( wp_create_nonce( 'wcgsc-ajax-nonce' ) ); ?>" />

       <?php
            //resolved - google sheet permission issues - START
            $wc_gsheetconnector_verify = get_option('wcgsc_verify');
            if (!empty($wc_gsheetconnector_verify) && $wc_gsheetconnector_verify == "invalid-auth") {
            ?>
    <p style="color:#c80d0d; font-size: 14px; border: 1px solid;padding: 8px;"> 
      <?php echo esc_html(__('Something went wrong! It looks you have not given the permission of Google Drive and Google Sheets from your google account.Please Deactivate Auth and Re-Authenticate again with the permissions.','wc-gsheetconnector')); ?></p>
    <p style="color:#c80d0d;border: 1px solid;padding: 8px;"><img width="350px"
                    src="<?php esc_url (WC_GSHEETCONNECTOR_URL); ?>assets/img/permission_screen.png"></p>
    <p style="color:#c80d0d; font-size: 14px; border: 1px solid;padding: 8px;"> <?php echo esc_html(__('Also,', 'wc-gsheetconnector')); ?><a href="https://myaccount.google.com/permissions"
                    target="_blank"> <?php echo esc_html(__('Click Here ', 'wc-gsheetconnector')); ?></a> <?php echo esc_html(__(' and if it displays "GSheetConnector for WooCommerce" under Third-party apps with account access then remove it.', 'wc-gsheetconnector')); ?> </p>
    <?php
            } // Close the if condition
            //resolved - google sheet permission issues - END
            else {
                if (!empty(get_option('wcgsc_token')) && get_option('wcgsc_token') !== "") {
                    $google_sheet = new GSCWOO_googlesheet();
                    $email_account = $google_sheet->gsheet_print_google_account_email();
                    if ($email_account) {
                    ?>
    <p class="connected-account">
        <?php
        $raw_output = sprintf(
            // translators: %s is the connected email address.
            __( 'Connected Email Account: <u>%s</u>', 'wc-gsheetconnector' ),
            esc_html( $email_account )
        );

        echo wp_kses( $raw_output, array( 'u' => array() ) );
        ?>
    </p>

    <?php
                    } else {
                    ?>
    <p style="color:red"> <?php echo esc_html(__('Something went wrong! Your Auth code may be wrong or expired. Please Deactivate and Re-Authenticate.', 'wc-gsheetconnector')); ?> </p>
    <?php
                    }
                }
            }
            ?>
    <?php 
      if(!empty(get_option('wcgsc_verify')) && (get_option('wcgsc_verify') =="valid")){ ?>
    <p class="wcgsc-sync-row">
      <?php
        printf(
            // translators: %s is the HTML <a> link for syncing WooCommerce settings.
            esc_html__( '%s to fetch sheets detail for "WooCommerce Data Settings" tab.', 'wc-gsheetconnector' ),
            '<a id="wcgsc-sync" data-init="yes">' . esc_html__( 'Click here', 'wc-gsheetconnector' ) . '</a>'
        );
        ?>
      <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </p>

    <?php } 
       ?>
    
    <div id="wc-gsc-cta" class="wc-gsc-privacy-box">
      <div class="wc-gsc-table">
        <div class="wc-gsc-less-free"> <i class="dashicons dashicons-lock"></i>
          <p> <?php esc_html_e('We do not store any of the data from your Google account on our servers, everything is processed & stored on your server. We take your privacy extremely seriously and ensure it is never misused.', 'wc-gsheetconnector'); ?><br />
            <a href="https://gsheetconnector.com/usage-tracking/" target="_blank"><?php esc_html_e('Learn more.', 'wc-gsheetconnector'); ?></a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="two-col wc-free-box-help12">
  <div class="col wc-free-box12">
    <header>
      <h3>
        <?php
        // Translators: Section heading "Next steps…"
        echo esc_html__( 'Next steps…', 'wc-gsheetconnector' );
        ?>
      </h3>
    </header>

    <div class="wc-free-box-content12">
      <ul class="wc-free-list-icon12">
        <li>
          <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank">
            <div>
              <button class="icon-button">
                <span class="dashicons dashicons-star-filled"></span>
              </button>
              <strong><?php echo esc_html__( 'Upgrade to PRO', 'wc-gsheetconnector' ); ?></strong>
              <p><?php echo esc_html__( 'Sync Orders, Order wise data and much more...', 'wc-gsheetconnector' ); ?></p>
            </div>
          </a>
        </li>

        <li>
          <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro/requirements" target="_blank">
            <div>
              <button class="icon-button">
                <span class="dashicons dashicons-download"></span>
              </button>
              <strong><?php echo esc_html__( 'Compatibility', 'wc-gsheetconnector' ); ?></strong>
              <p><?php echo esc_html__( 'Compatibility with WooCommerce Third-Party Plugins', 'wc-gsheetconnector' ); ?></p>
            </div>
          </a>
        </li>

        <li>
          <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro" target="_blank">
            <div>
              <button class="icon-button">
                <span class="dashicons dashicons-chart-bar"></span>
              </button>
              <strong><?php echo esc_html__( 'Multi Languages', 'wc-gsheetconnector' ); ?></strong>
              <p><?php echo esc_html__( 'This plugin supports multi-languages as well!', 'wc-gsheetconnector' ); ?></p>
            </div>
          </a>
        </li>

        <li>
          <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro" target="_blank">
            <div>
              <button class="icon-button">
                <span class="dashicons dashicons-download"></span>
              </button>
              <strong><?php echo esc_html__( 'Support WordPress multisites', 'wc-gsheetconnector' ); ?></strong>
              <p><?php echo esc_html__( 'With the use of a Multisite, you’ll also have a new level of user-available: the Super Admin.', 'wc-gsheetconnector' ); ?></p>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- 2nd column -->
  <div class="col wc-free-box13">
    <header>
      <h3>
        <?php
        // Translators: Section heading "Product Support"
        echo esc_html__( 'Product Support', 'wc-gsheetconnector' );
        ?>
      </h3>
    </header>

    <div class="wc-free-box-content13">
      <ul class="wc-free-list-icon13">
        <li>
          <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro" target="_blank">
            <div>
              <span class="dashicons dashicons-book"></span>
              <strong><?php echo esc_html__( 'Online Documentation', 'wc-gsheetconnector' ); ?></strong>
              <p><?php echo esc_html__( 'Understand all the capabilities of Woocommerce GsheetConnector', 'wc-gsheetconnector' ); ?></p>
            </div>
          </a>
        </li>

        <li>
          <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro" target="_blank">
            <div>
              <span class="dashicons dashicons-sos"></span>
              <strong><?php echo esc_html__( 'Ticket Support', 'wc-gsheetconnector' ); ?></strong>
              <p><?php echo esc_html__( 'Direct help from our qualified support team', 'wc-gsheetconnector' ); ?></p>
            </div>
          </a>
        </li>

        <li>
          <a href="https://www.gsheetconnector.com/affiliates" target="_blank">
            <div>
              <span class="dashicons dashicons-admin-links"></span>
              <strong><?php echo esc_html__( 'Affiliate Program', 'wc-gsheetconnector' ); ?></strong>
              <p><?php echo esc_html__( 'Earn flat 30% on every sale!', 'wc-gsheetconnector' ); ?></p>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>