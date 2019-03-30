<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\MessageBus;

use App\Application\Contract\MessageBus\CommandBus as CommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
	/**
	 * Constructor
	 *
	 * @param MessageBusInterface $messageBus
	 */
	public function __construct(MessageBusInterface $messageBus)
	{
		$this->messageBus = $messageBus;
	}

	/**
	 * @param object|Envelope $command
	 *
	 * @return Envelope
	 */
	public function handle($command)
	{
		return $this->messageBus->dispatch($command);
	}
}
