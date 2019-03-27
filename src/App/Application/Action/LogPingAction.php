<?php

declare(strict_types=1);

namespace App\Application\Action;

use Psr\Log\LoggerInterface;
use App\Application\Event\PingWasDoneEvent;
use App\Application\Contract\Event\EventListener;

class LogPingAction implements EventListener
{
	private $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function getSubscribedEvents()
	{
		return array(
			PingWasDoneEvent::NAME => 'onPingWasDone',
		);
	}

	public function onPingWasDone(PingWasDoneEvent $event)
	{
		$context = [
			'message' => $event,
			'class' => \get_class($event),
		];

		$this->logger->debug('Handling event "{class}"', $context);
	}
}
