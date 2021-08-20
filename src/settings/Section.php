<?php
namespace calisia_waitlist\settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class Section{
    public $id;
    public $title;
    public $template;
    public $page;
    public $text;

    private $renderer;
    
    function __construct(IRenderer $renderer){
        $this->renderer = $renderer;
    }

    public function Add(){
        \add_settings_section( 
            $this->id,
            $this->title,
            [$this, 'Render'],
            $this->page 
        );
    }

    public function Render(){
        $this->renderer->render(
            $this->template,
            [
                'text' => $this->text
            ]
        );
    }  
}