<?php
namespace calisia_waitlist\waitlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\exceptions\CalisiaException;

class Subscriber extends Subscription{

    public function customerSubscribe(){
        if(is_user_logged_in()){
            return $this->userSubscribe();
        }
        return $this->visitorSubscribe();
    }

    public function visitorSubscribe(){
        if($this->isSubscribed()){
            throw new CalisiaException('Visitor tried to double subscribe', __('You are all ready subscribed to that waitlist','calisia-waitlist'));  
        }

        $this->subscribe();
        return 1;     
    }

    public function userSubscribe(){
        if($this->isSubscribed()){
            $this->unSubscribe();
            return 0;
        }

        $this->subscribe();
        return 1;     
    }

}