<?php
namespace calisia_waitlist\waitlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\models\Waitlist;
use calisia_waitlist\waitlist\exceptions\CalisiaException;
use calisia_waitlist\utils\RandomGenerator;
use calisia_waitlist\waitlist\factories\MailerFactory;

class Subscription{
    protected $email = '';
    protected $productId;
    
    function __construct(int $productId, string $email = ''){
        $this->email = $email;
        $this->productId = $productId;
    }

    public function requestHandler(){
        $result = ['error' => null];
        try {
            $result['subscription'] = $this->customerSubscribe();
        }catch (CalisiaException $e){
            $result['error'] = $e->getUserMessage();
        }
        echo json_encode($result);
        wp_die();
    }

    public function autoSetEmail(){
        $this->email = is_user_logged_in() ? wp_get_current_user()->user_email : $this->getEmailFromPost();
        if(!$this->email)
            return $this->email;
        return false;
    }

    public function getEmailFromPost(){
        if(isset($_POST['email']) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            return $_POST['email'];
        }
        return '';
    }

    public function searchSubscription(){
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM ".$wpdb->prefix."calisia_waitlist_waitlist WHERE product_id = %d AND email = %s AND sent = '0000-00-00 00:00'",
                [
                    $this->productId,
                    $this->email,
                ]
            )
        );
    }

    public function getSubscription(){
        if(!$this->email)
            return false;

        $results = $this->searchSubscription();

        if(count($results) > 0){
            $waitlists = [];
            foreach($results as $result){
                $waitlist = new Waitlist();
                $waitlist->fill($results[0]);
                $waitlists[] = $waitlist;
            }
            return $waitlists;
        }
        return false;
    }

    public function isSubscribed(){
        if(!$this->email)
            return false;

        if(count($this->searchSubscription()) > 0){
            return true;
        }
        return false;
    }

    public function subscribe(){
        $waitlist_entry = new Waitlist();
        $waitlist_entry->set_product_id($this->productId);
        $waitlist_entry->set_email($this->email);
        $waitlist_entry->set_added(wp_date("Y-m-d H:i"));
        $waitlist_entry->set_added_by(get_current_user_id());
        $waitlist_entry->set_secret(RandomGenerator::generateString());
        $waitlist_entry->set_unsub_code(RandomGenerator::generateString());
        $waitlist_entry->set_unsub_code_hash(md5($waitlist_entry->get_secret() . $waitlist_entry->get_unsub_code()));

        $user = get_user_by( 'email', $this->email);
        if($user){
            $waitlist_entry->set_user_id($user->ID);
        }
        if($waitlist_entry->save() === false){ //
            throw new CalisiaException('DB save failed: Subscriber, subscribe()', __('Error while saving data','calisia-waitlist'));   
        }
        $this->sendSubscripcionConfirmation($waitlist_entry->get_unsub_code());
    }

    public function unSubscribe(){
        $subs = $this->getSubscription();
        foreach($subs as $subscription){
            if($subscription->delete() === false){
                throw new CalisiaException('DB save failed: Subscriber, unSubscribe()', __('Error while deleting data','calisia-waitlist'));   
            }
        }
        
    }

    public function sendSubscripcionConfirmation(string $unsubCode){
        $mailer = MailerFactory::create();
        $mailer->service->set_to($this->email);
        $mailer->service->set_subject(__('Waitlist Subscription',''));

        //$product = new \WC_Product($this->productId);
        $productFactory = new \WC_Product_Factory();  
        $product = $productFactory->get_product($this->productId);

        $unsubPageLink = get_permalink(get_page_by_title(CALISIA_WAITLIST_UNSUBSCRIBE_PAGE_NAME));

        $message = __( 'Hello,', 'calisia-waitlist' );
        $message .= '<br>' . sprintf( __( 'You have joined product (%s) waitlist.', 'calisia-waitlist' ), $product->get_data()['name'] );
        $message .= ' ' . __( 'We will inform you when it is available.', 'calisia-waitlist' ); 
        $message .= '<br>' . "<a href='$unsubPageLink?productId=$this->productId&email=$this->email&code=$unsubCode'>" . __( 'Use this link to unsubscribe', 'calisia-waitlist' ) . '</a>';
        $mailer->service->set_message($message);

        
        try{
            $mailer->sendEmail();
        }catch(CalisiaException $e){
            echo $e->getUserMessage();
        }
    }
}