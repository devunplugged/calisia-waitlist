<?php
namespace calisia_waitlist\waitlist\wptables;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\inputs\factories\InputFactory;
use calisia_waitlist\renderer\interfaces\IRenderer;

class WaitlistTableControls{
    private $renderer;

    function __construct(IRenderer $renderer){
        $this->renderer = $renderer;
    }

    public function get(){

        $hiddenControls = '';
        $hiddenControls .= $this->getHidden('page');
        $hiddenControls .= $this->getHidden('orderby');
        $hiddenControls .= $this->getHidden('order');
        

        $sentSelect = InputFactory::create('select');
        $sentSelect->label = '';
        $sentSelect->id = 'sent';
        $sentSelect->name = 'sent';
        $sentSelect->options = [
            __('All (Sent and Not Sent)', 'calisia-waitlist') => 'all',
            __('Not Sent', 'calisia-waitlist') => '0',
            __('Sent', 'calisia-waitlist') => '1'
        ];
        $sentSelect->value = isset($_GET['sent']) ? $_GET['sent'] : 'all';


        $customerSelect = InputFactory::create('select');
        $customerSelect->label = '';
        $customerSelect->id = 'customer';
        $customerSelect->name = 'customer';
        $customerSelect->options = [
            __('All (Customers and Visitors)', 'calisia-waitlist') => 'all',
            __('Customer', 'calisia-waitlist') => '1',
            __('Visitor', 'calisia-waitlist') => '0'
        ];
        $customerSelect->value = isset($_GET['customer']) ? $_GET['customer'] : 'all';


        $submitButton = InputFactory::create('button');
        $submitButton->class = 'button action';
        $submitButton->text =  __('Filter', 'calisia-waitlist');


        return [
            'sentSelect' => $sentSelect->render($this->renderer, false),
            'customerSelect' => $customerSelect->render($this->renderer, false),
            'hidden' => $hiddenControls,
            'submitButton' => $submitButton->render($this->renderer, false),
        ];
    }

    private function getHidden(string $property){
        if(!isset($_GET[$property]))
            return '';

        $input = InputFactory::create('input');
        $input->label = '';
        $input->id = $property;
        $input->name = $property;
        $input->type = 'hidden';
        $input->value = $_GET[$property];
        return $input->render($this->renderer, false);
    }
}