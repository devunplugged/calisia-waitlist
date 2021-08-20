<?php
namespace calisia_waitlist\email;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}


use calisia_waitlist\email\interfaces\IMailer;

class Mailer{
    public $service;

    function __construct(IMailer $service){
        $this->service = $service;
    }

    

    public function sendEmail(){   
        $this->service->validate();

        //append greetings
        $this->service->set_message( $this->service->get_message() . '<br>' . __('All the best from Spiżarnia Rumianek team', 'calisia-waitlist'));

        /* email template variables*/
        $email_heading = $this->service->get_subject();
        /* email template variables*/

        ob_start();
        include ABSPATH . '/wp-content/plugins/woocommerce/templates/emails/email-header.php';
        $message_header = ob_get_contents();
        ob_end_clean();

        ob_start();
        include ABSPATH . '/wp-content/plugins/woocommerce/templates/emails/email-footer.php';
        $message_footer .= ob_get_contents();
        ob_end_clean();

        require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/emails/class-wc-email.php';
        $WC_Email = new \WC_Email();
        $this->service->set_message($WC_Email->format_string($message_header . $this->service->get_message() . $message_footer));
        
 
        return $this->service->send();
    }

}