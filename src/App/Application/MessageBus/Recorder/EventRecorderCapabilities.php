<?php

declare(strict_types=1);

namespace App\Application\MessageBus\Recorder;

trait EventRecorderCapabilities
{
	/**
	 * @var array
	 */
	private $recordedEvents = [];

	/**
	 * Release and clear recorded events
	 *
	 * @return array
	 */
	public function releaseEvents() : iterable
	{
		$events = $this->recordedEvents;

		$this->eraseEvents();

		$seen = [];

		foreach ($events as $key => $event)
		{
			if (!\in_array($event, $seen, true))
			{
				yield $key => $seen[] = $event;
			}
		}
	}

	/**
	 * Erase all events
	 */
	public function eraseEvents()
	{
		$this->recordedEvents = [];
	}

	/**
	 * Record an event.
	 *
	 * @param mixed $event
	 */
	public function record($event)
	{
		$this->recordedEvents[] = $event;
	}
}
