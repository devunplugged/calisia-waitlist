<?php
namespace calisia_waitlist\email\interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

interface IMailer{
    public function set_to($to);
    public function get_to():string;
    public function set_subject($subject);
    public function get_subject():string;
    public function set_message($message);
    public function get_message():string;
    public function send();
}
