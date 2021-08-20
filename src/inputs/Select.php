<?php
namespace calisia_waitlist\inputs;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class Select{
    public $label = "label";
    public $id;
    public $name;
    public $class;
    public $options;
    public $value;

    public function Render(IRenderer $renderer, bool $render = true){
        return $renderer->render(
            'inputs/select',
            [
                'label' => $this->label,
                'id' => $this->id,
                'name' => $this->name,
                'class' => $this->class,
                'options' => $this->options,
                'value' => $this->value
            ],
            $render
        );
    }
}

