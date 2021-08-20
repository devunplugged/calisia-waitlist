<?php
namespace calisia_waitlist\waitlist\products;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}


class ProductHandler{

    public function isRestocked(){
        $productData = $this->product->get_data();
        $changes = $this->product->get_changes();
        if($productData['stock_status'] == 'outofstock' && $changes['stock_status'] == 'instock')
            return true;

        return false;
    }

}