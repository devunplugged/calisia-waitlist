<?php
/**
 * Plugin Name: calisia-waitlist
 * Author: Tomasz Boroń
 * Text Domain: calisia-waitlist
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define('CALISIA_WAITLIST_ROOT', __DIR__);
define('CALISIA_WAITLIST_URL', plugin_dir_url( __FILE__ ));
define('CALISIA_WAITLIST_FILE', __FILE__ );
define('CALISIA_WAITLIST_UNSUBSCRIBE_PAGE_NAME', 'Usuń Subskrypcje z Listy Oczekujących');

require CALISIA_WAITLIST_ROOT . '/vendor/autoload.php';

use calisia_waitlist\install\Install;
use calisia_waitlist\renderer\DefaultRenderer;
use calisia_waitlist\waitlist\Scripts;
use calisia_waitlist\waitlist\Cron;
use calisia_waitlist\waitlist\Menu;
use calisia_waitlist\waitlist\factories\ProductHandlerFactory;
use calisia_waitlist\waitlist\factories\SubscriberFactory;
use calisia_waitlist\waitlist\elements\SubscribeForm;
use calisia_waitlist\waitlist\elements\UnsubscribePage;
use calisia_waitlist\waitlist\elements\ProductWaitlistInfo;

$calisiaWaitlist = new CalisiaWaitlist();



class CalisiaWaitlist{
    private $db_version = '0.9';
    private $emailsPerCronTask = 10;
    private $renderer;
    private $cron;

    function __construct(){
        $this->renderer = new DefaultRenderer();
        $this->cron = new Cron($this->emailsPerCronTask);
        $this->registerFilters();
        $this->registerActions();
        new Menu($this->renderer);
    }

    private function registerFilters(){
        add_filter( 'the_content', [new UnsubscribePage($this->renderer),'content'], 999, 1 );
    }

    private function registerActions(){
        //load plugin textdomain
        add_action( 'init', [$this,'load_textdomain'] );
        //check if update is necessary
        add_action( 'plugins_loaded', [$this,'updateCheck'] );
        //add button to product page
        add_action( 'woocommerce_single_product_summary', [$this,'addWaitlistButton'], 25);
        //add scripts
        add_action( 'wp_enqueue_scripts', [$this,'enqueueJs'], 20);
        //ajax endpoints
        add_action( 'wp_ajax_calisia_waitlist_subscribe_form', [$this,'ajaxGetSubscribeForm'] ); //ajax call endpoint; variable product - get form
        add_action( 'wp_ajax_calisia_waitlist_subscribe', [$this,'ajaxSubscribe'] ); //ajax call endpoint
        add_action( 'wp_ajax_nopriv_calisia_waitlist_subscribe', [$this,'ajaxSubscribe'] ); //ajax call endpoint
        //create unsub page
        add_action( 'wp_loaded', [new UnsubscribePage($this->renderer),'create']);
        //show how many people are waiting for the product; product edit page
        add_action( 'woocommerce_product_options_stock', [$this, 'showProductWaitlistCount'] ); 
        //show how many people are waiting for the variant; product edit page - variant area
        add_action( 'woocommerce_variation_options_inventory', [$this, 'showVarianttWaitlistCount'], 9999, 3 );
    }

    

    public function updateCheck(){
        if ( get_site_option( 'calisia_waitlist_db' ) != $this->db_version ) {
            $this->install();
        }
    }

    private function install(){
        $install = new Install($this->db_version);
        $install->db_install();
    }

    public function addWaitlistButton(){
        global $product;
        $productHandler = ProductHandlerFactory::create($product);
        $productHandler->renderSubscribeForm($this->renderer);

        //debug cron
        /*
        if(isset($_GET['cron-schedule'])){
            $cron_jobs = get_option( 'cron' );
            foreach($cron_jobs as $key=>$value){
                echo wp_date('Y-m-d H:i', $key) . '<br>';
                echo "<pre>";var_dump($value);echo "</pre>";
            }
        }*/
    }


    public function enqueueJs(){
        $scripts = new Scripts();
        $scripts->addProductPageJs();
    }

    public function ajaxSubscribe(){
        $subscriber = SubscriberFactory::createWithAutoSetEmail($_POST['productId']);
        $subscriber->requestHandler();
    }

    public function ajaxGetSubscribeForm(){//used with variable products
        $productFactory = new \WC_Product_Factory();  
        $product = $productFactory->get_product($_POST['productId']);
        $productHandler = ProductHandlerFactory::create($product);
        $productHandler->renderSubscribeForm($this->renderer);
        wp_die();
    }

    public function showProductWaitlistCount(){
        global $post;
        $waitlistInfo = new ProductWaitlistInfo($post->ID);
        $waitlistInfo->render($this->renderer);
    }

    public function showVarianttWaitlistCount($loop, $variation_data, $variation){
        $waitlistInfo = new ProductWaitlistInfo($variation->ID);
        $waitlistInfo->render($this->renderer);
    }

    public function load_textdomain(){
        load_plugin_textdomain( 'calisia-waitlist', false, 'calisia-waitlist/languages' );
    }
}