<?php

declare(strict_types=1);

namespace App\Application\Contract\Event;

/**
 * Interface EventDispatcher Contract
 */
interface EventDispatcher
{
	/**
	 * Register a listener
	 *
	 * @param EventListener $listener
	 */
	public function register(EventListener $listener);

	/**
	 * Dispatch an event thought listener
	 *
	 * @param string $name
	 * @param Event $event
	 *
	 * @return void
	 */
	public function dispatch(string $name, Event $event);
}
