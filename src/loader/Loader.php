<?php
namespace calisia_waitlist\loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Loader{
    public function loadJs($name, $filePath, $validator, $deps = array(), $ver = false, $in_footer = false){
        if($validator() === true){
            wp_enqueue_script($name, $filePath, $deps, $ver, $in_footer);
            return true;
        }
        return false;
    }

    public function injectJsObject($scriptName, $jsObjectName, $values){
        wp_localize_script( $scriptName, $jsObjectName, $values );
    }
}