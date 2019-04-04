<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\MessageBus;

use App\Application\Contract\MessageBus\CommandBus as CommandBusInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
	use HandleTrait { handle as private privateHandle; }

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
		return $this->privateHandle($command);
	}
}
