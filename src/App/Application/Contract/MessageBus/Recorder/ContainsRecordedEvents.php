<?php

declare(strict_types=1);

namespace App\Application\Contract\MessageBus\Recorder;

interface ContainsRecordedEvents
{
	/**
	 * Release and erase recorded events
	 *
	 * @return iterable
	 */
	public function releaseEvents() : iterable;

	/**
	 *  Erase recorded events
	 *
	 * @return void
	 */
	public function eraseEvents();
}
