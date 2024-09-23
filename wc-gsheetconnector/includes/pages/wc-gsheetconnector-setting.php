<?php
// Get selected Sheet
$selected_sheet_key = get_option( 'gs_woo_settings' );
// Get all sheet details of the connected account
$sheet_data = get_option( 'gs_woo_sheet_feeds' );
// Get order states/ Tab names
$selected_order_states = get_option( 'gscwc_order_states' );
$woo_service = new wc_gsheetconnector_Service();

$adding_extra_order_row = $woo_service->get_adding_extra_order_row();
$adding_extra_product_item_row = $woo_service->get_adding_extra_product_item_row();
$adding_extra_product_row = $woo_service->get_adding_extra_product_row();
?>
<?php
// Check if the user is authenticated
   $authenticated = get_option('gs_woo_token');
  
   $per = get_option('gs_woo_verify');
   // check user is authenticated when save existing api method
  $show_setting = 0;
      
 if ((!empty($authenticated) && $per == "valid") ) {
    $show_setting = 1;
}
else{
 ?>
 <p class="wc-display-note">
        <?php 
        echo __( '<strong>Authentication Required:</strong>
              You must have to <a href="admin.php?page=wc-gsheetconnector-config&tab=integration" target="_blank">Authenticate using your Google Account</a> along with Google Drive and Google Sheets Permissions in order to enable the settings for configuration.', 'wc-gsheetconnector' );
        ?>
       
    </p>
 <?php 
}

if($show_setting == 1){
  ?>
    <form method="post" id="gsSettingFormFree">

        <div class="gs-woo-fields">
            <h2>
                <span
                    class="title11"><?php echo esc_html( __( 'WooCommerce Google Sheet Settings', 'wc-gsheetconnector' ) ); ?></span>

            </h2>
            <hr>
            </br>

            <div class="gs-woo-in-fields">
                <div class="sheet-details <?php echo esc_html($class,'wc-gsheetconnector'); ?>">
                    <p>
                        <label><?php echo esc_html( __( 'Google Sheet Name', 'wc-gsheetconnector' ) ); ?></label>
                        <select name="gs-woo-sheet-id" id="gs-woo-sheet-id">
                            <option value=""><?php echo __( 'Select', 'wc-gsheetconnector' ); ?></option>

                            <?php
    							if ( ! empty( $sheet_data ) ) {
    								foreach ( $sheet_data as $key => $value ) {
    									$selected = "";
    									if ( $selected_sheet_key !== "" && $key == $selected_sheet_key ) {
    										$selected = "selected";
    									}
    									?>
                            <option value="<?php echo esc_html($key,'wc-gsheetconnector'); ?>"
                                <?php echo esc_html($selected,'wc-gsheetconnector'); ?>>
                                <?php echo esc_html($value['sheet_name'],'wc-gsheetconnector'); ?></option>
                            <?php
    								}
    							}
    						?>
                        </select>
                        <span class="error_msg" id="error_spread"></span>

                        <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
                            value="<?php echo wp_create_nonce( 'gs-ajax-nonce' ); ?>" />

                    </p>

                    <p class="sheet-url" id="sheet-url">
                        <?php $sheet_id	 = "";
    					
    					if ( ! empty( $selected_sheet_key ) ) {
    						$sheet_id	 = $selected_sheet_key; ?>
                        <label><?php echo __( 'Google Sheet URL', 'wc-gsheetconnector' ); ?></label>
                        <a href="https://docs.google.com/spreadsheets/d/<?php echo esc_html($sheet_id,'wc-gsheetconnector'); ?>"
                            target="_blank"><input type="button" id="viewsheet" name="viewsheet"
                                value="<?php echo __( 'View Spreadsheet', 'wc-gsheetconnector' ); ?>"></a>
                        <?php    
    					}
    					?>
                    </p>

                    <br />

                    <p class="gs-woo-sync-row">
                        <?php echo __( 'Not showing Sheet Name, and Sheet URL Link ? Then <a id="gs-woo-sync" data-init="yes"> Click here </a> to fetch it. ', 'wc-gsheetconnector' ); ?><span
                            class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                    <p id="gs-woo-validation-message"></p>

                </div>
            </div>
        </div>
        </br>

        <div class="gs-woo-tabs-set" >
            <h2><span class="title1"><?php echo esc_html( __( 'Google Sheets/Tab Name ', 'wc-gsheetconnector' ) ); ?>
                </span>
            </h2>
          <span class="error_msg" id="error_gsTabName"></span>
            <?php $order_state_list = $woo_service->status_and_sheets;
             foreach ( $order_state_list as $key => $state_name ) {
    			$order_state_checked = "";
    			if(!empty($selected_order_states)){
    				if ( in_array( $key, $selected_order_states ) ) {
    					$order_state_checked = "checked";
    				}
    			}
    			?>
            <div class="gs-woo-cards">
                <span class="woo-pointer">
                    <input type="checkbox" class="wc_order_state check-toggle" name="wc_order_state[]"
                        value="<?php echo esc_html($key,'wc-gsheetconnector'); ?>"
                        <?php echo esc_html($order_state_checked,'wc-gsheetconnector'); ?> id="<?php echo $key; ?>"
                        style="display: none;"><?php echo esc_html($state_name,'wc-gsheetconnector'); ?>
                    <label for="<?php echo $key; ?>" class="button-woo-toggle"></label>
                </span>
            </div>
            <?php } ?>

            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    All Orders <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
            
          
        </div>
        <br class="clear">
        <div class="woo-header1" hidden>
            <h2>
                <span class="title1"><?php echo esc_html( __( 'Headers ', 'wc-gsheetconnector' ) ); ?> </span>
            </h2>
            <hr>
            <br class="clear">
            <ul>
                <?php 
    		$header_list = $woo_service->sheet_headers;
    		foreach( $header_list as $header => $data ) { ?>
               <li class="li-woo-header1">
                    <i class="fa fa-sort sort-icon1"></i>
                    <div class="switch-label1">
                        <label>
                            <span class='label1'>
                                <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                <div class="edit_col_name1"><span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                            class="fa fa-pencil"></i></div>
                        </label>
                    </div>

                  <div class="toggle-buttom-pos">
                        <span class="tooltip11"><span
                                class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                            <label for="<?php echo $header ?>-one"
                                class="button-woo-toggle1 button-tog-active product_headers-lbl"
                                id="button-woo-toggle1-click"></label>
                    </div>

                    </span>

                </li>
                <?php } ?>
            </ul>
        </div>
  
  <div id="opener">      
 <div class="gs-woo-google-set">
        <a class="gs-woo-list-set" data-id="12" href="#0">
            <p class="maxi_mize maxi_mize12"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
            <p class="mini_mize mini_mize12"><i class="fa fa-minus" aria-hidden="true"></i></p>
            <h2>
                <span
                    class="title1"><?php echo esc_html( __( ' Custom Order Status ', 'wc-gsheetconnector' ) ); ?>
                </span>
                 <span class="pro-ver"><?php echo __( 'PRO', 'wc-gsheetconnector' ); ?></span>
                
            </h2>
        </a>
        <hr>
        </br>
        <!-- custom order status -->
        <div class="gs-woo-list-set12">
         <?php 
           $corder_statuses = wc_get_order_statuses();
           $wc_custom_order_status = array_diff($corder_statuses, ["Pending payment", "Processing","On hold","Completed","Cancelled","Refunded","Failed","Draft"]);
             if(!empty($wc_custom_order_status)){

             foreach ( $wc_custom_order_status as $key => $state_name ) {
                $width = 0;
                ?>
            <span class="gs-woo-cards1" <?php echo ($width == "1") ? "style='width:20%'" : "" ?>>
                <span class="woo-pointer">
                    <?php echo $state_name; ?>
                    <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                  
                </span>
            </span>
            <?php
             }
            
            }
            else{
             ?>
               <h4 style="margin-left: 40%;">
                <?php echo esc_html( __( "No Custom Orders found in your WooCommerce Store", 'wc-gsheetconnector' ) ); ?>
             </h4>
             <?php 
            }
            ?>
        </div>
    </div>

    <br class="clear">

    <div class="gs-woo-google-set" >
        <a class="gs-woo-list-set" data-id="13" href="#0">
            <p class="maxi_mize maxi_mize13"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
            <p class="mini_mize mini_mize13"><i class="fa fa-minus" aria-hidden="true"></i></p>
            <h2>
                <span
                    class="title1"><?php echo esc_html( __( ' Other Sheet Tabs to Enable ', 'wc-gsheetconnector' ) ); ?>
                </span>
                <span class="pro-ver"><?php echo esc_html( __( 'PRO', 'wc-gsheetconnector' ) ); ?></span>
                
            </h2>
        </a>
        <hr>
        </br>
        <!-- Other Sheet Tabs to Enable -->
        <div class="gs-woo-list-set13">
         
         <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    <?php echo esc_html( __( 'All Products', 'wc-gsheetconnector' ) ); ?> <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                     <?php echo esc_html( __( 'All Products Variation', 'wc-gsheetconnector' ) ); ?><label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    <?php echo esc_html( __( 'All Customers', 'wc-gsheetconnector' ) ); ?> <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                     <?php echo esc_html( __( 'All Coupons', 'wc-gsheetconnector' ) ); ?> <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
             <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                   <?php echo esc_html( __( 'All Subscriptions', 'wc-gsheetconnector' ) ); ?>  <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
             <?php } ?>
              
        </div>
 </div>

    <br class="clear">
        <!-- product category filter start-->
        <div  class="gs-woo-google-set">
                <a class="gs-woo-list-set" data-id="7" href="#0">
                    <p class="maxi_mize maxi_mize7"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
                    <p class="mini_mize mini_mize7"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span
                            class="title1"><?php echo esc_html( __( 'Product Category Filter:', 'wc-gsheetconnector' ) ); ?>
                        </span>
                        <span class="pro-ver"><?php _e('PRO', 'wc-gsheetconnector'); ?></span>
                       
                    </h2>
                </a>
                <hr>
                </br>
                <?php 
                // get all product categories
                $product_categories = get_terms('product_cat', array(
                      'orderby' => 'name',
                      'order'   => 'ASC',
                      'hide_empty' => false
                      ));


               if (!empty($product_categories)) {
                ?>
               <div class="gs-woo-list-set7">
                <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    <?php echo esc_html( __( 'Select All Category', 'wc-gsheetconnector' ) ); ?>
                      <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>


                  
                </span>
            </div>
            <br class="clear">
                 <?php 
                     foreach ( $product_categories as $key => $category ) {
                      ?>
                         <div class="gs-woo-cards1">
                <span class="woo-pointer">
                     <?php echo __($category->name, 'wc-gsheetconnector' ); ?>
                      <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
               </span>
            </div>
                  
                    <?php } 

                    ?>
                </div>
            <?php } ?>
        </div>
       <br class="clear">
<!-- order category filter start-->
     <div  class="gs-woo-google-set">
                <a class="gs-woo-list-set" data-id="8" href="#0">
                    <p class="maxi_mize maxi_mize8"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
                    <p class="mini_mize mini_mize8"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span
                            class="title1"><?php echo esc_html( __( 'Order Category Filter:', 'wc-gsheetconnector' ) ); ?>
                        </span>
                        <span class="pro-ver"><?php echo esc_html( __( 'PRO', 'wc-gsheetconnector' ) ); ?></span>
                       
                    </h2>
                </a>
                <hr>
                </br>
                <?php 
                // get all product categories
                $order_categories = get_terms('product_cat', array(
                      'orderby' => 'name',
                      'order'   => 'ASC',
                      'hide_empty' => false
                      ));


               if (!empty($order_categories)) {
                ?>
               <div class="gs-woo-list-set8">
                <div class="gs-woo-cards1">
                <span class="woo-pointer">
                   <?php echo esc_html( __( 'Select All Category', 'wc-gsheetconnector' ) ); ?> 
                      <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>


                  
                </span>
            </div>
            <br class="clear">
                 <?php 
                     foreach ( $order_categories as $key => $category ) {
                      ?>
                         <div class="gs-woo-cards1">
                <span class="woo-pointer">
                     <?php echo __($category->name, 'wc-gsheetconnector' ); ?>
                     <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
               </span>
            </div>
                  
                    <?php } 

                    ?>
                </div>
            <?php } ?>
        </div>
    <br class="clear">
 <!-- order category filter end-->
        <div id="gform_setting_gsheet_field_maps" class="gform-settings-field gform-settings-field__map_form_fields"
            titlea="Upgrade to Pro">
           
            <div class="gs-woo-google-set" >

                <a class="gs-woo-list-set" data-id="3" href="#0">
                    <p class="maxi_mize maxi_mize3"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
                    <p class="mini_mize mini_mize3"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span
                            class="title1"><?php echo esc_html( __( 'Google Sheet Headers (Column Name) ', 'wc-gsheetconnector' ) ); ?>
                        </span>
                        <span class="pro-ver"><?php echo esc_html( __( 'PRO', 'wc-gsheetconnector' ) ); ?></span>
                      </h2>
                </a>
               <hr>
                <div class="woo-header-wrapper gs-woo-list-set3">

                    <div class="tabs-gs-back">
                        <div class="tabs-gs">

                            <a class="gs-woo-list-set active-t-gs" data-id="31" href="#0"><?php echo esc_html( __( 'Orders Header', 'wc-gsheetconnector' ) ); ?></a>
                            <a class="gs-woo-list-set" data-id="32" href="#0"><?php echo esc_html( __( 'Products Header', 'wc-gsheetconnector' ) ); ?></a>
                            <a class="gs-woo-list-set" data-id="34" href="#0"><?php echo esc_html( __( 'Product Variation Header', 'wc-gsheetconnector' ) ); ?></a>
                            <a class="gs-woo-list-set" data-id="33" href="#0"><?php echo esc_html( __( 'Customers Header', 'wc-gsheetconnector' ) ); ?></a>
                            <a class="gs-woo-list-set" data-id="35" href="#0"><?php echo esc_html( __( 'Coupons Header', 'wc-gsheetconnector' ) ); ?></a>
                            <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
                            <a class="gs-woo-list-set" data-id="36" href="#0"><?php echo esc_html( __( 'Subscriptions Header', 'wc-gsheetconnector' ) ); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <br class="clear">
                    <div class="woo-header-wrapper gs-woo-list-set31" id="extra-field">
                        <div class="checkallmaindiv">
                            <div class="extra-all-main">
                                <table class="table table-light adding_extra_table">
                                    <tbody>
                                        <tr>
                                            <td><label class="check-all-lbl"><?php echo esc_html( __( 'Extra Header Related To Order', 'wc-gsheetconnector' ) ); ?></label></td>
                                            <td>
                                                <select class="adding_extra_order_row adding_extra_css"
                                                    id="adding_extra_order_row">
                                                    <option value=""><?php echo esc_html('--Select--','wc-gsheetconnector'); ?></option>
                                                    <?php if(!empty($adding_extra_order_row)){
                                                        foreach ($adding_extra_order_row as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled>
                                                       <?php echo esc_html($value,'wc-gsheetconnector'); ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled><?php echo esc_html( __( 'Label', 'wc-gsheetconnector' ) ); ?></label></td>
                                            <td>
                                                <input type="text" name="ext_row_label_order" id="ext_row_label_order"
                                                    class="ext_row_label_order" disabled />
                                            </td>
                                            <td><button type="button" id="btn_extra_order_row"
                                                    class="btn_extra_order_row tooltip11">
                                                   <?php echo esc_html( __( ' Add New Extra Fields', 'wc-gsheetconnector' ) ); ?>
                                                    <span class="tooltiptext11"><?php echo esc_html( __( ' Upgrade To Pro', 'wc-gsheetconnector' ) ); ?></span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-extra-order-row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label class="check-all-lbl"><?php echo esc_html( __( "Extra Header Related To Order's Product", 'wc-gsheetconnector' ) ); ?></label>
                                            </td>
                                            <td>
                                                <select class="adding_extra_product_item_row adding_extra_css"
                                                    id="adding_extra_product_item_row">
                                                    <option value=""><?php echo esc_html('--Select--','wc-gsheetconnector'); ?></option>
                                                    <?php if(!empty($adding_extra_product_item_row)){
                                                        foreach ($adding_extra_product_item_row as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled
                                                        >
                                                       <?php echo esc_html($value,'wc-gsheetconnector'); ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled> <?php echo esc_html( __( ' Label', 'wc-gsheetconnector' ) ); ?></label></td>
                                            <td><input type="text" name="ext_row_label_order_item_row"
                                                    id="ext_row_label_order_item_row" class="ext_row_label_order_item_row"
                                                    disabled />
                                            </td>
                                            <td><button type="button" id="btn_extra_order_item_row"
                                                    class="btn_extra_order_item_row tooltip11">
                                                    <?php echo esc_html( __( ' Add New Extra Fields', 'wc-gsheetconnector' ) ); ?>
                                                    <span class="tooltiptext11"><?php echo esc_html( __( ' Upgrade To Pro', 'wc-gsheetconnector' ) ); ?></span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-extra-order-item-row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>


                                         <tr>
                                            <td><label class="check-all-lbl"><?php echo esc_html( __( ' Custom Static Headers', 'wc-gsheetconnector' ) ); ?></label>
                                            </td>
                                            <td>
                                                <?php 
                                         $adding_custom_static_headers = array('ip_address' => 'IP Address','site_name'=> 'Site Name','site_url'=> 'Site URL','site_admin_email'=>'Site Admin Email','site_description'=>'Site Description','user_agent'=> 'User Agent','user_name'=> 'User Name','user_login'=>'User Login','user_email'=>'User Email');

                                        ?>
                                                <select class="adding_custom_static_headers adding_extra_css"
                                                    id="adding_custom_static_headers">
                                                    <option value=""><?php echo esc_html('--Select--','wc-gsheetconnector'); ?></option>
                                                    <?php if(!empty($adding_custom_static_headers)){
                                                        foreach ($adding_custom_static_headers as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled
                                                      >
                                                        <?php echo esc_html($value,'wc-gsheetconnector'); ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled><?php echo esc_html( __( ' Label', 'wc-gsheetconnector' ) ); ?></label></td>
                                            <td><input type="text" name="ext_row_custom_static_headers"
                                                    id="ext_row_custom_static_headers" class="ext_row_custom_static_headers"
                                                    disabled />
                                            </td>
                                            <td><button type="button" id="btn_custom_static_headers"
                                                    class="btn_custom_static_headers tooltip11">
                                              <?php echo esc_html( __( ' Add New Custom Static Headers', 'wc-gsheetconnector' ) ); ?>
                                                    <span class="tooltiptext11"><?php echo esc_html( __( ' Upgrade To Pro', 'wc-gsheetconnector' ) ); ?></span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-custom-static-headers">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>

                                    <tr>
                                            <td><label class="check-all-lbl"><?php echo esc_html( __( 'Custom Static Blank Headers', 'wc-gsheetconnector' ) ); ?></label>
                                            </td>
                                            <td>
                                                <?php 
                                         $adding_custom_static_blank_headers = array('blank1' => 'Blank1','blank2'=> 'Blank2','blank3'=> 'Blank3','blank4'=>'Blank4','blank5'=>'Blank5','blank6'=> 'Blank6','blank7'=> 'Blank7','blank8'=>'Blank8','blank9'=>'Blank9','blank10'=>'Blank10');

                                        ?>
                                                <select class="adding_custom_static_blank_headers adding_extra_css"
                                                    id="adding_custom_static_blank_headers">
                                                    <option value=""><?php echo esc_html('--Select--','wc-gsheetconnector'); ?></option>
                                                    <?php if(!empty($adding_custom_static_blank_headers)){
                                                        foreach ($adding_custom_static_blank_headers as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled
                                                      >
                                                         <?php echo esc_html($value,'wc-gsheetconnector'); ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled><?php echo esc_html( __( 'Label', 'wc-gsheetconnector' ) ); ?></label></td>
                                            <td><input type="text" name="ext_row_custom_blank_headers"
                                                    id="ext_row_custom_blank_headers" class="ext_row_custom_blank_headers"
                                                    disabled />
                                            </td>
                                            <td><button type="button" id="btn_custom_blank_headers"
                                                    class="btn_custom_blank_headers tooltip11">
                                                   <?php echo esc_html( __( 'Add New Custom Static Blank Headers', 'wc-gsheetconnector' ) ); ?> 
                                                    <span class="tooltiptext11"><?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?></span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-custom-blank-headers">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="checked-all-div">
                                <label class="check-all-lbl"><?php echo esc_html( __( 'Check All', 'wc-gsheetconnector' ) ); ?> </label>
                                <span class="tooltip11"><span
                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>

                                <!-- Toggle button -->
                                    <label class="button-woo-toggle1 sheet_headers-order button-tog-inactive"
                                        id="button-woo-toggle1-click" data-id="sheet_headers-"
                                        style="float: left;margin-top: 5px;"></label>
                                </div>
                        </div>
                        <ul class="woo-header">
                            <ul>
                                <?php 
    						$header_list = $woo_service->sheet_headers;
    						foreach( $header_list as $header => $data ) { ?>
                                <li class="li-woo-header1">
                                    <i class="fa fa-sort sort-icon1"></i>
                                    <div class="switch-label1">
                                        <label>
                                            <span class='label1'>
                                                <div class='label_text1'>
                                                    <?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                                <div class="edit_col_name1"><span class="tooltip11"><span
                                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                            class="fa fa-pencil"></i></div>
                                        </label>
                                    </div>


                                    <div class="toggle-buttom-pos">
                                        <span class="tooltip11"><span
                                                class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                            <label for="<?php echo $header ?>-one"
                                                class="button-woo-toggle1 button-tog-active product_headers-lbl"
                                                id="button-woo-toggle1-click"></label>
                                    </div>

                                    </span>

                                </li>
                                <?php } ?>

                                <?php 
    						$header_list_pro = $woo_service->sheet_headers_pro;
    						foreach( $header_list_pro as $header  ) { ?>
                                <li class="li-woo-header1">
                                    <i class="fa fa-sort sort-icon1"></i>
                                    <div class="switch-label1">
                                        <label>
                                            <span class='label1'>
                                                <div class='label_text1'>
                                                    <?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                                <div class="edit_col_name1"><span class="tooltip11"><span
                                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                            class="fa fa-pencil"></i></div>
                                        </label>
                                    </div>


                                    <div class="toggle-buttom-pos">
                                        <span class="tooltip11"><span
                                                class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                            <label for="<?php echo $header ?>-one"
                                                class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                                id="button-woo-toggle1-click"></label>
                                    </div>

                                    </span>

                                </li>
                                <?php } ?>
                                <!-- Toggle button -->
                                <div class="remove_col_name">
                                </div>
                                </li>
                                <!-- order header -->
                                <li class="li-woo-header li-woo-header-order remove-currency ui-sortable-handle">
                                    <i class="fa fa-sort sort-icon"></i>


                                </li>
                            </ul>
                    </div>
                    <!-- 32 product headers -->
                    <div class="woo-header-wrapper gs-woo-list-set32">
                        <div class="checkallmaindiv">
                            <div class="extra-all-main">
                                <table class="table table-light adding_extra_table">
                                    <tbody>
                                        <tr>
                                            <td><label class="check-all-lbl"><?php echo esc_html( __( 'Additional Headers for Products', 'wc-gsheetconnector' ) ); ?></label></td>
                                            <td>
                                                <select class="adding_extra_order_row adding_extra_css"
                                                    id="adding_extra_order_row">
                                                    <option value=""><?php echo esc_html('--Select--','wc-gsheetconnector'); ?></option>
                                                    <?php if(!empty($adding_extra_product_row)){
                                                        foreach ($adding_extra_product_row as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled>
                                                       <?php echo esc_html($value,'wc-gsheetconnector'); ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled><?php echo esc_html( __( 'Label', 'wc-gsheetconnector' ) ); ?></label></td>
                                            <td>
                                                <input type="text" name="ext_row_label_order" id="ext_row_label_order"
                                                    class="ext_row_label_order" disabled />
                                            </td>
                                            <td><button type="button" id="btn_extra_order_row"
                                                    class="btn_extra_order_row tooltip11">
                                                   <?php echo esc_html( __( ' Add New Extra Fields', 'wc-gsheetconnector' ) ); ?>
                                                    <span class="tooltiptext11"><?php echo esc_html( __( ' Upgrade To Pro', 'wc-gsheetconnector' ) ); ?></span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-extra-order-row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>
                                      


                                         

                                   

                                    </tbody>
                                </table>
                            </div>
                            <div class="checked-all-div">
                                <label class="check-all-lbl"><?php echo esc_html( __( 'Check All', 'wc-gsheetconnector' ) ); ?></label>
                                <input type="radio" id="product_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="product_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 product_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="product_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <?php 
    						$header_list_pro2 = $woo_service->product_headers_pro;
    						foreach( $header_list_pro2 as $header  ) { ?>
                           <li class="li-woo-header1">
                            <i class="fa fa-sort sort-icon1"></i>
                            <div class="switch-label1">
                                <label>
                                    <span class='label1'>
                                        <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                        <div class="edit_col_name1"><span class="tooltip11"><span
                                                    class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                    class="fa fa-pencil"></i></div>
                                </label>
                            </div>


                            <div class="toggle-buttom-pos">
                                <span class="tooltip11"><span
                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                    <label for="<?php echo $header ?>-one"
                                        class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                        id="button-woo-toggle1-click"></label>
                            </div>

                            </span>

                        </li>
                        <?php } ?>

                        <!-- Toggle button -->
                        <div class="toggle-buttom-pos">
                            <label class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                id="button-woo-toggle1-click" data-id="prod_external_link-"></label>
                        </div>
                        <!-- Toggle button -->


                        <input type="radio" id="prod_external_link-one" name="product_headers[prod_external_link]" value="1"
                            class="header_name_1 product_headers-one radio-btn-hide">

                        <input type="radio" id="prod_external_link-two" name="product_headers[prod_external_link]" value="0"
                            checked="" class="header_name_0 product_headers-two radio-btn-hide">

                        </li>
                        </ul>
                    </div>
                    <!-- 33 ahmed -->
                    <div class="woo-header-wrapper gs-woo-list-set33" >
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl"><?php echo esc_html( __( 'Check All', 'wc-gsheetconnector' ) ); ?></label>
                                <input type="radio" id="customer_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="customer_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 customer_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="customer_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
    						$header_list_pro3 = $woo_service->customer_headers_pro;
    						foreach( $header_list_pro3 as $header  ) { ?>
                           

                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- 34 ahmed -->
                    <div class="woo-header-wrapper gs-woo-list-set34" >
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl"><?php echo esc_html( __( 'Check All', 'wc-gsheetconnector' ) ); ?></label>
                                <input type="radio" id="customer_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="customer_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 customer_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="customer_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
                            $header_list_pro4 = $woo_service->product_variations_headers_pro;
                            foreach( $header_list_pro4 as $header  ) { ?>
                           

                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                   <!-- coupons header -->

                    <div class="woo-header-wrapper gs-woo-list-set35" >
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl"><?php echo esc_html( __( 'Check All', 'wc-gsheetconnector' ) ); ?></label>
                                <input type="radio" id="coupon_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="coupon_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 coupon_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="coupon_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
                            $header_list_pro5 = $woo_service->coupons_headers_pro;
                            foreach( $header_list_pro5 as $header  ) { ?>
                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive coupon_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                   <!-- subscriptions header -->
            <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
                    <div class="woo-header-wrapper gs-woo-list-set36" >
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl"><?php echo esc_html( __( 'Check All', 'wc-gsheetconnector' ) ); ?></label>
                                <input type="radio" id="subscription_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="subscription_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 subscription_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="subscription_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
                            $header_list_pro6 = $woo_service->subscriptions_headers_pro;
                            foreach( $header_list_pro6 as $header  ) { ?>
                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive subscription_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>

            

            <!-- sorting -->
             <div class="gs-woo-header-set">
                <a class="gs-woo-list-set" data-id="6" href="#0">
                    <p class="maxi_mize maxi_mize6"><i class="fa fa-plus" aria-hidden="true"></i></p>
                    <p class="mini_mize mini_mize6"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span class="title1"><?php echo esc_html( __( "WooCommerce orders row's management", 'wc-gsheetconnector' ) ); ?></span>
                        <span class="pro-ver"><?php echo esc_html( __( "PRO", 'wc-gsheetconnector' ) ); ?></span>
                   </h2>
                </a>
                <hr>
             
                <div class="gs-woo-list-set6">

                    <div class="gs-woo-op-wise">
                        <label style="font-weight: bold;"><?php echo esc_html( __( "Manage row's by :", 'wc-gsheetconnector' ) ); ?> </label>

                        <span class="woo-pointer">
                            <input type="radio" name="order_wise_product_wise" value="productwise" id="product_wise">
                            <label><?php echo esc_html( __( "Product Wise :", 'wc-gsheetconnector' ) ); ?></label>

                            <input type="radio" name="order_wise_product_wise" value="orderwise" id="order_wise" checked="">

                            <label><?php echo esc_html( __( "Order Wise :", 'wc-gsheetconnector' ) ); ?></label>

                        </span>
                    </div>
                    <div class="note_orderwise">
                        <p class="notes"><?php echo esc_html( __( "Notes :", 'wc-gsheetconnector' ) ); ?></p>
                        <div class="message">
                            <p>
                                <i><?php echo esc_html( __( "Order-Wise:", 'wc-gsheetconnector' ) ); ?></i>
                                <?php echo esc_html( __( "Single Entry will be saved in Google Sheet!", 'wc-gsheetconnector' ) ); ?>
                            </p>
                            <p>
                                <i><?php echo esc_html( __( "Product Wise:", 'wc-gsheetconnector' ) ); ?></i>
                                <?php echo esc_html( __( "Each Entry will be shown product wise with same Order ID, if multiple products are there
                                in
                                order", 'wc-gsheetconnector' ) ); ?>
                            </p>
                        </div>
                    </div>
                    <br>
                    <div class="gs-woo-op-wise">
                        <label style="font-weight: bold;"> <?php echo esc_html( __( "Sorting :", 'wc-gsheetconnector' ) ); ?></label>

                        <span class="woo-pointer">
                            <input type="radio" name="asc_desc_sorting" value="ASC" id="asc_sorting" checked="">
                            <label><?php echo esc_html( __( "Ascending :", 'wc-gsheetconnector' ) ); ?></label>

                            <input type="radio" name="asc_desc_sorting" value="DESC" id="desc_sorting">

                            <label><?php echo esc_html( __( "Descending :", 'wc-gsheetconnector' ) ); ?></label>

                        </span>
                    </div>

                </div>
            </div>
           

            
            
            <!-- color -->
            <div class="gs-woo-google-set" >
                <a class="gs-woo-list-set" data-id="4" href="#0">
                    <p class="maxi_mize maxi_mize4"><i class="fa fa-plus" aria-hidden="true"></i></p>
                    <p class="mini_mize mini_mize4"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span class="title1"><?php echo esc_html( __( "Google Sheet Settings.", 'wc-gsheetconnector' ) ); ?></span>
                        <span class="pro-ver"><?php echo esc_html( __( "PRO", 'wc-gsheetconnector' ) ); ?></span>
                      
                    </h2>
                </a>
                <hr>
                <br class="clear">
                <div class="gs-woo-list-set4">
                    <div class="freez_order_sort">
                        <div class="">
                            <label style="font-weight: bold;"><?php echo esc_html( __( "Freeze Header :", 'wc-gsheetconnector' ) ); ?></label>
                            <span class="woo-pointer">
                                <input type="checkbox" name="freeze_header" value="true" class="check-toggle"
                                    id="freeze_header" style="display: none;">

                                <label for="freeze_header" class="button-woo-toggle"></label>
                            </span>
                     
                    <label style="font-weight: bold;"><?php echo esc_html( __( "Background Color :", 'wc-gsheetconnector' ) ); ?> </label><br>
                    <div class="gs-woo-cards">
                        <label><?php echo esc_html( __( "Header Row :", 'wc-gsheetconnector' ) ); ?> </label>
                        <span class="woo-pointer">
                            <input type="color" name="gs_woo_header_color" value="#ffffff">
                        </span>
                    </div>
                    <div class="gs-woo-cards">
                        <label><?php echo esc_html( __( "Odd Rows :", 'wc-gsheetconnector' ) ); ?></label>
                        <span class="woo-pointer">
                            <input type="color" name="gs_woo_odd_color" value="#ffffff">
                        </span>
                    </div>
                    <div class="gs-woo-cards">
                    	<label><?php echo esc_html( __( "Even Rows :", 'wc-gsheetconnector' ) ); ?></label>
                        <span class="woo-pointer">
                            <input type="color" name="gs_woo_even_color" value="#ffffff">
                        </span>
                    </div>
                    </div>
                        </div>
                 </div>
            </div>

            
            <div class="gs-woo-google-syc-set1">
                <a class="gs-woo-list-set" data-id="5" href="#0">
                    <p class="maxi_mize maxi_mize5"><i class="fa fa-plus" aria-hidden="true"></i></p>
                    <p class="mini_mize mini_mize5"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span class="title1"><?php echo esc_html( __( "Google Sheet Sync", 'wc-gsheetconnector' ) ); ?></span>
                        <span class="pro-ver"><?php echo esc_html( __( "PRO", 'wc-gsheetconnector' ) ); ?></span>
                     </h2>
                </a>
                <hr>
                <br class="clear">
                
                <div class="gs-woo-list-set5"  class="popup-click"  >
                    <div class=" sync-card">
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele"><?php echo esc_html( __( "Sync Orders", 'wc-gsheetconnector' ) ); ?> </label>
                                <select name="asc_desc_order" id="asc_desc_order" class="design-syn-ele">
                                    <option value="ASC">
                                        <?php echo esc_html( __( "Ascending", 'wc-gsheetconnector' ) ); ?></option>
                                    <option value="DESC">
                                       <?php echo esc_html( __( "Descending", 'wc-gsheetconnector' ) ); ?> </option>
                                </select>

                                <label class="design-syn-ele"><?php echo esc_html( __( "From Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_fromdate" id="sync_all_fromdate" class="design-syn-ele">
                                <label class="design-syn-ele"><?php echo esc_html( __( "To Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_todate" id="sync_all_todate" class="design-syn-ele">
                                <label class="design-syn-ele"><?php echo esc_html( __( "Sync Orders", 'wc-gsheetconnector' ) ); ?> </label>
                                <select name="asc_desc_order" id="asc_desc_order" class="design-syn-ele">
                                    <option value="ASC">
                                        <?php echo esc_html( __( "All", 'wc-gsheetconnector' ) ); ?></option>
                                </select>
                                <button type="button" class="button button_primary sync-orders sync-btn design-syn-ele"
                                    data-type="all"><?php echo esc_html( __( "Sync Orders", 'wc-gsheetconnector' ) ); ?> <img class="sync-loader-orders"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>

                            </span>
                            <span id="synctext"></span>
                            <span class="sync-message-orders sync-message" style="display:block"></span>
                        </div>

                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele"><?php echo esc_html( __( "Sync Products", 'wc-gsheetconnector' ) ); ?></label>
                                <select name="asc_desc_pro" id="asc_desc_pro" class="design-syn-ele">
                                    <option value="ASC" selected=""><?php echo esc_html( __( "Ascending", 'wc-gsheetconnector' ) ); ?></option>
                                    <option value="DESC"><?php echo esc_html( __( "Descending", 'wc-gsheetconnector' ) ); ?></option>
                                </select>
                                <label class="design-syn-ele"><?php echo esc_html( __( "From Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_fromdate_pro" id="sync_all_fromdate_pro"
                                    class="design-syn-ele">
                                <label class="design-syn-ele"><?php echo esc_html( __( "To Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_todate_pro" id="sync_all_todate_pro"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-products sync-btn design-syn-ele"
                                    data-type="wc-products"><?php echo esc_html( __( "Sync Products", 'wc-gsheetconnector' ) ); ?> <img class="sync-loader-products"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span id="synctext-product"></span>
                            <span class="sync-message-products sync-message" style="display:block"></span>
                        </div>
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele"><?php echo esc_html( __( "Sync Products Variation", 'wc-gsheetconnector' ) ); ?></label>
                                <select name="asc_desc_cus" id="asc_desc_cus" class="design-syn-ele">
                                    <option value="ASC" selected=""><?php echo esc_html( __( "Ascending", 'wc-gsheetconnector' ) ); ?></option>
                                    <option value="DESC"><?php echo esc_html( __( "Descending", 'wc-gsheetconnector' ) ); ?></option>
                                </select>
                                <label class="design-syn-ele"><?php echo esc_html( __( "From Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_fromdate_cus" id="sync_all_fromdate_cus"
                                    class="design-syn-ele">
                                <label class="design-syn-ele"><?php echo esc_html( __( "To Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_todate_cus" id="sync_all_todate_cus"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-customers sync-btn design-syn-ele"
                                    data-type="wc-customers"><?php echo esc_html( __( "Sync Products Variation", 'wc-gsheetconnector' ) ); ?> <img class="sync-loader-customers"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-customers sync-message" style="display:block"></span>
                        </div>

                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele"><?php echo esc_html( __( "Sync Customers", 'wc-gsheetconnector' ) ); ?></label>
                                <select name="asc_desc_cus" id="asc_desc_cus" class="design-syn-ele">
                                    <option value="ASC" selected=""><?php echo esc_html( __( "Ascending", 'wc-gsheetconnector' ) ); ?></option>
                                    <option value="DESC"><?php echo esc_html( __( "Descending", 'wc-gsheetconnector' ) ); ?></option>
                                </select>
                                <label class="design-syn-ele"><?php echo esc_html( __( "From Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_fromdate_cus" id="sync_all_fromdate_cus"
                                    class="design-syn-ele">
                                <label class="design-syn-ele"><?php echo esc_html( __( "To Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_todate_cus" id="sync_all_todate_cus"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-customers sync-btn design-syn-ele"
                                    data-type="wc-customers"><?php echo esc_html( __( "Sync Customers", 'wc-gsheetconnector' ) ); ?> <img class="sync-loader-customers"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-customers sync-message" style="display:block"></span>
                        </div>
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele"><?php echo esc_html( __( "Sync Coupons", 'wc-gsheetconnector' ) ); ?></label>
                                <select name="asc_desc_coupons" id="asc_desc_coupons" class="design-syn-ele">
                                    <option value="ASC" selected=""><?php echo esc_html( __( "Ascending", 'wc-gsheetconnector' ) ); ?></option>
                                    <option value="DESC"><?php echo esc_html( __( "Descending", 'wc-gsheetconnector' ) ); ?></option>
                                </select>
                                <label class="design-syn-ele"><?php echo esc_html( __( "From Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_fromdate_coupons" id="sync_all_fromdate_coupons"
                                    class="design-syn-ele">
                                <label class="design-syn-ele"><?php echo esc_html( __( "To Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_todate_coupons" id="sync_all_todate_coupons"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-coupons sync-btn design-syn-ele"
                                    data-type="wc-coupons"><?php echo esc_html( __( "Sync Coupons", 'wc-gsheetconnector' ) ); ?> <img class="sync-loader-coupons"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-coupons sync-message" style="display:block"></span>
                        </div>

                     <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele"><?php echo esc_html( __( "Sync Subscriptions", 'wc-gsheetconnector' ) ); ?></label>
                                <select name="asc_desc_subscription" id="asc_desc_subscription" class="design-syn-ele">
                                    <option value="ASC" selected=""><?php echo esc_html( __( "Ascending", 'wc-gsheetconnector' ) ); ?></option>
                                    <option value="DESC"><?php echo esc_html( __( "Descending", 'wc-gsheetconnector' ) ); ?></option>
                                </select>
                                <label class="design-syn-ele"><?php echo esc_html( __( "From Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_fromdate_subscription" id="sync_all_fromdate_subscription"
                                    class="design-syn-ele">
                                <label class="design-syn-ele"><?php echo esc_html( __( "To Date :", 'wc-gsheetconnector' ) ); ?></label>
                                <input type="date" name="sync_all_todate_subscription" id="sync_all_todate_subscription"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-subscription sync-btn design-syn-ele"
                                    data-type="wc-subscription"><?php echo esc_html( __( "Sync Subscriptions", 'wc-gsheetconnector' ) ); ?> <img class="sync-loader-subscriptions"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-subscriptions sync-message" style="display:block"></span>
                        </div>
                    <?php } ?>

                    </div>
                    <div class="download-card">
                        <!-- dropdown select tab name -->
                        <div class="gs-woo-download-drop" style="display:none;">
                            <select name="gs-woo-download-tab" id="gs-woo-download-tab">

                                <option value="all_entire_sheet_tabs" style="font-weight: bold;" selected=""> </option>
                            </select>
                        </div>
                        <!-- dropdown select tab name -->
                        <div class="gs-woo-download-btn" style="padding: 5px;">
                            <span class="woo-pointer">
                                <button type="button" class="button button_primary download-orders download-btn"
                                    data-type="all" data-url="https://docs.google.com/spreadsheets/d/"
                                    data-sheet_id=""><?php echo esc_html( __( "Download Spreadsheet", 'wc-gsheetconnector' ) ); ?>
                                    <img class="download-loader"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button><span><?php echo esc_html( __( "(You can download connected Google Spreadsheet )", 'wc-gsheetconnector' ) ); ?></span>
                            </span>
                        </div>
                        <div class="gs-woo-download-msg">
                            <span class="download-message"></span>
                        </div>
                    </div>
                    <br class="clear">
                </div>
            </div>
        </div>
        </h2>
        
        </div>

       
          <input type="hidden" name="gs-woo-nonce" id="gs-woo-nonce"
            value="<?php echo wp_create_nonce('gs-woo-nonce'); ?>" />
        <input type="submit" value="<?php echo __('Submit Data', 'wc-gsheetconnector' ); ?>" id="woo-save-btn" class="woo-save-btn" name="woo-save-btn">
    </form>
<?php
}
?>

<?php include( WC_GSHEETCONNECTOR_PATH . "includes/pages/pro-popup.php" ) ;?>

<!-- popup file include here -->