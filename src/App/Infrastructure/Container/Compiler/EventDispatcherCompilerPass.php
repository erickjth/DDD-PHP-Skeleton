<?php

namespace App\Infrastructure\Container\Compiler;

use App\Application\Contract\Event\EventDispatcher as EventDispatcherInterface;
use App\Application\Contract\Event\EventSubscriber;
use App\Infrastructure\Application\Event\EventDispatcher;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use RuntimeException;

class EventDispatcherCompilerPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		$definition = (new Definition(EventDispatcher::class))->setPublic(true);

		$taggedServices = $container->findTaggedServiceIds('event_dispatcher.listener', true);

		foreach ($taggedServices as $serviceId => $attributes)
		{
			$className = $container->getDefinition($serviceId)->getClass();
			$handlerReflector = $container->getReflectionClass($className);

			if (null === $handlerReflector)
			{
				throw new RuntimeException(sprintf('Invalid service "%s": class "%s" does not exist.', $serviceId, $className));
			}

			if ($handlerReflector->implementsInterface(EventSubscriber::class))
			{
				$definition->addMethodCall('addSubscriber', [new Reference($serviceId)]);
			}
			else
			{
				$method = $attributes['method'] ?? '__invoke';
				$eventName = $this->guessEventClass($handlerReflector, $serviceId, $method);

				if (isset($attributes['event']) && $eventName !== $attributes['event'])
				{
					throw new RuntimeException(sprintf('Invalid listener "%s": class "%s" must have an "%s" as parameter in method %s.', $serviceId, $handlerReflector->getName(), $eventName, $method));
				}

				$definition->addMethodCall('addListener', [$eventName, [new Reference($serviceId), $method]]);
			}
		}

		$container->setDefinition('event_dispatcher', $definition);

		$container->setAlias(EventDispatcherInterface::class, 'event_dispatcher');
	}

	private function guessEventClass(\ReflectionClass $handlerClass, string $serviceId, string $method): string
	{
		try {
			$method = $handlerClass->getMethod($method);
		} catch (\ReflectionException $e) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": class "%s" must have an "%s" method.', $serviceId, $handlerClass->getName(), $method));
		}

		$parameters = $method->getParameters();
		if (1 !== \count($parameters)) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": method "%s::%s()" must have exactly one argument corresponding to the message it handles.', $serviceId, $method, $handlerClass->getName()));
		}

		if (!$type = $parameters[0]->getType()) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": argument "$%s" of method "%s::%s()" must have a type-hint corresponding to the message class it handles.', $serviceId, $parameters[0]->getName(), $handlerClass->getName(), $method));
		}

		if ($type->isBuiltin()) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": type-hint of argument "$%s" in method "%s::%s()" must be a class , "%s" given.', $serviceId, $parameters[0]->getName(), $handlerClass->getName(), $method, $type));
		}

		return (string) $parameters[0]->getType();
	}
}
