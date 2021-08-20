<?php
namespace calisia_waitlist\waitlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\loader\Loader;

class Scripts extends Loader{
    
    public function addProductPageJs(){
        $loaded = $this->loadJs(
            'waitlist-product-page',
            CALISIA_WAITLIST_URL . 'js/waitlist-product-page.js',
            function(){
                if ( !is_product() ){// || !is_user_logged_in()
                    return false;
                }
               // global $post;
               // $product = new \WC_Product($post->ID);
               // if( $product->is_in_stock() ) {
               //     return false;
               // }

                return true;
            }
        );
        if($loaded){
            $this->injectJsObject(
                'waitlist-product-page', 
                'ajaxObject', 
                array( 
                    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                    'joinWaitlistText' => __('Join Waitlist','calisia-waitlist'),
                    'leaveWaitlistText' => __('Leave Waitlist','calisia-waitlist'),
                    'isLoggedIn' => is_user_logged_in(),
                    'visitorMsg' => __('Thanks for signing up on the waitlist. We will inform you once the product is available for purchase.','calisia-waitlist'),
                )
            );
        }
    }
}