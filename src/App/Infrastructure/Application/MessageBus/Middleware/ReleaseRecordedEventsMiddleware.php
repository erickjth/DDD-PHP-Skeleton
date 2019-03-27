<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\MessageBus\Middleware;

use App\Application\Contract\Event\EventDispatcher;
use App\Application\MessageBus\Recorder\EventRecorder;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class ReleaseRecordedEventsMiddleware implements MiddlewareInterface
{
	private $eventRecorder;
	private $dispatcher;

	public function __construct(EventRecorder $eventRecorder, EventDispatcher $dispatcher)
	{
		$this->eventRecorder = $eventRecorder;
		$this->dispatcher = $dispatcher;
	}

	public function handle(Envelope $envelope, StackInterface $stack): Envelope
	{
		try
		{
			$envelope = $stack->next()->handle($envelope, $stack);
		}
		catch (\Throwable $e)
		{
			$this->eventRecorder->eraseEvents();
			throw $e;
		}

		foreach ($this->eventRecorder->releaseEvents() as $event)
		{
			$this->dispatcher->dispatch($event);
		}

		return $envelope;
	}
}
