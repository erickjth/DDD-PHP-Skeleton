<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\Bus;

use App\Application\Contract\Bus\QueryBus as QueryBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
	use HandleTrait;

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
	 * @param object|Envelope $query
	 *
	 * @return mixed The handler returned value
	 */
	public function query($query)
	{
		return $this->handle($query);
	}
}
