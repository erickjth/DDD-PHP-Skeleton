<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory\MessageBus;

use App\Application\Contract\MessageBus\QueryBus as QueryBusInterface;
use App\Infrastructure\Application\MessageBus\QueryBus;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\LoggingMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;

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

		if ($debug === true)
		{
			$middlewareStack[] = new LoggingMiddleware($container->get(LoggerInterface::class));
		}

		$middlewareStack[] = new SendMessageMiddleware($container->get('messenger.senders_locator'));

		$middlewareStack[] = new HandleMessageMiddleware($container->get('messenger.handlers_locator'));

		$bus = new MessageBus($middlewareStack);

		return new QueryBus($bus);
	}
}
