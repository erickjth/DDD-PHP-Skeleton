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
			$container->get('messenger.middleware.validation'),
			$container->get('messenger.middleware.doctrine_transaction'),
			$container->get('messenger.middleware.release_recorded_events')
		];

		if ($debug === true)
		{
			$middlewareStack[] = $container->get('messenger.middleware.logging');
		}

		$middlewareStack[] = $container->get('messenger.middleware.send_message');
		$middlewareStack[] = $container->get('messenger.bus.commands.middleware.handle_message');

		$bus = new MessageBus($middlewareStack);

		return new CommandBus($bus);
	}
}
