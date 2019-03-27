<?php

declare(strict_types=1);

namespace App\Application\EventListener;

use Psr\Log\LoggerInterface;
use App\Application\Event\PingWasDoneEvent;

class LogPingAfterWasDoneListener
{
	private $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function __invoke(PingWasDoneEvent $event)
	{
		$context = [
			'message' => $event,
			'class' => \get_class($event),
		];

		$this->logger->debug('Handling event from listener "{class}"', $context);
	}
}
