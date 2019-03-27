<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\Event;

use App\Application\Contract\Event\EventDispatcher as EventDispatcherInterface;
use App\Application\Contract\Event\EventSubscriber;
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
	 * @param callable $listener
	 */
	public function addListener(string $eventName, callable $listener)
	{
		$this->listeners[$eventName][] = $listener;
	}

	/**
	 * Register subscriber
	 *
	 * @param EventSubscriber $eventSubscriber
	 */
	public function addSubscriber(EventSubscriber $eventSubscriber)
	{
		foreach($eventSubscriber->getSubscribedEvents() as $eventName => $listener)
		{
			$this->addListener($eventName, $listener);
		}
	}

	/**
	 * Dispatch an event thought listeners and subscribers
	 *
	 * @param Event $event
	 *
	 * @return void
	 */
	public function dispatch(Event $event)
	{
		$name = get_class($event);

		foreach ($this->getListeners($name) as $listener)
		{
			call_user_func_array($listener, [$event]);
		}
	}

	public function getListeners($eventName) : iterable
	{
		$seen = [];

		foreach ($this->listeners[$eventName] ?? [] as $alias => $listener)
		{
			if (!\in_array($listener, $seen, true))
			{
				yield $alias => $seen[] = $listener;
			}
		}
	}

	public function hasListeners($eventName)
	{
		return isset($this->listeners[$eventName]);
	}
}
