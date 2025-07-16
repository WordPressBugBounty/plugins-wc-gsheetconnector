<?php
$gs_woo_page_roles = get_option( 'wcgsc_tab_roles_setting' );
?>
<form id="wcgsc_role_settings_form" method="post" action="options.php">
    <?php
    settings_fields( 'wcgsc-settings' );
    settings_errors();
    ?>
    <div class="wrap gs-form">
        <div class="card" id="googlesheet">
            <div class="wrap gs-form">
                <div class="wcgsc-card">
                    <label><?php echo esc_html__( 'Roles that can access Google Sheet Page', 'wc-gsheetconnector' ); ?></label>
                    <?php
                    wc_gsheetconnector_utility::instance()->wcgsc_checkbox_roles_multi(
                        $gs_woo_page_roles . '[]',
                        $gs_woo_page_roles
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="select-info">
        <input type="submit" class="button button-primary button-large"
            name="wcgsc_settings"
            value="<?php echo esc_attr__( 'Save', 'wc-gsheetconnector' ); ?>" />
    </div>
</form>
