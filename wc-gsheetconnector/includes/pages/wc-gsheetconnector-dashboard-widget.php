<?php
/*
 * GS Woocommerce Google sheet connector Dashboard Widget
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit();
}
?>
<div class="dashboard-content">
   <?php
   $gs_woo_page_roles = get_option('gs_woo_page_roles_setting');
   $sheet_data = get_option( 'gs_woo_sheet_feeds' );
   $gs_woo_settings = get_option( 'gs_woo_settings' );
   
   $selected_sheet_key = isset($gs_woo_settings) ? $gs_woo_settings : "";
   $sheetName	=	"Google Sheet Not Connected";
	if ( ! empty( $sheet_data ) ) {
		foreach ( $sheet_data as $key => $value ) {
			if ( $selected_sheet_key !== "" && $key == $selected_sheet_key ) {
				$sheetName = $value['sheet_name'];
			}
			
		}

	}
	
   $sheet_url = "#"; // Default URL or placeholder
   if (is_string($selected_sheet_key) && !empty($selected_sheet_key)) {
       $sheet_url = "https://docs.google.com/spreadsheets/d/" . $selected_sheet_key;
   }


   ?>
   <div class="main-content">
      <div class="gs_woo_dash_widget">
         <div class="gs_woo_conn_sheet">
			 
			 <style>
			  .widget-table { border:1px solid #eee; width:100%; margin-bottom: 30px; }
			  .widget-table th { text-align: left; background: #eee; padding: 2px 3px; border-bottom: 1px solid #eee; }
			  .widget-table td { text-align: left; background: #fff; padding: 2px 3px; word-wrap: break-word; }
			  .widget-table td:nth-child(1) {width:50%;}
		  </style>
			 
			 
			 
			 
			 <table class="widget-table">
			  <tbody><tr> 
				<th><?php echo __("Sheet URL", "wc-gsheetconnector"); ?></th>
			  </tr>
			  
			   	<tr> 
				<td><a href="<?php echo $sheet_url; ?>" target="_blank"><?php echo __($sheetName, "wc-gsheetconnector"); ?></a></td>
			  </tr> 	  
		  </tbody></table>
			 <table class="widget-table">
			  <tbody><tr> 
				<th><?php echo __("Permission To Access GSheetConnector WooCommerce", "wc-gsheetconnector"); ?></th>
			  </tr>
			  
			   	<tr> 
				<td><?php
	         if ( ! empty( $gs_woo_page_roles ) ) {
	         	?>
	         	 <?php echo __("Administrator", "wc-gsheetconnector"); ?> 
	         	<?php 
	         	foreach ($gs_woo_page_roles as $key => $value) {
	         		?>
	         		 <?php echo __($value, "wc-gsheetconnector"); ?> 
	         		<?php
	         	}
	         }else { 
	         	?>
	         	 <a  href='<?php echo admin_url('admin.php?page=wc-gsheetconnector-config&tab=role_settings'); ?>'> <?php echo __("Administrator", "wc-gsheetconnector"); ?> </a>
	        <?php  }
	          ?></td>
			  </tr> 	  
		  </tbody></table>
		</div>
       </div>
   </div> <!-- main-content end -->
</div> <!-- dashboard-content end -->
<style type="text/css">
.postbox-header .hndle {
justify-content: flex-start !important;
}
</style>