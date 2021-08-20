<?php
namespace calisia_waitlist\waitlist\elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\renderer\interfaces\IRenderer;

class SubscribeForm{
    private $isSubscribed;
    private $productId;
 

	function __construct(bool $isSubscribed, int $productId){
		$this->isSubscribed = $isSubscribed;
		$this->productId = $productId;
	}

	public function render(IRenderer $renderer){
		$text = $this->isSubscribed ? __('Leave Waitlist','calisia-waitlist') : __('Join Waitlist','calisia-waitlist');
		//$email = is_user_logged_in() ? wp_get_current_user()->user_email : '';
		$renderer->render(
			'elements/SubscribeForm',
			[
				'text' => $text,
				'isSubscribed' => $this->isSubscribed,
				'productId' => $this->productId,

			]
		);
	}
}