<?php
namespace calisia_waitlist\waitlist\products\interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

interface IProductHandler{
    public function renderSubscribeForm(IRenderer $renderer);
    public function isRestocked();
    //public function getParentToSchedule();
}