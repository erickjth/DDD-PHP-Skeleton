<?php

declare(strict_types=1);

namespace App\Application\EventListener;

use Psr\Log\LoggerInterface;
use App\Application\Event\PingWasDoneEvent;
use App\Application\Contract\Event\EventSubscriber;

class LogPingAfterWasDoneSubscriber implements EventSubscriber
{
	private $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function getSubscribedEvents()
	{
		return array(
			PingWasDoneEvent::class => [$this, 'onPingWasDone'],
		);
	}

	public function onPingWasDone(PingWasDoneEvent $event)
	{
		$context = [
			'message' => $event,
			'class' => \get_class($event),
		];

		$this->logger->debug('Handling event from subscriber "{class}"', $context);
	}
}
