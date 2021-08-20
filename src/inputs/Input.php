<?php
namespace calisia_waitlist\inputs;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class Input{
    public $label = "label";
    public $type = "text";
    public $id;
    public $name;
    public $class;
    public $value;

    public function Render(IRenderer $renderer, bool $render = true){
        return $renderer->render(
            'inputs/input',
            [
                'label' => $this->label,
                'type' => $this->type,
                'id' => $this->id,
                'name' => $this->name,
                'class' => $this->class,
                'value' => $this->value
            ],
            $render
        );
    }
}

