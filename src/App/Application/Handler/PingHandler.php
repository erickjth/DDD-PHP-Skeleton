<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Contract\Event\EventDispatcher;
use App\Application\Command\PingCommand;
use App\Application\Event\PingWasDoneEvent;

class PingHandler
{
	private $dispatcher;

	public function __construct(EventDispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

	public function __invoke(PingCommand $message) : array
	{
		$time = $message->getTime();

		// @TODO: This events needs to be dispatched after the bus run completaly.
		$this->dispatcher->dispatch(PingWasDoneEvent::NAME, new PingWasDoneEvent($time));

		return [
			'ack' => $time,
		];
	}
}
