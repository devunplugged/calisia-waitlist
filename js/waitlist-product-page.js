console.log("JS loaded!");

jQuery( document ).ready(function() {
    jQuery(document).on('click', '.waitlist-button', function(event) {
        event.preventDefault();

        //console.log('subscribe button clicked');
        jQuery('.waitlist-button').prop('disabled', true);
        let dataToPost = {
            action: "calisia_waitlist_subscribe", 
            productId: jQuery(this).data( "product-id" )
        }
        if(jQuery('#user-email').length){
            dataToPost['email'] = jQuery('#user-email').val();
        }
        console.log("dataToPost:");
        console.log(dataToPost);
        CW_AjaxCall(
            dataToPost,
            function (data){
                data = JSON.parse(data)
                jQuery('.waitlist-button').prop('disabled', false);
                if(data.error !== null){
                    alert(data.error);
                    return;
                }
                if(!ajaxObject.isLoggedIn){
                    jQuery('#waitlist-form-content').text(ajaxObject.visitorMsg);
                }else if(data.subscription == 1){
                    jQuery('.waitlist-button').text(ajaxObject.leaveWaitlistText);
                }else{
                    jQuery('.waitlist-button').text(ajaxObject.joinWaitlistText);
                }
            }
        );
    });
    jQuery( ".variations_form" ).on( "woocommerce_variation_select_change", function () {
        // Fires whenever variation selects are changed
        jQuery('#waitlist-form').hide();
    } );

    jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
        // Fired when the user selects all the required dropdowns / attributes
        // and a final variation is selected / shown
        //console.log("variation:");
        //console.log(variation);
        if(variation.is_in_stock === true){
            return;
        }
        let dataToPost = {
            action: "calisia_waitlist_subscribe_form", 
            productId: variation.variation_id
        }
        
        CW_AjaxCall(
            dataToPost,
            function (data){
                console.log(data);
                jQuery("#waitlist-form-wrapper").html(data);
            }
        );
    });
});

function CW_AjaxCall(dataObject, callback){
    jQuery.ajax({
        url: ajaxObject.ajaxUrl,
        type: 'POST',
        data: dataObject,
        success: function( data ){
            callback(data);
        }
    });
}