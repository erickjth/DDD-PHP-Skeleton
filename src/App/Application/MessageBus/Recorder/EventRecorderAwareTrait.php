<?php
declare(strict_types=1);

namespace App\Application\MessageBus\Recorder;

use App\Application\Contract\Event\Event;

trait EventRecorderAwareTrait
{
	private $eventRecorder;

	/**
	 * Return event recorder.
	 *
	 * @return App\Application\MessageBus\Recorder\EventRecorder $eventRecorder
	 */
	public function getEventRecorder()
	{
		return $this->eventRecorder;
	}

	/**
	 * Set event recorder.
	 *
	 * @param App\Application\MessageBus\Recorder\EventRecorder $eventRecorder
	 */
	public function setEventRecorder(EventRecorder $eventRecorder)
	{
		$this->eventRecorder = $eventRecorder;
	}

	/**
	 * Record an event
	 *
	 * @param Event $event
	 */
	public function record(Event $event)
	{
		$this->eventRecorder->record($event);
	}
}
