<?php
namespace calisia_waitlist\install;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Install{
    private $db_version;

    function __construct(string $db_version){
        $this->db_version = $db_version;
    }

    public function db_install() {
        global $wpdb;
    
        $table_name = $wpdb->prefix . 'calisia_waitlist_waitlist';
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            product_id int(11) NOT NULL,
            email varchar(128) NOT NULL,
            added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            sent datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            added_by int(11) NOT NULL,
            user_id int(11) NOT NULL,
            restock_id int(11) NOT NULL,
            secret varchar(64) NOT NULL,
            unsub_code varchar(64) NOT NULL,
            unsub_code_hash varchar(32) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );


        $table_name = $wpdb->prefix . 'calisia_waitlist_restock';
    
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            product_id int(11) NOT NULL,
            quantity int(4),
            added datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            done datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            added_by int(11) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        dbDelta( $sql );

        add_option( 'calisia_waitlist_db', $this->db_version );
    }
}