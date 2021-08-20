<?php
namespace calisia_waitlist\renderer\interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

interface IRenderer{
    public function render(string $template, array $args, bool $render);
}
