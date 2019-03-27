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
	 * @param callable $listener
	 */
	public function addListener(string $eventName, callable $listener);

	/**
	 * Register subscriber
	 *
	 * @param EventSubscriber $eventSubscriber
	 */
	public function addSubscriber(EventSubscriber $eventSubscriber);

	/**
	 * Dispatch an event thought listeners
	 *
	 * @param Event $event
	 *
	 * @return void
	 */
	public function dispatch(Event $event);
}
