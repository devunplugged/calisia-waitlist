<?php
namespace calisia_waitlist\settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class SettingsPage{
    public $pageTitle;
    public $menuTitle;
    public $capability;
    public $menuSlug;
    public $pageTemplate = '';
    public $position = null;
    public $optionGroup;
    public $page;
    public $templateVars;

    private $renderer;
    
    function __construct(IRenderer $renderer){
        $this->renderer = $renderer;
    }

    public function Add(){
        \add_options_page(
            $this->pageTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug,
            [$this, 'Render'],
            $this->position
        );
    }

    public function Render(){
        $this->renderer->render(
            $this->pageTemplate,
            [
                'title' => $this->pageTitle,
                'optionGroup' => $this->optionGroup,
                'page' => $this->page,
                'templateVars' => $this->templateVars
            ]
        );
    }
}