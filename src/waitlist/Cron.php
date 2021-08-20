<?php
namespace calisia_waitlist\waitlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\Waitlist;
use calisia_waitlist\waitlist\Schedule;
use calisia_waitlist\waitlist\factories\MailerFactory;
use calisia_waitlist\waitlist\exceptions\CalisiaException;
use calisia_waitlist\debugger\Debugger;

class Cron{
    private $maxEmailsPerTask;
    private $schedule;

    function __construct(int $emailsPerTask){
        $this->maxEmailsPerTask = $emailsPerTask;
        $this->schedule = new Schedule();
        $this->registerHooks();
        $this->registerActions();
    }

    private function registerHooks(){
        //set activation actions
        register_activation_hook( __FILE__, [$this,'activateHooks'] );
        //set deactivation actions
        register_deactivation_hook( __FILE__, [$this,'deactivateHooks'] );
    }

    private function registerActions(){
        //stock changed action
        //add_action( 'woocommerce_product_set_stock', [$this,'scheduleNotifications'] );
        add_action( 'woocommerce_product_set_stock_status', [$this,'scheduleNotifications2'], 999, 3 );
        add_action( 'woocommerce_variation_set_stock_status', [$this,'scheduleNotifications2'], 999, 3 );
        //add_action( 'woocommerce_update_product_variation', [$this,'scheduleNotifications'] );
        //cron
        add_action( 'calisia_waitlist_cron_hook', [$this,'executeTask'] );
    }

    public function activateHooks(){
        if ( ! wp_next_scheduled( 'calisia_waitlist_cron_hook' ) ) {
            wp_schedule_event( time(), 'hourly', 'calisia_waitlist_cron_hook' );
        }
    }

    public function deactivateHooks(){
        wp_clear_scheduled_hook( 'calisia_waitlist_cron_hook' );
    }

    public function scheduleNotifications($product){
        $this->schedule->createSchedule($product);
    }
    public function scheduleNotifications2($id, $stockstatus, $product){
        //Debugger::debug("debugging");
        //Debugger::debug($product);
        $this->schedule->createSchedule($product);
    }
    
    public function executeTask(){
        
        $schedules = $this->schedule->getActiveSchedules();
        $waitlist = new Waitlist();
        $waitlist->setEntriesForSchedules($schedules);

        $i = 0;

        foreach($schedules as $schedule){

            

            $waitlistEntries = $waitlist->getEntriesForProduct($schedule->get_product_id());//$waitlist->getMatchedToProduct($schedule->get_product_id());
            //echo"<pre>"; print_r($waitlistEntries); echo"</pre>";
            if(count($waitlistEntries) == 0){
                $schedule->set_done(wp_date('Y-m-d H:i'));
                $schedule->update();
                break;
            }
            
            //$product = new \WC_Product($schedule->get_product_id());
            $productFactory = new \WC_Product_Factory();  
            $product = $productFactory->get_product($schedule->get_product_id());

            if( !$product->is_in_stock() ) {
                $schedule->set_done(wp_date('Y-m-d H:i'));
                $schedule->update();
                break;
            }
            //$quantity = $product->get_data()['stock_quantity'];
            
            foreach($waitlistEntries as $entry){
                if($i == $this->maxEmailsPerTask){
                    return;
                }
 
                if($this->sendNotification($entry)){
                    $entry->set_sent(wp_date('Y-m-d H:i'));
                    $entry->set_restock_id($schedule->get_id());
                    $entry->update();
                }
                $i++;
            }
        }

    }
    public function sendNotification($entry){
        $mailer = MailerFactory::create();
        $mailer->service->set_to($entry->get_email());
        $mailer->service->set_subject(__('Product from your waitlist is available for purchase','calisia-waitlist'));
        $message =  sprintf( __('Hello %s,'),$entry->get_email()) . '<br>';
        $message .= sprintf( __('Product %s is available for purchase.'), get_the_title($entry->get_product_id())) . '<br>';
        $message .= sprintf( __('Here is your link to the product: %s '), get_permalink($entry->get_product_id())) . '<br>';
        $mailer->service->set_message($message);
        try{
            $mailer->sendEmail();
            return true;
        }catch(CalisiaException $e){
            return false;
        }
    }
}