<?php
namespace calisia_waitlist\waitlist\products;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;
use calisia_waitlist\waitlist\products\ProductHandler;
use calisia_waitlist\waitlist\products\interfaces\IProductHandler;

class VariableHandler extends ProductHandler implements IProductHandler{
    protected $product;

    function __construct($product){
        $this->product = $product;
    }

    public function renderSubscribeForm(IRenderer $renderer){
        $renderer->render('elements/VariableProductForm');
    }



    //no way to find parents in efficent way :(
    //public function getParentToSchedule(){
    //  return NULL;
    //}
}