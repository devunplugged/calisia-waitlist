<?php
namespace calisia_waitlist\inputs;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class Button{
    public $id;
    public $name;
    public $class;
    public $autofocus;
    public $disableAutocomplete;
    public $disabled;
    public $form;
    public $formaction;
    public $formenctype;
    public $formmethod;
    public $formnovalidate;
    public $formtarget;
    public $type;
    public $value;
    public $text;

    public function Render(IRenderer $renderer, bool $render = true){
        return $renderer->render(
            'inputs/button',
            [
                'id' => $this->id,
                'name' => $this->name,
                'class' => $this->class,
                'autofocus' => $this->autofocus,
                'disableAutocomplete' => $this->disableAutocomplete,
                'disabled' => $this->disabled,
                'form' => $this->form,
                'formaction' => $this->formaction,
                'formenctype' => $this->formenctype,
                'formmethod' => $this->formmethod,
                'formnovalidate' => $this->formnovalidate,
                'formtarget' => $this->formtarget,
                'type' => $this->type,
                'value' => $this->value,
                'text' => $this->text,
            ],
            $render
        );
    }
}

