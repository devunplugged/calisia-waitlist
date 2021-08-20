<?php
namespace calisia_waitlist\waitlist\elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;
use calisia_waitlist\waitlist\factories\SubscriberFactory;

class UnsubscribePage{
    private $renderer;

    function __construct(IRenderer $renderer){
        $this->renderer = $renderer;
    }

	public function create(){
        if(post_exists( CALISIA_WAITLIST_UNSUBSCRIBE_PAGE_NAME,'','','page'))
            return;
    
        $my_post = array(
            'post_title'    => CALISIA_WAITLIST_UNSUBSCRIBE_PAGE_NAME,
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page'
        );
        wp_insert_post( $my_post );
    }

    public function content($content){
        if ( !is_singular() || !in_the_loop() || !is_main_query() || !is_page(CALISIA_WAITLIST_UNSUBSCRIBE_PAGE_NAME)) {
            return $content;
        }

        $content = $this->productInfo();

        if(!isset($_POST['confirm']))
            return $content . $this->confirmForm();

        if(!isset($_GET['code']) || !isset($_GET['email']) || !isset($_GET['productId'])){
            return $content . $this->renderer->render(
                'alerts/error',
                [
                    'msg' => __('ERROR. Not enough data.','calisia-waitlist')
                ],
                false
            );
        }

        $subscriber = Subscriberfactory::create($_GET['productId'],$_GET['email']);
        $subscription = $subscriber->getSubscription();
        if($subscription === false || md5($subscription[0]->get_secret() . $_GET['code']) !== $subscription[0]->get_unsub_code_hash()){
            return $content . $this->renderer->render(
                'alerts/error',
                [
                    'msg' => __('ERROR. Wrong data.','calisia-waitlist')
                ],
                false
            );
        }
        $subscriber->unsubscribe();
        return $content . $this->renderer->render(
            'alerts/success',
            [
                'msg' => __('You successfully unsubscribed from the waitlist.','calisia-waitlist')
            ],
            false
        );
    }

    public function confirmForm(){
        return $this->renderer->render(
            'elements/UnsubscribeForm',
            [],
            false
        );
    }

    public function productInfo(){
        if(!isset($_GET['productId']))
            return;

        //$product = new \WC_Product($_GET['productId']);
        $productFactory = new \WC_Product_Factory();  
        $product = $productFactory->get_product($_GET['productId']);

        if(!$product->exists())
            return;

        return $this->renderer->render(
            'elements/ProductInfo',
            [
                'product' => $product
            ],
            false
        );
        
    }
}