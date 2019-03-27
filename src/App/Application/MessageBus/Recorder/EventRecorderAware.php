<?php
declare(strict_types=1);

namespace App\Application\MessageBus\Recorder;

use App\Application\Contract\MessageBus\Recorder\RecordsEvents;

interface EventRecorderAware extends RecordsEvents
{
	/**
	 * Return event recorder.
	 *
	 * @return App\Application\MessageBus\Recorder\EventRecorder $eventRecorder
	 */
	public function getEventRecorder();

	/**
	 * Set event recorder.
	 *
	 * @param App\Application\MessageBus\Recorder\EventRecorder $eventRecorder
	 */
	public function setEventRecorder(EventRecorder $eventRecorder);
}
