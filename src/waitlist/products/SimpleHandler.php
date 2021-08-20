<?php
namespace calisia_waitlist\waitlist\products;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;
use calisia_waitlist\waitlist\products\ProductHandler;
use calisia_waitlist\waitlist\products\interfaces\IProductHandler;
use calisia_waitlist\waitlist\factories\SubscriberFactory;
//use calisia_waitlist\waitlist\factories\ProductHandlerFactory;
use calisia_waitlist\waitlist\elements\SubscribeForm;
//use calisia_waitlist\debugger\Debugger;

class SimpleHandler extends ProductHandler implements IProductHandler{
    protected $product;

    function __construct($product){
        $this->product = $product;
    }

    public function renderSubscribeForm(IRenderer $renderer){
        if( $this->product->is_in_stock() ) {
            return;
        }

        $form = new SubscribeForm(SubscriberFactory::createWithAutoSetEmail($this->product->get_id())->isSubscribed(),$this->product->get_id());
        $form->render($renderer);
    }

    //no way to find parents in efficent way :(
        /*
    public function getParentToSchedule(){
        Debugger::debug('start getParentToSchedule');
        $parentId = $this->product->get_parent_id('edit');//this desnt work; no way to fix it in efficent way (?); woo relationships table doesnt exist yet
        if(!$parentId)
            return NULL;
        
        Debugger::debug('parent id:');
        Debugger::debug($parentId);

        $productFactory = new \WC_Product_Factory();  
        $parentProduct = $productFactory->get_product($parentId);
        if($parentProduct && $parentProduct->is_type('grouped')){
            $productHandler = ProductHandlerFactory::create($parentProduct);
            if(!$productHandler->childIsOutOfStock()){
                return $parentProduct;
            }else{
                return NULL;
            }
        }
    }*/
}