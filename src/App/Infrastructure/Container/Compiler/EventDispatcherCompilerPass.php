<?php

namespace App\Infrastructure\Container\Compiler;

use App\Application\Contract\Event\EventDispatcher as EventDispatcherInterface;
use App\Infrastructure\Application\Event\EventDispatcher;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventDispatcherCompilerPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		$definition = (new Definition(EventDispatcher::class))->setPublic(true);

		$taggedServices = $container->findTaggedServiceIds('event_dispatcher.listener', true);

		foreach ($taggedServices as $id => $attributes)
		{
			$definition->addMethodCall('register', [new Reference($id)]);
		}

		$container->setDefinition('event_dispatcher', $definition);

		$container->setAlias(EventDispatcherInterface::class, 'event_dispatcher');
	}
}
