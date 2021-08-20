<?php
namespace calisia_waitlist\waitlist\products;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;
use calisia_waitlist\waitlist\products\ProductHandler;
use calisia_waitlist\waitlist\products\interfaces\IProductHandler;
use calisia_waitlist\waitlist\factories\SubscriberFactory;
use calisia_waitlist\waitlist\elements\SubscribeForm;

class GroupedHandler extends ProductHandler implements IProductHandler{
    protected $product;
    private $children;

    function __construct($product){
        $this->product = $product;
        $this->children = $this->product->get_children();
    }

    public function renderSubscribeForm(IRenderer $renderer){
        if(!$this->childIsOutOfStock())
            return;

        $form = new SubscribeForm(SubscriberFactory::createWithAutoSetEmail($this->product->get_id())->isSubscribed(),$this->product->get_id());
        $form->render($renderer);
    }

    private function childIsOutOfStock(){
        $productFactory = new \WC_Product_Factory(); 

        foreach($this->children as $childProductId){
            $childProduct = $productFactory->get_product($childProductId);
            if(!$childProduct->is_in_stock())
                return true;
        }

        return false;
    }


    //no way to find parents in efficent way :(
    //public function getParentToSchedule(){
    //  return NULL;
    //}
}