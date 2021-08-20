<?php
namespace calisia_waitlist\waitlist\factories;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\products\SimpleHandler;
use calisia_waitlist\waitlist\products\VariableHandler;
use calisia_waitlist\waitlist\products\VariantHandler;
use calisia_waitlist\waitlist\products\GroupedHandler;
//use calisia_waitlist\renderer\interfaces\IRenderer;

class ProductHandlerFactory{
    /**
     * creates producthandler instance
     * inputs
     * product: product_id/product object
     * renderer: renderer object that implements IRenderer interface
     * 
     * returns ProductHandler object
     */
    public static function create($product/*, IRenderer $renderer*//*, $forceProduct = ''*/){
/*
        if(!is_object($product)){
            $productFactory = new \WC_Product_Factory();  
            $product = $productFactory->get_product($product);
        }*/

        if($product->is_type('grouped')){
            return new GroupedHandler($product);
        }

        if($product->is_type('variable')){
            return new VariableHandler($product);
        }

        if($product->is_type('variation')){
            return new VariantHandler($product);
        }

        if($product->is_type('simple')){
            return new SimpleHandler($product);
        }

        return NULL;

    }
}