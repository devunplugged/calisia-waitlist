<?php
namespace calisia_waitlist\waitlist\elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class WaitlistInfo{
    private $renderer;

	function __construct(IRenderer $renderer){
		$this->renderer = $renderer;
	}

	public function render(){
        return $this->renderer->render(
            'elements/WaitlistInfo',
            [
                'forProduct' => isset($_GET['ID']) ? sprintf(__('for product: %s','calisia-waitlist'),get_the_title($_GET['ID'])) : '',
                'forUser' => isset($_GET['email']) ? sprintf(__('for user: %s','calisia-waitlist'),$_GET['email']) : '',
            ],
            false
        );
	}
}