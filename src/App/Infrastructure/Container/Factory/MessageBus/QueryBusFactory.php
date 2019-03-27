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

		$middlewareStack = [];

		$middlewareStack[] = $container->get('messender.middleware.release_recorded_events_middleware');

		if ($debug === true)
		{
			$middlewareStack[] = $container->get('messender.middleware.logging_middleware');
		}

		$middlewareStack[] = $container->get('messender.middleware.send_message_middleware');

		$middlewareStack[] = $container->get('messender.middleware.handle_message_middleware');

		$bus = new MessageBus($middlewareStack);

		return new QueryBus($bus);
	}
}
