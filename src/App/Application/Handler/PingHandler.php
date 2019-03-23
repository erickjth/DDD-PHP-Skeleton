<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\PingCommand;

class PingHandler
{
	public function __invoke(PingCommand $message) : array
	{
		return [
			'ack' => $message->getTime()
		];
	}
}
