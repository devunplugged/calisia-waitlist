<?php
namespace calisia_waitlist\email;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\exceptions\CalisiaException;
use calisia_waitlist\email\interfaces\IMailer;

class WpMail implements IMailer{
    protected $to;
    protected $subject;
    protected $message;
    protected $headers = array('Content-Type: text/html; charset=UTF-8');
    protected $attachments;

    public function get_to():string{
        return $this->to;
    }

    public function set_to($to){
        $this->to = $to;
    }

    public function get_subject():string{
        return $this->subject;
    }

    public function set_subject($subject){
        $this->subject = $subject;
    }

    public function get_message():string{
        return $this->message;
    }

    public function set_message($message){
        $this->message = $message;
    }

    public function get_headers(){
        return $this->headers;
    }

    public function set_headers($headers){
        $this->headers = $headers;
    }

    public function get_attachments(){
        return $this->attachments;
    }

    public function set_attachments($attachments){
        $this->attachments = $attachments;
    }

    public function validate(){
        if(empty($this->to)){
            throw new CalisiaException('Email attempt without "to" parameter', __('Reciver address not provided','calisia-waitlist'));   
        }
        if(empty($this->subject)){
            throw new CalisiaException('Email attempt without "subject" parameter', __('Subject not provided','calisia-waitlist'));   
        }
        if(empty($this->message)){
            throw new CalisiaException('Email attempt without "message" parameter', __('Message not provided','calisia-waitlist'));   
        }
    }

    public function send(){
        return wp_mail( $this->to, $this->subject, $this->message, $this->headers, $this->attachments );
    }

}