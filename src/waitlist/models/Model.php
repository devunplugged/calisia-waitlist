<?php
namespace calisia_waitlist\waitlist\models;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Model{
    public function fill($object){
        foreach(get_object_vars($object) as $key => $value){
            if(property_exists($this, $key))
                $this->$key = $value;
        }
    }

    public function save(){
        global $wpdb;

        $params = array();
        foreach(get_object_vars($this) as $key => $value){
            if($key == 'id') //skip id when inserting data
                continue;
            $params[$key] = $value;
        }

        $result = $wpdb->insert( 
            $this->get_table_name(), 
            $params
        );
        $this->set_id($wpdb->insert_id);
        return $result;
    }

    public function update(){

      //  if(empty($params)){
            $params = array();
            $types = array();
            foreach(get_object_vars($this) as $key => $value){
                $params[$key] = $value;
                if(is_float($value)){
                    $types[] = '%f';
                }elseif(is_int($value)){
                    $types[] = '%d';
                }else{
                    $types[] = '%s';
                }
            }
      //  }

       // if(empty($where)){
            $where = array('id' => $this->get_id());
            $where_type = array( '%d' );
        //}

        global $wpdb;
        return $wpdb->update( 
            $this->get_table_name(), 
            $params, 
            $where, 
            $types, 
            $where_type
        );
    }

    public function delete(){
        global $wpdb;
        return $wpdb->delete( 
            $this->get_table_name(), 
            ['id' => $this->get_id()], 
            ['%d']
        );
    }

    public function get_table_name(){
        global $wpdb;
        return $wpdb->prefix . 'calisia_waitlist_' . strtolower($this->get_class_name());
    }

    public function get_class_name(){
        $class_parts = explode('\\', get_class($this));
        return end($class_parts);
    }

    public function db_fill(){
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT * FROM ".$this->get_table_name()." WHERE id = %d",
            array(
                $this->get_id()
               )
            )
        );
        $this->fill($result[0]);
        
    }
}