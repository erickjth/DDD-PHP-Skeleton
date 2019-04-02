<?php

namespace App\Infrastructure\Container\Compiler;

use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use RuntimeException;

class MessageHandlerCompilerPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		$handlers = $container->findTaggedServiceIds('messenger.message_handler', true);

		$handlersByBusAndMessage = [];

		$busIds = [];

		foreach ($handlers as $serviceId => $tags)
		{
			foreach ($tags as $tag)
			{
				$busId = $tag['bus'] ?? 'messenger.bus.commands'; // default is messenger.bus.commands

				// TODO: add tags for buses
				$className = $container->getDefinition($serviceId)->getClass();
				$handlerReflector = $container->getReflectionClass($className);

				if (null === $handlerReflector)
				{
					throw new RuntimeException(sprintf('Invalid service "%s": class "%s" does not exist.', $serviceId, $className));
				}

				$messageType = $this->guessHandledClasses($handlerReflector, $serviceId);

				$priority = $tag['priority'] ?? 0;

				$handlersByBusAndMessage[$busId][$messageType][$priority][] = $serviceId;

				$busIds[] = $busId;
			}
		}

		foreach ($handlersByBusAndMessage as $bus => $handlersByMessage)
		{
			foreach ($handlersByMessage as $message => $handlersByPriority)
			{
				krsort($handlersByPriority);
				$handlersByBusAndMessage[$bus][$message] = array_unique(array_merge(...$handlersByPriority));
			}
		}

		$handlersLocatorMappingByBus = [];
		foreach ($handlersByBusAndMessage as $bus => $handlersByMessage)
		{
			foreach ($handlersByMessage as $message => $handlerIds)
			{
				$handlers = array_map(function (string $handlerId) {
					return new Reference($handlerId);
				}, $handlerIds);

				$handlersLocatorMappingByBus[$bus][$message] = new IteratorArgument($handlers);
			}
		}

		foreach ($busIds as $bus)
		{
			$locatorId = $bus . '.handlers_locator';

			$locatorDifinition = (new Definition(HandlersLocator::class))->setPublic(true);

			$container->setDefinition($locatorId, $locatorDifinition)
				->setArgument(0, $handlersLocatorMappingByBus[$bus]);

			$handleMessageId =  $bus . '.middleware.handle_message';

			if ($container->has($handleMessageId))
			{
				$container->getDefinition($handleMessageId)->replaceArgument(0, new Reference($locatorId));
			}
		}
	}

	private function guessHandledClasses(\ReflectionClass $handlerClass, string $serviceId): string
	{
		if ($handlerClass->implementsInterface(MessageSubscriberInterface::class)) {
			return $handlerClass->getName()::getHandledMessages();
		}

		try {
			$method = $handlerClass->getMethod('__invoke');
		} catch (\ReflectionException $e) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": class "%s" must have an "__invoke()" method.', $serviceId, $handlerClass->getName()));
		}

		$parameters = $method->getParameters();
		if (1 !== \count($parameters)) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": method "%s::__invoke()" must have exactly one argument corresponding to the message it handles.', $serviceId, $handlerClass->getName()));
		}

		if (!$type = $parameters[0]->getType()) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": argument "$%s" of method "%s::__invoke()" must have a type-hint corresponding to the message class it handles.', $serviceId, $parameters[0]->getName(), $handlerClass->getName()));
		}

		if ($type->isBuiltin()) {
			throw new RuntimeException(sprintf('Invalid handler service "%s": type-hint of argument "$%s" in method "%s::__invoke()" must be a class , "%s" given.', $serviceId, $parameters[0]->getName(), $handlerClass->getName(), $type));
		}

		return (string) $parameters[0]->getType();
	}
}
