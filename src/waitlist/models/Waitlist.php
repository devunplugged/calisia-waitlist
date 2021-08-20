<?php
namespace calisia_waitlist\waitlist\models;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Waitlist extends Model{
    protected $id;
    protected $product_id;
    protected $email;
    protected $added;
    protected $sent = '0000-00-00 00:00:00';
    protected $added_by;
    protected $user_id = 0;
    protected $restock_id = 0;
    protected $secret;
    protected $unsub_code;
    protected $unsub_code_hash;

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

    public function set_email($email){
        $this->email = $email;
    }
    public function get_email(){
        return $this->email;
    }
    public function set_added($added){
        $this->added = $added;
    }
    public function get_added(){
        return $this->added;
    }
    public function set_sent($sent){
        $this->sent = $sent;
    }
    public function get_sent(){
        return $this->sent;
    }
    public function set_added_by($added_by){
        $this->added_by = $added_by;
    }
    public function get_added_by(){
        return $this->added_by;
    }
    public function set_user_id($user_id){
        $this->user_id = $user_id;
    }
    public function get_user_id(){
        return $this->user_id;
    }
    public function set_restock_id($restock_id){
        $this->restock_id = $restock_id;
    }
    public function get_restock_id(){
        return $this->restock_id;
    }

    public function set_secret($secret){
        $this->secret = $secret;
    }
    public function get_secret(){
        return $this->secret;
    }
    public function set_unsub_code($unsub_code){
        $this->unsub_code = $unsub_code;
    }
    public function get_unsub_code(){
        return $this->unsub_code;
    }
    public function set_unsub_code_hash($unsub_code_hash){
        $this->unsub_code_hash = $unsub_code_hash;
    }
    public function get_unsub_code_hash(){
        return $this->unsub_code_hash;
    }
    
}