<?php
namespace calisia_waitlist\waitlist\models;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Restock extends Model{
    protected $id;
    protected $product_id;
    protected $quantity;
    protected $added;
    protected $done = '0000-00-00 00:00:00';
    protected $added_by;

    public function set_id($id){
        $this->id = $id;
    }
    public function get_id(){
        return $this->id;
    }
    public function set_product_id($product_id){
        $this->product_id = $product_id;
    }
    public function get_product_id(){
        return $this->product_id;
    }
    public function set_quantity($quantity){
        $this->quantity = $quantity;
    }
    public function get_quantity(){
        return $this->quantity;
    }
    public function set_added($added){
        $this->added = $added;
    }
    public function get_added(){
        return $this->added;
    }
    public function set_done($done){
        $this->done = $done;
    }
    public function get_done(){
        return $this->done;
    }
    public function set_added_by($added_by){
        $this->added_by = $added_by;
    }
    public function get_added_by(){
        return $this->added_by;
    }
    
}