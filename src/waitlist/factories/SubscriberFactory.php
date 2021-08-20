<?php
namespace calisia_waitlist\waitlist\factories;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\Subscriber;

class SubscriberFactory{
    public static function create(int $productId,string $email = ''){
        return new Subscriber($productId,$email);
    }

    public static function createWithAutoSetEmail(int $productId){
        $subscriber = new Subscriber($productId);
        $subscriber->autoSetEmail();
        return $subscriber;
    }
}