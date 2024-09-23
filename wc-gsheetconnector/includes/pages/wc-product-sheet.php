<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}

?>
<div id="opener">
<div class="gs-woo-fields"> <a class="gs-woo-list-set" data-id="1" href="#0">
  <p class="maxi_mize maxi_mize1"><i class="fa fa-plus" aria-hidden="true"></i></p>
  <p class="mini_mize mini_mize1"><i class="fa fa-minus" aria-hidden="true"></i></p>
  <h2> <span class="title1"><?php echo esc_html( __( 'Google Sheet to WooCommerce Sync Configuration', 'wc-gsheetconnector' ) ); ?> </span>  <span class="pro-ver"><?php echo __("PRO", "wc-gsheetconnector"); ?></span> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span class="tooltiptext-set tooltip-right-set">
   <?php echo __("We're excited to introduce the Google Sheet to WooCommerce Sync feature. Now, you can seamlessly add single or bulk products within your connected sheet. Simply follow the provided instructions, and click to sync, and watch as your products are seamlessly sent into your WooCommerce store.", "wc-gsheetconnector"); ?>    </span> </span> <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>  </h2>
  </a>
  <hr>
  <br>
  <div class=" gs-woo-list-set1">
    <div class="sheet-details">
      <h3 class="systemifo"><?php echo esc_html( __( 'Connected WooCommerce Google Sheet', 'wc-gsheetconnector' ) ); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span class="tooltiptext-set tooltip-right-set"><?php echo __(" Connected WooCommerce Google Sheet, you'll see the linked sheet. Please note that sheet settings cannot be updated from this interface. ", "wc-gsheetconnector"); ?>
              </span> </span> </h3>
      <label class="productsheetlabel"><?php echo esc_html( __( 'Google Spreadsheet Name', 'wc-gsheetconnector' ) ); ?></label>
                        <input type="text" name="" value="Plugin Test" disabled="disabled" class="productsheetname">
      <p class="sheet-url-ps" id="sheet-url">
                <label class="productsheetlabel"><?php echo esc_html( __( 'Google Spreadsheet URL', 'wc-gsheetconnector' ) ); ?></label>
        <a href="https://docs.google.com/spreadsheets/d/1tHVvTNzNRnn03uZsCQmvrPh55rDx58c0YSXnos-nvy8" target="_blank">
        <input type="button" disabled="disabled" id="viewsheet" name="viewsheet" value="View Spreadsheet">
        </a>
              </p>
    </div>
  </div>
</div> <!-- #end -->


<div class="system-debug-logs"> <a class="gs-woo-list-set" data-id="2" href="#0">
  <p class="maxi_mize maxi_mize2"><i class="fa fa-plus" aria-hidden="true"></i></p>
  <p class="mini_mize mini_mize2"><i class="fa fa-minus" aria-hidden="true"></i></p>
  <h2> <span class="title1"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo esc_html( __( 'Sync Settings for Two-Way Sync - Insert & Updates', 'wc-gsheetconnector' ) ); ?> </span>  <span class="pro-ver">PRO</span> <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </h2>
  </a>
  <hr>
  <br>
  <div class=" gs-woo-list-set2 gs-woo-list-sync">
    <div class="sheet-details">
      <h3> <?php echo esc_html( __( '2-Way Sync Settings Insert & Updates', 'wc-gsheetconnector' ) ); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span class="tooltiptext-set tooltip-right-set">
        <?php echo __(" These sync settings facilitate the seamless transfer of records to WooCommerce within the selected Google Sheet! ", "wc-gsheetconnector"); ?>
              </span> </span> </h3>
      <h4><?php echo esc_html( __( 'Considerations for Two-Way Sync Insert & Updates:', 'wc-gsheetconnector' ) ); ?></h4>
      <ol class="woogsc-gs-alert-steps">
        <li><?php echo esc_html( __( 'Ensure the header names remain unchanged from the default settings during synchronization.', 'wc-gsheetconnector' ) ); ?></li>
        <li><?php echo esc_html( __( 'Leave the Product ID field blank; it will be automatically updated once the sync is processed. If a Product ID is present, the sync will fail and display an error.', 'wc-gsheetconnector' ) ); ?></li>
        <li><?php echo esc_html( __( 'To add multiple Product Categories and tags, separate them with commas (,).', 'wc-gsheetconnector' ) ); ?></li>
         <li><?php echo esc_html( __('Mandatory fields required Product name to be entered, and make sure to add a dash (-) in the last column, if the last column is empty.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __( 'Specify the rows for synchronization. For a single row, input the same number in both text boxes.', 'wc-gsheetconnector' ) ); ?></li>
        <li><?php echo esc_html( __( 'Example of Single Row: If the product is in row 5, enter "5" in both boxes and click to sync.', 'wc-gsheetconnector' ) ); ?></li>
        <li><?php echo esc_html( __( 'Example of Multiple Rows: If products are listed in rows 5-10, input "5" and "10" in the respective boxes and initiate the sync.', 'wc-gsheetconnector' ) ); ?></li>
        <li><?php echo esc_html( __( 'In the Image column, upload the image from media, copy the uploaded image path, and paste it in the corresponding column for each product.', 'wc-gsheetconnector' ) ); ?></li>
        <li><?php echo esc_html( __( 'For the Product Downloadable Files column, upload a single file from media, copy the uploaded file path, and paste it in the relevant column for each product.', 'wc-gsheetconnector' ) ); ?></li>
       <li><?php echo __('Please refer to the knowledgebase/docs for a comprehensive <a href="https://support.gsheetconnector.com/kb/woocommerce-gsheetconnector-2-way-sync-products" target="_blank">list of supported fields available for two-way synchronization.</a>', 'wc-gsheetconnector'); ?></li>
      </ol>
      <br>
      <h3 class="systemifo"><i class="fa fa-refresh" aria-hidden="true"></i><?php echo esc_html( __( 'Product Sync Configuration: Sheet to WooCommerce Insert', 'wc-gsheetconnector' ) ); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span class="tooltiptext-set tooltip-right-set">
        <?php echo __("This configuration includes two selection boxes for specifying the range of rows to sync.
                            Example of Single Row: If you've added a product in row 5, input '5' in both boxes and click to sync.
                            Example of Multiple Rows: If products are listed in rows 5 through 10, input '5' and '10' in the respective boxes and click to sync. ", "wc-gsheetconnector"); ?>       </span> </span> </h3>
      <p class="note"><?php echo esc_html( __( 'Leave the Product ID field blank; it will be automatically updated once the sync is processed. If a Product ID is present, the sync will fail and display an error.', 'wc-gsheetconnector' ) ); ?></p>
      <form method="post" id="product-sheet-to-wc-form-insert" action="">
        <label for="quantity"><?php echo esc_html( __( ' Enter Rows Range: from ', 'wc-gsheetconnector' ) ); ?></label>
        <input type="number" id="rangeStart" name="range_start" min="2" disabled="disabled" value="" required="" style="width: 100px;">
        <label for="quantity"><?php echo esc_html( __( ' to ', 'wc-gsheetconnector' ) ); ?></label>
        <input type="number" id="rangeEnd" name="range_end" min="2" value=""  required="" style="width: 100px;" disabled="disabled">
        <h3>
          <input type="" style="width:162px;" class="button button-primary button-large product-sheet-to-wc-insert"  name="gs_woo_product_sheet_settings" value="<?php echo __("Click to Sync Insert", "wc-gsheetconnector"); ?>" />
           </h3>
      </form>
    </div>
    <!-- sheet deatail #end --> 
    
    <!-- new added --> 
    <br>
    <hr>
    <br>
    <div class="sheet-details">
    
      <h3 class="systemifo"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo esc_html( __( 'Product Sync Configuration: Sheet to WooCommerce Update', 'wc-gsheetconnector' ) ); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span class="tooltiptext-set tooltip-right-set">
        <?php echo __(" This configuration includes two selection boxes for specifying the range of rows to sync.
                            Example of Single Row: If you've added a product in row 5, input '5' in both boxes and click to sync.
                            Example of Multiple Rows: If products are listed in rows 5 through 10, input '5' and '10' in the respective boxes and click to sync.", "wc-gsheetconnector"); ?>
               </span> </span> </h3>
      <p class="note"><?php echo esc_html( __( 'Product ID can not empty in the sheet.If a Product ID is not present in the sheet, the sync will fail and display an error.', 'wc-gsheetconnector' ) ); ?></p>
      <form method="post" action="">
        <label for="quantity"><?php echo esc_html( __( ' Enter Rows Range: from ', 'wc-gsheetconnector' ) ); ?> </label>
        <input type="number" id="rangeStartUpdate" name="range_start_update" min="2" disabled="disabled" value="" required="" style="width: 100px;">
        <label for="quantity"><?php echo esc_html( __( ' to ', 'wc-gsheetconnector' ) ); ?></label>
        <input type="number" id="rangeEndUpdate" name="range_end_update" min="2" disabled="disabled" value="" required="" style="width: 100px;">
        <h3>
          <input type="" id="update-btn" style="width:162px;" class="button button-primary button-large product-sheet-to-wc-update" name="gs_woo_product_sheet_settings_update" value="<?php echo __("Click to Sync Update", "wc-gsheetconnector"); ?>" />
           </h3>
      </form>
    </div>
  </div>
</div> <!-- #end -->


        <div class="system-debug-logs"> <a class="gs-woo-list-set" data-id="3" href="#0">
  <p class="maxi_mize maxi_mize3"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
  <p class="mini_mize mini_mize3"><i class="fa fa-minus" aria-hidden="true"></i></p>
  <h2> <span class="title1"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo esc_html( __( 'Sync Settings for Two-Way Sync - Order Status Updates', 'wc-gsheetconnector' ) ); ?> </span> <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </h2>
  </a>
  <hr>
  </br>
  <div class=" gs-woo-list-set3 gs-woo-list-sync">
    <div class="sheet-details">
      <h3> <?php  echo esc_html( __('2-Way Sync Settings Order Status', 'wc-gsheetconnector' )); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span
                        class="tooltiptext-set tooltip-right-set">
        <?php _e("These sync settings facilitate the seamless transfer of records to WooCommerce within the selected Google Sheet!", 'wc-gsheetconnector'); ?>
        </span> </span> </h3>
      <h4><?php  echo esc_html( __('Considerations for Two-Way Sync Order Status Updates:', 'wc-gsheetconnector' )); ?></h4>
      <ol class="woogsc-gs-alert-steps">
         <li><?php echo esc_html( __('To update the Orders Status from Google Sheet to WooCommerce Store use the “All Orders” named Sheet Tab.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Ensure the header names remain unchanged from the default settings during synchronization.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Specify the rows for synchronization. For a single row, input the same number in both text boxes.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Example of Single Row: If the order is in row 5, enter "5" in both boxes and click to sync.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Example of Multiple Rows: If orders are listed in rows 5-10, input "5" and "10" in the respective boxes and initiate the sync.', 'wc-gsheetconnector')); ?></li>
         <li><?php echo __('Please refer to the knowledgebase/docs for a comprehensive <a href="https://support.gsheetconnector.com/kb/woocommerce-gsheetconnector-2-way-sync-products" target="_blank">list of supported fields available for two-way synchronization.</a>', 'wc-gsheetconnector'); ?></li>
      </ol>
      <br>
      <h3 class="systemifo"><i class="fa fa-refresh" aria-hidden="true"></i><?php echo esc_html( __( ' Order Status Sync Configuration: Sheet to WooCommerce Update', 'wc-gsheetconnector' ) ); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span
                        class="tooltiptext-set tooltip-right-set">
        <?php _e("This configuration includes two selection boxes for specifying the range of rows to sync.
                            Example of Single Row: If you've added a order in row 5, input '5' in both boxes and click to sync.
                            Example of Multiple Rows: If orders are listed in rows 5 through 10, input '5' and '10' in the respective boxes and click to sync.", 'wc-gsheetconnector'); ?>
        </span> </span> </h3>
      <p class="note"><?php echo  esc_html( __('Order Id can not be empty in the sheet. If a Order Id is not present in the sheet, the sync will fail and display an error.', 'wc-gsheetconnector')); ?></p>
      <form method="post" id="order-status-sheet-to-wc-form-update" action="">
        
     
        <label for="quantity"><?php echo esc_html( __( ' Enter Rows Range: from ', 'wc-gsheetconnector' ) ); ?> </label>
        <input type="number" id="OrderstatusrangeStart" name="order_status_range_start" min="2" value="" required="" disabled="disabled" style="width: 100px;">
        <label for="quantity"><?php echo esc_html( __( ' to ', 'wc-gsheetconnector' ) ); ?></label>
        <input type="number" id="OrderstatusrangeEnd" name="order_status_range_end" min="2" value="" disabled="disabled" required=""  style="width: 100px;">
    
        <h3>
          <input type="submit" class="button button-primary button-large order-status-sheet-to-wc-update"  name="gs_woo_order_sheet_settings" value="<?php echo __("Click to Order Status Sync Update", "wc-gsheetconnector"); ?>"/>
          <span class="wcgsc-product-sheet-loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </h3>
           
      </form>
    </div>
    <!-- sheet deatail #end --> 
    </div>
</div>
<div class="system-debug-logs"> <a class="gs-woo-list-set" data-id="4" href="#0">
  <p class="maxi_mize maxi_mize4"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
  <p class="mini_mize mini_mize4"><i class="fa fa-minus" aria-hidden="true"></i></p>
  <h2> <span class="title1"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo esc_html( __( 'Sync Settings for Two-Way Sync - Insert Orders', 'wc-gsheetconnector' ) ); ?> </span> <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </h2>
  </a>
  <hr>
  </br>
  <div class=" gs-woo-list-set4 gs-woo-list-sync">
    <div class="sheet-details">
      <h3> <?php  echo esc_html( __('2-Way Sync Settings Insert Orders', 'wc-gsheetconnector' )); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span
                        class="tooltiptext-set tooltip-right-set">
        <?php _e("These sync settings facilitate the seamless transfer of records to WooCommerce within the selected Google Sheet!", 'wc-gsheetconnector'); ?>
        </span> </span> </h3>
      <h4><?php  echo esc_html( __('Considerations for Two-Way Sync Insert Orders:', 'wc-gsheetconnector' )); ?></h4>
      <ol class="woogsc-gs-alert-steps">
         <li><?php echo esc_html( __('To Insert the Orders from Google Sheet to WooCommerce Store use the “All Orders” named Sheet Tab.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Ensure the header names remain unchanged from the default settings during synchronization.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Leave the Order Id field blank; it will be automatically updated once the sync is processed. If a Order Id is present, the sync will fail and display an error.', 'wc-gsheetconnector')); ?></li>
       <li><?php echo esc_html( __('Mandatory fields required Product ID and Order Status to be entered, and make sure to add a dash (-) in the last column, if the last column is empty.', 'wc-gsheetconnector')); ?></li>
       <li><?php echo esc_html( __('Specify the rows for synchronization. For a single row, input the same number in both text boxes.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Example of Single Row: If the order is in row 5, enter "5" in both boxes and click to sync.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo esc_html( __('Example of Multiple Rows: If orders are listed in rows 5-10, input "5" and "10" in the respective boxes and initiate the sync.', 'wc-gsheetconnector')); ?></li>
        <li><?php echo __('Please refer to the knowledgebase/docs for a comprehensive <a href="https://support.gsheetconnector.com/kb/woocommerce-gsheetconnector-2-way-sync-products" target="_blank">list of supported fields available for two-way synchronization.</a>', 'wc-gsheetconnector'); ?></li>
      </ol>
      <br>
      <h3 class="systemifo"><i class="fa fa-refresh" aria-hidden="true"></i><?php echo esc_html( __( 'Order Insert Sync Configuration: Sheet to WooCommerce Insert', 'wc-gsheetconnector' ) ); ?> <span class="tooltip-set"> <i class="gswoo-helop-icon fa fa-question-circle"></i> <span
                        class="tooltiptext-set tooltip-right-set">
        <?php _e("This configuration includes two selection boxes for specifying the range of rows to sync.
                            Example of Single Row: If you've added a order in row 5, input '5' in both boxes and click to sync.
                            Example of Multiple Rows: If orders are listed in rows 5 through 10, input '5' and '10' in the respective boxes and click to sync.", 'wc-gsheetconnector'); ?>
        </span> </span> </h3>
      <p class="note"><?php echo  esc_html( __('Leave the Order Id field blank; it will be automatically updated once the sync is processed. If a Order Id is present, the sync will fail and display an error.', 'wc-gsheetconnector')); ?></p>
      <form method="post" id="order-sheet-to-wc-form-insert" action="">
        
     
        <label for="quantity"><?php echo esc_html( __( ' Enter Rows Range: from ', 'wc-gsheetconnector' ) ); ?> </label>
        <input type="number" id="OrderrangeStart" name="order_range_start" min="2" value="" required="" disabled="" style="width: 100px;">
        <label for="quantity"><?php echo esc_html( __( ' to ', 'wc-gsheetconnector' ) ); ?></label>
        <input type="number" id="OrderrangeEnd" name="order_range_end" min="2" value="" required="" disabled="" style="width: 100px;">
    
        <h3>
          <input type="submit" class="button button-primary button-large order-sheet-to-wc-insert"  name="gs_woo_order_sheet_settings" value="<?php echo __("Click to Sync Order", "wc-gsheetconnector"); ?>"/>
          <span class="wcgsc-product-sheet-loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </h3>
          
      </form>
    </div>
    <!-- sheet deatail #end --> 
    
    <!-- new added --> 
   <!--  <br  />
    <hr /> -->
  
  </div>
</div>
</div>
    <!-- new added --> 
   <!--  <br  />
    <hr /> -->
  
  
<!-- popup file include herre -->         
<?php include( WC_GSHEETCONNECTOR_PATH . "includes/pages/pro-popup.php" ) ;?>
