<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Contract\MessageBus\QueryBus;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Infrastructure\Container;
use Psr\Log\LoggerInterface;

/**
 * The configuration provider for the App module
 */
class ConfigProvider
{
	/**
	 * Returns the configuration array
	 *
	 * To add a bit of a structure, each section is defined in a separate
	 * method which returns an array with its configuration.
	 *
	 */
	public function __invoke() : array
	{
		return [
			'dependencies' => $this->getDependencies(),
			'infrastructure' => $this->getSettings(),
		];
	}

	/**
	 * Returns the container dependencies
	 */
	public function getDependencies() : array
	{
		return [
			'invokables' => [
			],
			'factories'  => [
				LoggerInterface::class => Container\Factory\LoggerFactory::class,
				ValidatorInterface::class => Container\Factory\ValidatorFactory::class,
				QueryBus::class => Container\Factory\MessageBus\QueryBusFactory::class,
			],
			'extensions' => [
				'infrastructure' => Container\InfrastructureExtension::class
			],
		];
	}

	public function getSettings() : array
	{
		return [];
	}
}
