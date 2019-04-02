<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory\MessageBus;

use App\Application\Contract\MessageBus\QueryBus as QueryBusInterface;
use App\Infrastructure\Application\MessageBus\QueryBus;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\MessageBus;

class QueryBusFactory
{
	/**
	 * Invokable method
	 *
	 * @param  ContainerInterface $container Service container
	 *
	 * @return QueryBus  Query Bus instance
	 */
	public function __invoke(ContainerInterface $container) : QueryBusInterface
	{
		$config = $container->has('config') ? $container->get('config') : [];
		$debug = $config['debug'] ?? false;

		$middlewareStack = [
			$container->get('messenger.middleware.validation'),
			$container->get('messenger.middleware.release_recorded_events')
		];

		if ($debug === true)
		{
			$middlewareStack[] = $container->get('messenger.middleware.logging');
		}

		$middlewareStack[] = $container->get('messenger.bus.queries.middleware.handle_message');

		$bus = new MessageBus($middlewareStack);

		return new QueryBus($bus);
	}
}
