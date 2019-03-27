<?php
declare(strict_types=1);

namespace App\Application\Contract\MessageBus\Recorder;

use App\Application\Contract\Event\Event;

interface RecordsEvents
{
	/**
	 * Record an event
	 *
	 * @param Event $event
	 */
	public function record(Event $event);
}
