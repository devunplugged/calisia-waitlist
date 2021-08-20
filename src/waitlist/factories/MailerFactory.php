<?php
namespace calisia_waitlist\waitlist\factories;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\email\Mailer;
use calisia_waitlist\email\WpMail;

class MailerFactory{
    public static function create(){

        $service = new WpMail();

        return new Mailer($service);
    }
}