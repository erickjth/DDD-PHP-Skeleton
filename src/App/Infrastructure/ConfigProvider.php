<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Contract\MessageBus\QueryBus;
use App\Application\Contract\MessageBus\CommandBus;
use App\Domain\Contract\IdentityRepository;
use App\Infrastructure\Container;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
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
				// Doctrine
				Connection::class => Container\Factory\Doctrine\DoctrineConnectionFactory::class,
				EntityManager::class => Container\Factory\Doctrine\DoctrineEntityManagerFactory::class,
				// Logger
				LoggerInterface::class => Container\Factory\LoggerFactory::class,
				// Validator
				ValidatorInterface::class => Container\Factory\ValidatorFactory::class,
				// Message bus
				QueryBus::class => Container\Factory\MessageBus\QueryBusFactory::class,
				CommandBus::class => Container\Factory\MessageBus\CommandBusFactory::class,
				// Repositories
				IdentityRepository::class => Container\Factory\Repository\IdentityRepositoryFactory::class,
			],
			'aliases' => [
				'doctrine.connection' => Connection::class,
				'doctrine.entitymanager' => EntityManager::class,
				'messenger.bus.queries' => QueryBus::class,
				'messenger.bus.commands' => CommandBus::class,
				'messenger.bus.default' => CommandBus::class,
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
