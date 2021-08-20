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


class VariantHandler extends ProductHandler implements IProductHandler{
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
    //public function getParentToSchedule(){
    //  return NULL;
    //}
}