<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory\MessageBus;

use App\Application\Contract\MessageBus\CommandBus as CommandBusInterface;
use App\Infrastructure\Application\MessageBus\CommandBus;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\MessageBus;

class CommandBusFactory
{
	/**
	 * Invokable method
	 *
	 * @param  ContainerInterface $container Service container
	 *
	 * @return CommandBus CommandBus instance
	 */
	public function __invoke(ContainerInterface $container) : CommandBusInterface
	{
		$config = $container->has('config') ? $container->get('config') : [];
		$debug = $config['debug'] ?? false;

		$middlewareStack = [
			$container->get('messender.middleware.validation_middleware'),
			$container->get('messender.middleware.doctrine_transaction_middleware'),
			$container->get('messender.middleware.release_recorded_events_middleware')
		];

		if ($debug === true)
		{
			$middlewareStack[] = $container->get('messender.middleware.logging_middleware');
		}

		$middlewareStack[] = $container->get('messender.middleware.send_message_middleware');
		$middlewareStack[] = $container->get('messender.middleware.handle_message_middleware');

		$bus = new MessageBus($middlewareStack);

		return new CommandBus($bus);
	}
}
