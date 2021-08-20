<?php
namespace calisia_waitlist\debugger;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Debugger{

public static function debug($element, $file = true){
    
    ob_start();
        print_r($element);
    $content = ob_get_contents();
    ob_end_clean();
    
    if($file){
        self::to_file($content);
    }else{
        echo "<pre>";
        echo $content;
        echo "</pre>";
    }
}

public static function to_file($text){
    $fp = fopen(CALISIA_WAITLIST_ROOT . '/debug_log.txt', 'a');//opens file in append mode.
    fwrite($fp, "$text\n ");
    fclose($fp);
}

}