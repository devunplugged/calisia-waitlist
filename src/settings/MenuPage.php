<?php
namespace calisia_waitlist\settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class MenuPage{
    public $pageTitle;
    public $menuTitle;
    public $capability;
    public $menuSlug;
    public $pageTemplate = '';
    public $iconUrl = '';
    public $position = null;
    public $optionGroup;
    public $page;
    public $templateVars;

    private $renderer;
    
    function __construct(IRenderer $renderer){
        $this->renderer = $renderer;
    }

    public function Add(){
        \add_menu_page(
            $this->pageTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug,
            [$this, 'Render'],
            $this->iconUrl,
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