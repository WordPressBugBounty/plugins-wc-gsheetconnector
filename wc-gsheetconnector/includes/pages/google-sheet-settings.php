<?php
/*
 * Google Sheet configuration and settings page
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}

$active_tab = ( isset ( $_GET['tab'] ) && sanitize_text_field( $_GET["tab"] )) ?  sanitize_text_field( $_GET['tab'] ) : 'integration';

$active_tab_name = '';
if($active_tab ==  'integration'){
  $active_tab_name = 'Integration';
}
elseif($active_tab ==  'settings'){
  $active_tab_name = 'WooCommerce Data Settings';
}
elseif($active_tab ==  'role_settings'){
  $active_tab_name = 'Role Settings';
}
elseif($active_tab ==  'system_status'){
  $active_tab_name = 'System Status';
}
elseif($active_tab ==  'beta_version'){
  $active_tab_name = 'Beta - Version';
}
elseif($active_tab ==  'product_sheet_to_woocommerce'){
  $active_tab_name = '2 Way Sync';
}

// Check plugin version and subscription plan
$plugin_version = defined('WC_GSHEETCONNECTOR_VERSION') ? WC_GSHEETCONNECTOR_VERSION : 'N/A';

?>
<div class="gsheet-header">
			<div class="gsheet-logo">
				<a href="https://www.gsheetconnector.com/"><i></i></a></div>
			<h1 class="gsheet-logo-text"><span><?php echo esc_html( __('WC GSheetConnector', 'wc-gsheetconnector' ) ); ?></span> <small><?php echo esc_html( __('Version :', 'wc-gsheetconnector' ) ); ?> <?php echo esc_html($plugin_version,'wc-gsheetconnector'); ?> </small></h1>
			<a href="https://support.gsheetconnector.com/kb" title="gsheet Knowledge Base" target="_blank" class="button gsheet-help"><i class="dashicons dashicons-editor-help"></i></a>
		</div>
   <span class="dashboard-gsc"><?php echo esc_html( __('DASHBOARD', 'wc-gsheetconnector' ) ); ?></span>
   <span class="divider-gsc"> / </span>
   <span class="modules-gsc"> <?php echo esc_html( __($active_tab_name, 'wc-gsheetconnector' ) ); ?></span>  
<div class="wrap">
<?php
       $tabs = array(  
        'integration' => __( 'Integration', 'wc-gsheetconnector' ),
        'settings'    => __( 'WooCommerce Data Settings', 'wc-gsheetconnector'),
        'role_settings'    => __( 'Role Settings', 'wc-gsheetconnector'),
        'system_status'    => __( 'System Status', 'wc-gsheetconnector'),
        'beta_version'    => __( 'Beta - Version', 'wc-gsheetconnector'),
		    'product_sheet_to_woocommerce'    => __("Sync", 'wc-gsheetconnector'),
        );
       echo '<div id="icon-themes" class="icon32"><br></div>';
       echo '<h2 class="nav-tab-wrapper">';
       foreach( $tabs as $tab => $name ){
        // FILTER_SANITIZE_STRING
           $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';
           echo "<a class='nav-tab$class' href='?page=wc-gsheetconnector-config&tab=$tab'>".filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS)."</a>";

       }
       echo '</h2>';
   	switch ( $active_tab ){
        case 'integration' :
            include( WC_GSHEETCONNECTOR_PATH . "includes/pages/wc-gsheetconnector-integration.php" ) ;
              break;
            case 'settings' :
                include( WC_GSHEETCONNECTOR_PATH . "includes/pages/wc-gsheetconnector-setting.php" ) ;
            break;  
            case 'role_settings' :
                $role_settings = new wc_gsheetconnector_role_settings_free();
                $role_settings->add_role_setting_page_free();
            break;
            case 'beta_version' :
               include( WC_GSHEETCONNECTOR_PATH . "includes/pages/wc-beta-version.php" );
            break; 
           case 'system_status' :
                include( WC_GSHEETCONNECTOR_PATH . "includes/pages/wc-gsheetconnector-systeminfo.php" ) ;
            break;
		       case 'product_sheet_to_woocommerce' :
                include( WC_GSHEETCONNECTOR_PATH . "includes/pages/wc-product-sheet.php" ) ;
            break;            
		
	}
	?>
</div>

<?php include( WC_GSHEETCONNECTOR_PATH . "includes/pages/admin-footer.php" ) ;?>