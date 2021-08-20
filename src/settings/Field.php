<?php
namespace calisia_waitlist\settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class Field{
   // public $optionGroup;
    public $optionName;
    public $input;
    public $id;
    public $title;
    public $page;
    public $sectionId;

    private $renderer;
    
    function __construct(IRenderer $renderer){
        $this->renderer = $renderer;
    }

    

    public function Add(){
        \add_settings_field(
            $this->id,
            $this->title,
            [$this, 'Render'],
            $this->page, 
            $this->sectionId
        );
    }

    public function Render(){

        $this->input->id = $this->id;
        $this->input->name = $this->optionName . '[' . $this->id . ']';
        //$this->input->class = 'my-input-class';
        
        $this->input->render($this->renderer);
    }

    
}