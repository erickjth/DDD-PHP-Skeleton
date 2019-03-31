<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory\Doctrine;

use Psr\Container\ContainerInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Doctrine\UuidType;
use RuntimeException;

class DoctrineConnectionFactory
{
	/**
	 * Invokable method
	 *
	 * @param  ContainerInterface $container Service container
	 *
	 * @return Connection  Connection instance
	 */
	public function __invoke(ContainerInterface $container) : Connection
	{
		$config = $container->has('config') ? $container->get('config') : [];
		$database = $config['database'] ?? null;

		if ($database === null)
		{
			throw new RuntimeException('Database params are missing.');
		}

		$config = new Configuration();

		$connection = DriverManager::getConnection($database, $config);

		// Register uuid type
		Type::addType('uuid', UuidType::class);

		return $connection;
	}
}
