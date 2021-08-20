<?php
namespace calisia_waitlist\inputs\factories;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\inputs\Input;
use calisia_waitlist\inputs\Select;
use calisia_waitlist\inputs\Button;

class InputFactory{
    public static function Create(string $type){
        switch($type){
            case 'input': return new Input(); break;
            case 'select': return new Select(); break;
            case 'button': return new Button(); break;
        }
    }
}
