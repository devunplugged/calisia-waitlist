<?php
namespace calisia_waitlist\waitlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\models\Waitlist as WaitlistModel;

class Waitlist{
    private $entries = [];

    public function getEntriesForProduct(int $productId){
        if(isset($this->entries[$productId])){
            return $this->entries[$productId];
        }
        return [];
    }

    public function setEntriesForProduct(int $productId){
        global $wpdb;
        $query = "SELECT * FROM ".$wpdb->prefix."calisia_waitlist_waitlist WHERE sent='0000-00-00 00:00:00' AND product_id = %d";
        $params = [$productId];
        $this->setEntries($query, $params);
    }

    public function setEntriesForSchedules(array $schedules){
        $this->entries = [];

        if(count($schedules) == 0){
            return;
        }
        global $wpdb;
        $productIds = [];
        foreach($schedules as $schedule){
            $productIds[] = $schedule->get_product_id();
        }

        $params = [];
        $query = "SELECT * FROM ".$wpdb->prefix."calisia_waitlist_waitlist WHERE sent='0000-00-00 00:00:00' AND (";
        foreach($productIds as $productId){
            $query .= " product_id = %d OR";
            $params[] = $productId;
        }
        $query = rtrim($query, ' OR');
        $query .= ')';
        $this->setEntries($query, $params);
       /* $results = $wpdb->get_results(
            $wpdb->prepare($query, $params)
        );

        foreach($results as $result){
            $waitlist = new WaitlistModel();
            $waitlist->fill($result);
            $this->entries[$waitlist->get_product_id()][] = $waitlist;
        }*/
    }

    private function setEntries($query, $params){
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare($query, $params)
        );

        foreach($results as $result){
            $waitlist = new WaitlistModel();
            $waitlist->fill($result);
            $this->entries[$waitlist->get_product_id()][] = $waitlist;
        }
    }

}