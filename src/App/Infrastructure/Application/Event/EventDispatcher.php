<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\Event;

use App\Application\Contract\Event\EventDispatcher as EventDispatcherInterface;
use App\Application\Contract\Event\EventListener;
use App\Application\Contract\Event\Event;

class EventDispatcher implements EventDispatcherInterface
{
	/**
	 * Undocumented variable
	 *
	 * @var EventListener[]
	 */
	private $listeners;

	/**
	 * Register a listener
	 *
	 * @param EventListener $listener
	 */
	public function register(EventListener $listener)
	{
		$this->listeners[] = $listener;
	}

	/**
	 * Dispatch an event thought listener
	 *
	 * @param string $name
	 * @param Event $event
	 *
	 * @return void
	 */
	public function dispatch(string $name, Event $event)
	{
		foreach ($this->listeners as $listener)
		{
			$subscribedEvents = $listener->getSubscribedEvents();

			if (
				is_array($subscribedEvents) &&
				count($subscribedEvents) > 0 &&
				array_key_exists($name, $subscribedEvents)
			)
			{
				$method = $subscribedEvents[$name];
				call_user_func_array([$listener, $method], [$event]);
			}
		}
	}
}
