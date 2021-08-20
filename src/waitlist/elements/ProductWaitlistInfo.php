<?php
namespace calisia_waitlist\waitlist\elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

use calisia_waitlist\waitlist\Waitlist;
use calisia_waitlist\renderer\interfaces\IRenderer;

class ProductWaitlistInfo{
    private $productId;
    private $waitlist;
	private $entries;

	function __construct(int $productId){
		$this->productId = $productId;
		$this->waitlist = new Waitlist();
		$this->getEntries();
	}

	public function getEntries(){
		$this->waitlist->setEntriesForProduct($this->productId);
		$this->entries = $this->waitlist->getEntriesForProduct($this->productId);
	}

	public function render(IRenderer $renderer){
		$renderer->render(
			'elements/ProductWaitlistInfo',
			[
				'count' => count($this->entries),
				'waitlistUrl' => admin_url( '/admin.php?page=waitlist' ).'&ID='.$this->productId.'&sent=0', //menu_page_url( 'waitlist', false ) return empty when used in ajax call
			]
		);
	}
/*

getEntriesForProduct(int $productId);
        //setEntriesForProduct(int $productId);


	public function render(IRenderer $renderer){
		$text = $this->isSubscribed ? __('Leave Waitlist','calisia-waitlist') : __('Join Waitlist','calisia-waitlist');
		//$email = is_user_logged_in() ? wp_get_current_user()->user_email : '';
		$renderer->render(
			'elements/SubscribeForm',
			[
				'text' => $text,
				'isSubscribed' => $this->isSubscribed,
				'productId' => $this->productId,
				//'email' => $email,
			]
		);
	}*/
}