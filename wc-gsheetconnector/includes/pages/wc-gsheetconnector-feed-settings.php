 <div class="wcgsc-card">
	 
	 <h2><?php esc_html_e('Feed Settings', 'wc-gsheetconnector'); ?>  <span class="pro-ver"><?php esc_html_e('PRO', 'wc-gsheetconnector'); ?></span></h2>
	 
  <button class="woogsc-feed-btn" id="woogsc-add-feed">
    <?php echo esc_html('Add Feeds','wc-gsheetconnector'); ?>
  </button>
 
  <div class="woogsc-add-feed">
    <form method="post" id="feedForm">
      <label for="feed_name"><?php echo esc_html('Feed Name','wc-gsheetconnector'); ?></label>

      <input type="text" id="feed_name" class="feedName" name="feed_name"/>

      <select name="location" class="location" id="location">
        <option value=""><?php echo esc_html('Select Location','wc-gsheetconnector'); ?></option>
        <option value="Orders"><?php echo esc_html('Orders','wc-gsheetconnector'); ?></option>
        <option value="Products"><?php echo esc_html('Products','wc-gsheetconnector'); ?></option>
        <option value="Products Variation"><?php echo esc_html('Products Variation','wc-gsheetconnector'); ?></option>
        <option value="Customers"><?php echo esc_html('Customers','wc-gsheetconnector'); ?></option>
        <option value="Coupons"><?php echo esc_html('Coupons','wc-gsheetconnector'); ?></option>
        <option value="Subscriptions"><?php echo esc_html('Subscriptions','wc-gsheetconnector'); ?></option>
        <option value="All"><?php echo esc_html('All','wc-gsheetconnector'); ?></option>
      </select>

     
      <?php
     // Generate nonce securely
     $nonce = wp_create_nonce( 'woogsc-feed-ajax-nonce' );
      ?>

      <input type="hidden" 
       name="woogsc-feed-ajax-nonce" 
       id="woogsc-feed-ajax-nonce" 
       value="<?php echo esc_attr( $nonce ); ?>" />

      <input type="submit" 
       name="execute-submit-feed-woogsc" 
       class="woogsc-feed-sub-btn" 
       value="<?php echo esc_attr__( 'Submit', 'wc-gsheetconnector' ); ?>" 
       style="pointer-events: none;" />

      <span class="woogsc-feed-fetch-load">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </form>
  </div>
</div>
