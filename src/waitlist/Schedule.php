<?php
namespace calisia_waitlist\waitlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\models\Restock;
use calisia_waitlist\waitlist\exceptions\CalisiaException;
use calisia_waitlist\waitlist\factories\ProductHandlerFactory;
use calisia_waitlist\debugger\Debugger;

class Schedule{
    
    public function createSchedule($product){
/*
        $productData = $product->get_data();
        $changes = $product->get_changes();
        if($productData['stock_status'] == 'outofstock' && $changes['stock_status'] == 'instock'){
            $this->create($product);
        }
*/

        $productHandler = ProductHandlerFactory::create($product);
        if(!$productHandler->isRestocked())
            return;

        $this->create($product);  
/*
//no way to find parents in efficent way; this has to wait for woocommerce relationships table to be implemented (?)

        $parentToSchedule = $productHandler->getParentToSchedule();
        Debugger::debug('parentToSchedule');
        Debugger::debug($parentToSchedule);
        if($parentToSchedule !== NULL)
            $this->create($parentToSchedule);    
*/
        
    }

    public function create($product){
        $restock = new Restock();
        $restock->set_product_id($product->get_id());
        $restock->set_quantity($product->get_changes()['stock_quantity']);
        $restock->set_added(wp_date('Y-m-d H:i'));
        $restock->set_added_by(get_current_user_id());
        $restock->save();
    }

    public function getActiveSchedules(){
        global $wpdb;

        $results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."calisia_waitlist_restock WHERE done = '0000-00-00 00:00:00' ORDER BY id" );

        $restocks = [];
        foreach($results as $result){
            $restock = new Restock();
            $restock->fill($result);
            $restocks[] = $restock;
        }
        return $restocks;
    }
}