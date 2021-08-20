<?php
namespace calisia_waitlist\waitlist\wptables;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\wp\WpListTable;
use calisia_waitlist\waitlist\models\Waitlist;

class TopProductsTable extends WpListTable {

	private static $where_validation_array = [
		'sent'  => 	[
			0 => 'w.sent = "0000-00-00 00:00"',
			1 => 'w.sent != "0000-00-00 00:00"'
		],
		'customer'  => 	[
			0 => 'w.user_id = 0',
			1 => 'w.user_id != 0'
		],
	];



	private static $allowed_sort = ['post_title','waitlistcount'];
    /** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'waitlist', 'calisia-waitlist' ), //singular name of the listed records
			'plural'   => __( 'waitlists', 'calisia-waitlist' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	private static function where_clause(){
		$where = ' WHERE';
		$params = [];

		foreach($_GET as $key => $value){//iterate through get array
			
			if(isset(self::$where_validation_array[$key][$value])){// check if key value element exists in validation array

				$where .= ' ' . self::$where_validation_array[$key][$value] . ' AND';//add element to query

			}elseif(isset(self::$where_validation_array[$key]['value'])){//check if coresponding where validation array element has value 'value'; indicates value insertion into sql query
				
				$where .= ' ' . self::$where_validation_array[$key]['value'] . ' AND';//add element to query
				$params[] = $value;//add get value to param array

			}
			
		}

		return array('sql'=>rtrim(rtrim($where, 'AND'), 'WHERE'), 'params'=>$params);//filter both AND and WHERE in case no query was constructed
	}

	/**
	 * Retrieve rows data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_rows( $per_page = 25, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT w.id, w.product_id, COUNT(w.product_id) AS waitlistcount, p.post_title
				FROM {$wpdb->prefix}calisia_waitlist_waitlist AS w 
				INNER JOIN {$wpdb->prefix}posts AS p ON w.product_id=p.ID";

		$where_clause = self::where_clause();
		$sql .= $where_clause['sql'];
		$params = $where_clause['params'];

		$sql .= ' GROUP BY w.product_id';

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if(in_array($_REQUEST['orderby'],self::$allowed_sort)){
				$sql .= ' ORDER BY ';
				$sql .= esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}else{
				$sql .= ' ORDER BY waitlistcount DESC';
			}
		}else{
			//default order by
			$sql .= ' ORDER BY waitlistcount DESC';
		}

		$sql .= " LIMIT %d";
		$params[] = $per_page;
		$sql .= ' OFFSET %d';
		$params[] = ( $page_number - 1 ) * $per_page;

		//print_r($sql);
		//echo "<pre>";
		//print_r($params);
		//echo "</pre>";
		$result = $wpdb->get_results( 
			$wpdb->prepare(
				$sql,
				$params
			), 
			'ARRAY_A' 
		);

		return $result;
	}


	/**
	 * Delete record.
	 *
	 * @param int $id waitlist entry id
	 */
	public static function delete_waitlist_entry( $id ) {

		$entry = new Waitlist();
		$entry->set_id($id);
		$entry->delete();
		
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$where_clause = self::where_clause();
		$sql =  "SELECT COUNT(*) AS `count` FROM ( 
				SELECT w.id AS elements_count
				FROM {$wpdb->prefix}calisia_waitlist_waitlist AS w 
				INNER JOIN {$wpdb->prefix}posts AS p ON w.product_id=p.ID" . $where_clause['sql'] . ' GROUP BY w.product_id) tmp';

		

		if(empty($where_clause['params'])){

			echo "record count:<br> " . $sql;
			return $wpdb->get_var( $sql );
		}else{
			$result = $wpdb->get_results( 
				$wpdb->prepare(
					$sql,
					$where_clause['params']
				)
			);
			return $result[0]->elements_count;
		}
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No elements avaliable.', 'calisia-waitlist' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			//case 'product_id':
			case 'post_title':
				return '<a href="'.menu_page_url( 'waitlist', false ).'&ID='.$item['product_id'].'">'.$item[ $column_name ].'</a> <a href="'.get_permalink($item['product_id']).'"><span class="dashicons dashicons-search"></span></a>';
			case 'waitlistcount':
				return $item[ $column_name ];
			default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_waitlist_entry' );

		$title = '<strong>' . $item['title'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			//'cb'      => '<input type="checkbox" />',
			//'product_id'    => __( 'Product Id', 'calisia-waitlist' ),
			'post_title'    => __( 'Product Name', 'calisia-waitlist' ),
			'waitlistcount'    => __( 'Count', 'calisia-waitlist' ),
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = [
		//	'product_id' => ['product_id', true],
			'post_title' => ['post_title', true],
			'waitlistcount' => ['waitlistcount', true],
		];

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			//'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		//$this->_column_headers = $this->get_column_info();
        $this->_column_headers = [
            $this->get_columns(),
            [], // hidden columns
            $this->get_sortable_columns(),
            $this->get_primary_column_name(),
        ];

		/** Process bulk action */
		//$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'tickets_per_page', 25 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_rows( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_waitlist_entry' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_waitlist_entry( absint( $_GET['customer'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_waitlist_entry( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}

	//function display_tablenav($which){}
}