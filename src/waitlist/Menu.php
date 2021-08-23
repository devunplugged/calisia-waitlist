<?php
namespace calisia_waitlist\waitlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\settings\MenuPage;
use calisia_waitlist\settings\SubMenuPage;
use calisia_waitlist\renderer\interfaces\IRenderer;
use calisia_waitlist\waitlist\wptables\WaitlistTable;
use calisia_waitlist\waitlist\wptables\WaitlistTableControls;
use calisia_waitlist\waitlist\wptables\TopProductsTable;
use calisia_waitlist\waitlist\wptables\TopProductsTableControls;
use calisia_waitlist\waitlist\elements\WaitlistInfo;

class Menu{
    private $renderer;

    function __construct(IRenderer $renderer){
        $this->renderer = $renderer;
        add_action( 'admin_menu', [$this, 'addMenuPage'] );
    }
    
    public function addMenuPage(){
        $menuPage = new MenuPage($this->renderer);
        $menuPage->pageTitle = __( 'Waitlist', 'calisia-waitlist' );
        $menuPage->menuTitle = __( 'Waitlist', 'calisia-waitlist' );
        $menuPage->capability = 'edit_posts';
        $menuPage->menuSlug = 'waitlist';
        $menuPage->pageTemplate = 'settings/Waitlist';
        $menuPage->templateVars = [
            'table' => new WaitlistTable(),
            'controls' => (new WaitlistTableControls($this->renderer))->get(),
            'info' => (new WaitlistInfo($this->renderer))->render(),
        ];
        $menuPage->iconUrl = 'dashicons-clock';
        $menuPage->position = 99;
        $menuPage->optionGroup = 'waitlist-option-group';
        $menuPage->page = 'waitlist-menu-main-page';
        $menuPage->Add();


        $menuPage = new SubMenuPage($this->renderer);
        $menuPage->parentSlug = 'waitlist';
        $menuPage->pageTitle = __( 'Top Products', 'calisia-waitlist' );
        $menuPage->menuTitle = __( 'Top Products', 'calisia-waitlist' );
        $menuPage->capability = 'edit_posts';
        $menuPage->menuSlug = 'waitlist-top-products';
        $menuPage->pageTemplate = 'settings/WaitlistTopProducts';
        $menuPage->templateVars = [
            'table' => new TopProductsTable(),
            'controls' => (new TopProductsTableControls($this->renderer))->get(),
        ];
        $menuPage->position = 1;
        $menuPage->optionGroup = 'waitlist-option-group';
        $menuPage->page = 'waitlist-menu-sub-page';
        $menuPage->Add();
    }
}