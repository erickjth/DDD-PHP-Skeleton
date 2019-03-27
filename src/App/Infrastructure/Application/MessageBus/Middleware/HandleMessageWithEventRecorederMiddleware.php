<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\MessageBus\Middleware;

use App\Application\MessageBus\Recorder\EventRecorder;
use App\Application\MessageBus\Recorder\EventRecorderAware;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class HandleMessageWithEventRecorederMiddleware implements MiddlewareInterface
{
	private $eventRecorder;
	private $handlersLocator;
	private $allowNoHandlers;

	public function __construct(
		HandlersLocatorInterface $handlersLocator,
		EventRecorder $eventRecorder,
		bool $allowNoHandlers = false
	)
	{
		$this->handlersLocator = $handlersLocator;
		$this->eventRecorder = $eventRecorder;
		$this->allowNoHandlers = $allowNoHandlers;
	}

	/**
	 * {@inheritdoc}
	 */
	public function handle(Envelope $envelope, StackInterface $stack): Envelope
	{
		$handler = null;
		$message = $envelope->getMessage();

		foreach ($this->handlersLocator->getHandlers($envelope) as $alias => $handler)
		{
			if ($handler instanceof EventRecorderAware)
			{
				$handler->setEventRecorder($this->eventRecorder);
			}

			$envelope = $envelope->with(HandledStamp::fromCallable($handler, $handler($message), \is_string($alias) ? $alias : null));
		}

		if (null === $handler && !$this->allowNoHandlers)
		{
			throw new NoHandlerForMessageException(sprintf('No handler for message "%s".', \get_class($envelope->getMessage())));
		}

		return $stack->next()->handle($envelope, $stack);
	}
}
