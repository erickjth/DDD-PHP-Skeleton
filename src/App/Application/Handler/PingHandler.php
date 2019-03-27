<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\MessageBus\Recorder\EventRecorderAware;
use App\Application\MessageBus\Recorder\EventRecorderAwareTrait;
use App\Application\Command\PingCommand;
use App\Application\Event\PingWasDoneEvent;

class PingHandler implements EventRecorderAware
{
	use EventRecorderAwareTrait;

	public function __invoke(PingCommand $message) : array
	{
		$time = $message->getTime();

		$this->record(new PingWasDoneEvent($time));

		return [
			'ack' => $time,
		];
	}
}
