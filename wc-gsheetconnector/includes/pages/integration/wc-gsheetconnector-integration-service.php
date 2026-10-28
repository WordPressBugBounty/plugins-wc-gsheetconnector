<?php

/**
 * WooCommerce Google Sheet Integration Page - Service Account settings
 *
 * Only saves/displays the Service Account JSON. Option keys match the Pro plugin.
 *
 * @package     GSheetConnector for WooCommerce
 * @since       1.5.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit;
}

$wcgsc_service_json     = get_option('wcgsc_service_account_json', '');
$wcgsc_json_is_valid    = false;
$wcgsc_client_email     = '';

if (!empty($wcgsc_service_json)) {
  $wcgsc_decoded = json_decode($wcgsc_service_json, true);

  if (json_last_error() === JSON_ERROR_NONE && is_array($wcgsc_decoded) && isset($wcgsc_decoded['client_email'])) {
    $wcgsc_client_email  = sanitize_email($wcgsc_decoded['client_email']);
    $wcgsc_json_is_valid = true;
  }
}
?>

<div class="oauth-method row justify-between shadow-box mt-40 p-30">
  <div class="col-7">
    <div class="service-method mr-20">
      <div class="card-wp wcgsc-service-auth-area">

        <div class="heading mt-0"><?php echo esc_html__('Google Sheets Integration', 'wc-gsheetconnector'); ?>
          <span class="badge"><?php echo esc_html__('Service Account', 'wc-gsheetconnector'); ?></span>
        </div>

        <?php if (!$wcgsc_json_is_valid) : ?>
          <p><?php echo esc_html__('Connect WooCommerce to Google Sheets using a secure service account. Upload your JSON credentials and share your spreadsheet with the service account email to enable syncing.', 'wc-gsheetconnector'); ?></p>
          <div class="heading mt-30"><?php echo esc_html__('Upload or paste your JSON credentials', 'wc-gsheetconnector'); ?></div>

          <div class="gsc-json-wrapper">
            <label for="wcgsc_service_account_json" class="gsc-json-label">
              <?php echo esc_html__('Service Account JSON File', 'wc-gsheetconnector'); ?>
            </label>
            <textarea
              name="wcgsc_service_account_json"
              id="wcgsc_service_account_json"
              rows="10"
              class="wcgsc-service-json"
              placeholder="<?php echo esc_attr__('Paste your service account JSON credentials here...', 'wc-gsheetconnector'); ?>"><?php echo esc_textarea($wcgsc_service_json); ?></textarea>
            <div class="gsc-json-help">
              <?php echo esc_html__('This JSON file is used for authentication and is stored securely.', 'wc-gsheetconnector'); ?>
            </div>
            <div class="input-msg d-none">
              <?php echo esc_html__('Please fill out this field', 'wc-gsheetconnector'); ?>
            </div>
          </div>

          <div class="mt-30 mb-20">
            <div class="gsc-upload-wrapper">
              <input type="file" id="wcgsc_upload_service_json_file" accept=".json" />
              <label for="wcgsc_upload_service_json_file" class="gsc-upload-box">
                <span class="gsc-upload-icon">
                  <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
                    <path d="M12 16V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <path d="M8 8L12 4L16 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 16V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                  </svg>
                </span>
                <div class="gsc-upload-text">
                  <strong><?php echo esc_html__('Upload JSON File', 'wc-gsheetconnector'); ?></strong>
                  <span><?php echo esc_html__('Click to select your .json file', 'wc-gsheetconnector'); ?></span>
                </div>
              </label>
              <small class="gsc-upload-help">
                <?php echo esc_html__('Choose your service account JSON file. Its contents will be auto-filled above.', 'wc-gsheetconnector'); ?>
              </small>
            </div>
          </div>
          <input type="button" id="wcgsc-save-service-json" class="btn btn-primary mt-20 common-disable" value="<?php echo esc_attr__('Save', 'wc-gsheetconnector'); ?>" />
          <span class="wcgsc-loading-sign-service-auth"></span>
        <?php else : ?>
          <p><?php echo esc_html__('To enable syncing, share your Google Sheet with the service account email connected to this plugin and grant Editor access.', 'wc-gsheetconnector'); ?></p>
          <div class="gsc-service-success">
            <div class="gsc-connected-box d-flex justify-between flex-wrap gap-10 align-center mt-15">
              <div class="gsc-connected-left d-flex">
                <span class="gsc-connected-label">
                  <?php echo esc_html__('Service Account Email', 'wc-gsheetconnector'); ?>
                  <span class="tooltip" data-tooltip="<?php echo esc_attr__('Your Google Sheet must be shared with this service account email and granted Editor access to enable data synchronization.', 'wc-gsheetconnector'); ?>" data-tooltip-pos="right" data-tooltip-length="medium">
                    <i class="fa-solid fa-circle-question help-icon"></i>
                  </span>
                </span>
                <span class="service-email gsc-connected-email"><?php echo esc_html($wcgsc_client_email); ?>
                  <a href="#" class="ml-5 wcgsc-copy-btn mt-5" data-copy="authorized_domain">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19.53 8L14 2.47C13.8595 2.32931 13.6688 2.25018 13.47 2.25H11C10.2707 2.25 9.57118 2.53973 9.05546 3.05546C8.53973 3.57118 8.25 4.27065 8.25 5V6.25H7C6.27065 6.25 5.57118 6.53973 5.05546 7.05546C4.53973 7.57118 4.25 8.27065 4.25 9V19C4.25 19.7293 4.53973 20.4288 5.05546 20.9445C5.57118 21.4603 6.27065 21.75 7 21.75H14C14.7293 21.75 15.4288 21.4603 15.9445 20.9445C16.4603 20.4288 16.75 19.7293 16.75 19V17.75H17C17.7293 17.75 18.4288 17.4603 18.9445 16.9445C19.4603 16.4288 19.75 15.7293 19.75 15V8.5C19.7421 8.3116 19.6636 8.13309 19.53 8ZM14.25 4.81L17.19 7.75H14.25V4.81ZM15.25 19C15.25 19.3315 15.1183 19.6495 14.8839 19.8839C14.6495 20.1183 14.3315 20.25 14 20.25H7C6.66848 20.25 6.35054 20.1183 6.11612 19.8839C5.8817 19.6495 5.75 19.3315 5.75 19V9C5.75 8.66848 5.8817 8.35054 6.11612 8.11612C6.35054 7.8817 6.66848 7.75 7 7.75H8.25V15C8.25 15.7293 8.53973 16.4288 9.05546 16.9445C9.57118 17.4603 10.2707 17.75 11 17.75H15.25V19ZM17 16.25H11C10.6685 16.25 10.3505 16.1183 10.1161 15.8839C9.8817 15.6495 9.75 15.3315 9.75 15V5C9.75 4.66848 9.8817 4.35054 10.1161 4.11612C10.3505 3.8817 10.6685 3.75 11 3.75H12.75V8.5C12.7526 8.69811 12.8324 8.88737 12.9725 9.02747C13.1126 9.16756 13.3019 9.24741 13.5 9.25H18.25V15C18.25 15.3315 18.1183 15.6495 17.8839 15.8839C17.6495 16.1183 17.3315 16.25 17 16.25Z" fill="#199436"></path>
                    </svg>
                  </a>
                </span>
              </div>
              <div class="gsc-connected-status d-flex align-center">
                <span class="status-dot"></span>
                <?php echo esc_html__('Share sheet for sync', 'wc-gsheetconnector'); ?>
              </div>
            </div>
            <input type="button" id="wcgsc-deactivate-service-auth" class="btn deactivate-btn wcgsc-service-deactivate mt-30" value="<?php echo esc_attr__('Deactivate', 'wc-gsheetconnector'); ?>" />
            <span class="wcgsc-loading-sign-service-auth"></span>
          </div>
        <?php endif; ?>

        <div id="gs-woo-validation-message"></div>
        <div id="deactivate-msg"></div>
      </div>
    </div> <!-- service-method #end -->

    <div id="wcgsc-confirm-service-popup" class="wcgsc-popup-overlay d-none">
      <div class="wcgsc-popup text-center">
        <div class="gsc-modal-title"><?php echo esc_html__('Deactivate Integration', 'wc-gsheetconnector'); ?></div>
        <p class="gsc-modal-text"><?php echo esc_html__('Are you sure you want to remove the saved service account credentials?', 'wc-gsheetconnector'); ?></p>
        <div class="popup-actions d-flex justify-center gap-10">
          <button type="button" class="btn deactivate-btn" id="wcgsc-service-popup-cancel"><?php echo esc_html__('Cancel', 'wc-gsheetconnector'); ?></button>
          <button type="button" class="btn btn-primary" id="wcgsc-service-popup-confirm"><?php echo esc_html__('Deactivate', 'wc-gsheetconnector'); ?></button>
        </div>
      </div>
    </div>
  </div><!-- col-7 #end -->

  <?php if (!$wcgsc_json_is_valid) :
    $wcgsc_img_url = esc_url(WC_GSHEETCONNECTOR_URL) . 'assets/img/';
    $wcgsc_help_svg = '<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 19.5C16.1421 19.5 19.5 16.1421 19.5 12C19.5 7.85786 16.1421 4.5 12 4.5C7.85786 4.5 4.5 7.85786 4.5 12C4.5 16.1421 7.85786 19.5 12 19.5ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM12.75 15V16.5H11.25V15H12.75ZM10.5 10.4318C10.5 9.66263 11.1497 9 12 9C12.8503 9 13.5 9.66263 13.5 10.4318C13.5 10.739 13.3151 11.1031 12.9076 11.5159C12.5126 11.9161 12.0104 12.2593 11.5928 12.5292L11.25 12.7509V14.25H12.75V13.5623C13.1312 13.303 13.5828 12.9671 13.9752 12.5696C14.4818 12.0564 15 11.3296 15 10.4318C15 8.79103 13.6349 7.5 12 7.5C10.3651 7.5 9 8.79103 9 10.4318H10.5Z" fill="#080341" /></svg>';
    $wcgsc_console_url = 'https://console.cloud.google.com/iam-admin/serviceaccounts';
    $wcgsc_steps = array(
      array(
        'title' => __('Step-1 Open Google Cloud > Service Accounts', 'wc-gsheetconnector'),
        'tip'   => __('Open your Google Cloud project and go to IAM & Admin > Service Accounts to manage service identities.', 'wc-gsheetconnector'),
        'url'   => $wcgsc_console_url,
        'label' => __('Navigate to Service Accounts', 'wc-gsheetconnector'),
        'img'   => 'service-step1.png',
      ),
      array(
        'title' => __('Step-2 Create a New Service Account', 'wc-gsheetconnector'),
        'tip'   => __('Click Create Service Account and enter a meaningful name like ex.WooCommerce GSheet.', 'wc-gsheetconnector'),
        'url'   => $wcgsc_console_url . '/create',
        'label' => __('Create a Service Account', 'wc-gsheetconnector'),
        'img'   => 'service-step2.png',
      ),
      array(
        'title' => __('Step-3 Fill Service Account Details', 'wc-gsheetconnector'),
        'tip'   => __('Add Service Account Name & ID', 'wc-gsheetconnector'),
        'url'   => $wcgsc_console_url,
        'label' => __('Google Console', 'wc-gsheetconnector'),
        'img'   => 'service-step3.png',
      ),
      array(
        'title' => __('Step-4 Open Service Account Keys Section', 'wc-gsheetconnector'),
        'tip'   => __('Open the created service account and switch to the Keys tab to generate credentials.', 'wc-gsheetconnector'),
        'url'   => $wcgsc_console_url,
        'label' => __('Go to Service Account → Keys', 'wc-gsheetconnector'),
        'img'   => 'service-step4.png',
      ),
      array(
        'title' => __('Step-5 Download JSON Key File', 'wc-gsheetconnector'),
        'tip'   => __('Select JSON format and download the key file securely to your computer.', 'wc-gsheetconnector'),
        'url'   => $wcgsc_console_url,
        'label' => __('Download JSON Key File', 'wc-gsheetconnector'),
        'img'   => 'service-step5.png',
      ),
      array(
        'title' => __('Step-6 Upload JSON Key in WooCommerce', 'wc-gsheetconnector'),
        'tip'   => __('Select JSON format and download the key file securely to your computer.', 'wc-gsheetconnector'),
        'url'   => 'https://www.gsheetconnector.com/docs/woocommerce-gsheetconnector/service-account-setting-pro-version',
        'label' => __('Check our detailed guideline', 'wc-gsheetconnector'),
        'img'   => 'service-step7.png',
      ),
    );
  ?>
    <div class="col-5">
      <div class="ml-20 service-account-method">
        <div class="step-guide-col">
          <div class="heading mt-0"><?php echo esc_html__('Connection Guide', 'wc-gsheetconnector'); ?>
            <span class="badge"><?php echo esc_html__('Step-by-Step', 'wc-gsheetconnector'); ?></span>
          </div>
          <p><?php echo esc_html__('Follow these step-by-step instructions to create and configure your Google service account and securely connect it with WooCommerce.', 'wc-gsheetconnector'); ?></p>
        </div>

        <div class="gsc-slider-wrapper mt-30">
          <div class="gsc-slider">
            <?php foreach ($wcgsc_steps as $wcgsc_step) : ?>
              <div class="gsc-slide">
                <div class="gsc-slider-headers fw-600 mb-10 text-dark"><?php echo esc_html($wcgsc_step['title']); ?>
                  <a href="#" class="i-help" hover-tooltip="<?php echo esc_attr($wcgsc_step['tip']); ?>"><?php echo $wcgsc_help_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup ?></a>
                </div>
                <a href="<?php echo esc_url($wcgsc_step['url']); ?>" target="_blank" rel="noopener noreferrer" class="link"><?php echo esc_html($wcgsc_step['label']); ?></a>
                <img src="<?php echo esc_url($wcgsc_img_url . $wcgsc_step['img']); ?>" alt="<?php echo esc_attr($wcgsc_step['title']); ?>" />
              </div>
            <?php endforeach; ?>
          </div>
          <button class="gsc-nav prev">❮</button>
          <button class="gsc-nav next">❯</button>
        </div><!-- slider #end -->
      </div>
    </div>
  <?php else : ?>
    <div class="col-5">
      <div class="heading mt-0"><?php echo esc_html__('How to Connect Google Sheets', 'wc-gsheetconnector'); ?></div>
      <p><?php echo esc_html__('This guide shows how to share your Google Sheet with the connected service account and grant editor access for seamless synchronization.', 'wc-gsheetconnector'); ?></p>
      <img src="<?php echo esc_url(WC_GSHEETCONNECTOR_URL . 'assets/img/service-account-email-share.gif'); ?>" class="mt-30" alt="" />
    </div>
  <?php endif; ?>
</div><!-- oauth-method #end -->
