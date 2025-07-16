jQuery(document).ready(function () {
  
   /**
   * verify the api code
   * @since 1.0
   */
   jQuery(document).on('click', '#wcgsc-save-code', function (event) {
      event.preventDefault();
         jQuery( ".loading-sign" ).addClass( "loading" );
         var data = {
         action: 'wcgsc_verify_integration',
         code: jQuery('#wcgsc-code').val(),
         security: jQuery('#wcgsc-ajax-nonce').val()
         };
         jQuery.post(ajaxurl, data, function (response ) {
            var clear_msg = response.data;
            if( ! response.success ) { 
               jQuery( ".loading-sign" ).removeClass( "loading" );
               jQuery( "#wcgsc-validation-message" ).empty();
               jQuery("<span class='error-message'>Access code Can't be blank.</span>").appendTo('#wcgsc-validation-message');
            } else {
               jQuery( ".loading-sign" ).removeClass( "loading" );
               jQuery( "#wcgsc-validation-message" ).empty();
               jQuery("<span class='wcgsc-valid-message'>Your Google Access Code is Authorized and Saved.</span> ").appendTo('#wcgsc-validation-message');
               setTimeout(function () {
               window.location.href = jQuery("#redirect_auth").val();
           }, 1000);
           }
         });
         
   });  

  /**
    * deactivate the api code
    * @since 1.0
    */
   jQuery(document).on('click', '#wcgsc-deactivate-log', function () {
      jQuery(".loading-sign-deactive").addClass( "loading" );
    var txt;
    var r = confirm("Are You sure you want to deactivate Google Integration ?");
    if (r == true) {
       var data = {
          action: 'wcgsc_deactivate_integration',
          security: jQuery('#wcgsc-ajax-nonce').val()
       };
       jQuery.post(ajaxurl, data, function (response ) {
          if ( response == -1 ) {
             return false; // Invalid nonce
          }
        
          if( ! response.success ) {
             alert('Error while deactivation');
             jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
             jQuery( "#deactivate-msg" ).empty();
             
          } else {
             jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
             jQuery( "#deactivate-msg" ).empty();
             jQuery("<span class='wcgsc-valid-message'>Your account is removed. Reauthenticate again to integrate WooCommerce with Google Sheet.</span>").appendTo('#deactivate-msg');
             setTimeout(function () { location.reload(); }, 1000);
          }
       });
    } else {
       jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
    }
         
  }); 

  function html_decode(input) {
      var doc = new DOMParser().parseFromString(input, "text/html");
      return doc.documentElement.textContent;
   }

   jQuery(document).on('click', '#wcgsc-sync', function () {
      jQuery(this).parent().children(".loading-sign").addClass("loading");
      var integration = jQuery(this).data("init");
      var data = {
         action: 'wcgsc_sync_google_account',
         isajax: 'yes',
         isinit: integration,
         security: jQuery('#wcgsc-ajax-nonce').val()
      };

      jQuery.post(ajaxurl, data, function (response) {
         if (response == -1) {
            return false; // Invalid nonce
         }

         if (response.data.success == "yes") {
            jQuery(".loading-sign").removeClass("loading");
            jQuery("#wcgsc-validation-message").empty();
            jQuery("<span class='wcgsc-valid-message'>Fetched latest sheet names.</span>").appendTo('#wcgsc-validation-message');
            setTimeout(function () { location.reload(); }, 1000);
         } else {
            jQuery(this).parent().children(".loading-sign").removeClass( "loading" );
          location.reload(); // simply reload the page
         }
      });
   });

   /**
    * Clear debug
    */
   jQuery(document).on('click', '.debug-clear', function () {
      jQuery(".clear-loading-sign").addClass("loading");
      var data = {
         action: 'wcgsc_clear_logs',
         security: jQuery('#wcgsc-ajax-nonce').val()
      };
      jQuery.post(ajaxurl, data, function (response) {
         var clear_msg = response.data;
         if (response.success) {
            jQuery(".clear-loading-sign").removeClass("loading");
            jQuery("#wcgsc-validation-message").empty();
            jQuery("<span class='wcgsc-valid-message'>"+clear_msg+"</span>").appendTo('#wcgsc-validation-message');
            setTimeout(function () {
                     location.reload();
                 }, 1000);
         }
      });
   });

  jQuery(document).on('submit', '#gsSettingFormFree', function (event) {
      console.log('prevent the subitting the form');
      jQuery('#error_spread').html('');
      jQuery('#error_gsTabName').html('');
      
      var submit = true;
      var spreadsheetsName = jQuery('#wcgsc-sheet-id').val();
      var gsTabName = jQuery('input.wcgsc_order_state:checked').length;
      

      if(spreadsheetsName == ""){
         jQuery('#error_spread').html('* Please Select Spreadsheet Name !');
         submit = false;
      }
      if(gsTabName <= 0){
         jQuery('#error_gsTabName').html('* Please select atleast one Tabs !');
         submit = false;
      }
      
      if(submit == false){
         event.preventDefault();
         window.scrollTo({ top: 0, behavior: 'smooth' });
      }
   });
   jQuery(".wcgsc-list-set32").hide();
   jQuery(".wcgsc-list-set33").hide();
   jQuery(".wcgsc-list-set34").hide();
   jQuery(".wcgsc-list-set35").hide();
   jQuery(".wcgsc-list-set36").hide();
   jQuery(document).on("click", ".wcgsc-list-set", function (event){
      var $this = jQuery(this);
      var $id = $this.attr( "data-id" );
      
      
      if($id == "31" || $id == "32" || $id == "33" || $id == "34" || $id == "35" || $id == "36"){
         if(jQuery(".wcgsc-list-set"+$id).css("display") == "none") { 
           jQuery(".wcgsc-list-set31").hide();
           jQuery(".wcgsc-list-set32").hide();
           jQuery(".wcgsc-list-set33").hide();
           jQuery(".wcgsc-list-set34").hide();
           jQuery(".wcgsc-list-set").removeClass("active-t-gs");
           jQuery(this).addClass("active-t-gs");
           jQuery(".wcgsc-list-set"+$id).show();
         }else{
            if ( !($this).hasClass("active-t-gs") ) {
               jQuery(".wcgsc-list-set"+$id).hide();
            }
         }
      }else{
         if(jQuery(".wcgsc-list-set"+$id).css("display") == "none") { 
           //jQuery(".gs-woo-list-set"+$id).css("display", "block");
           jQuery(".wcgsc-list-set"+$id).show('slow');
           jQuery(".mini_mize"+$id).show();
           jQuery(".maxi_mize"+$id).hide();
         }else{
           //jQuery(".gs-woo-list-set"+$id).css("display", "none");
           jQuery(".wcgsc-list-set"+$id).hide('slow');
           jQuery(".mini_mize"+$id).hide();
           jQuery(".maxi_mize"+$id).show();
         } 
      }
   });
});


 /**
     * Display Error logs
     */
    jQuery(document).ready(function($) {
    // Hide .wcgsc-system-error-logs initially
    $('.wcgsc-system-error-logs').hide();

    // Add a variable to track the state
    var isOpen = false;

    // Function to toggle visibility and button text
    function toggleLogs() {
        if (isOpen) {
            $('.wcgsc-system-error-logs').hide(); // Hide the logs
            $('.wcgsc-logs').text('View');     // Change button text to "View"
        } else {
            $('.wcgsc-system-error-logs').show(); // Show the logs
            $('.wcgsc-logs').text('Close');    // Change button text to "Close"
        }
        isOpen = !isOpen; // Toggle the state
    }

    // Toggle visibility and button text when clicking .wcgsc-logs button
    $('.wcgsc-logs').on('click', function(e) {
        e.stopPropagation(); // Ensure only the button click triggers toggle
        toggleLogs();
    });

    // Prevent closing the logs when clicking inside the .wcgsc-system-error-logs div
    $('.wcgsc-system-error-logs').on('click', function(event) {
        event.stopPropagation(); // Prevent the click from affecting anything outside
    });

    // Optional: Close the logs when clicking outside the div, if needed
    $(document).on('click', function(event) {
        if (isOpen && !$(event.target).closest('.wcgsc-system-error-logs, .wcgsc-logs').length) {
            toggleLogs(); // Close the logs if clicked outside the button or the logs div
        }
    });
});


// Msg Hide ///
    
jQuery(document).ready(function($) {
    // Check if the message has already been hidden by looking in localStorage
    if (localStorage.getItem('googleDriveMsgHidden') === 'true') {
        jQuery('#google-drive-msg').hide(); // Hide the message if it's already hidden
    }

    // On button click, hide the #google-drive-msg div and store the hidden state in localStorage
    jQuery('.wcgsc_button').on('click', function() {
        jQuery('#google-drive-msg').hide(); // Hide the message
        localStorage.setItem('googleDriveMsgHidden', 'true'); // Save the hidden state in localStorage
    });

    // On #deactivate-log click, show the #google-drive-msg div and clear localStorage
    jQuery('#wcgsc-deactivate-log').on('click', function() {
        // jQuery('#google-drive-msg').show(); // Show the message
        localStorage.removeItem('googleDriveMsgHidden'); // Remove the hidden state from localStorage
    });
});
jQuery(document).ready(function () {
   jQuery('.wcgsc-addons-list').each(function () {
      if (jQuery(this).html().trim().length === 0) {
         jQuery(this).addClass('blank_div');
         jQuery(this).prev('h2').hide();
      }
   });
});
