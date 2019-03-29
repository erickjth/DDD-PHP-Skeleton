<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory\Database;

use Psr\Container\ContainerInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ApcCache;
use RuntimeException;

class DoctrineEntityManagerFactory
{
	/**
	 * Invokable method
	 *
	 * @param  ContainerInterface $container Service container
	 *
	 * @return EntityManager  EntityManager instance
	 */
	public function __invoke(ContainerInterface $container) : EntityManager
	{
		$config = $container->has('config') ? $container->get('config') : [];
		$debug = $config['debug'] ?? false;

		if ($container->has(Connection::class) === false)
		{
			throw new RuntimeException('Connection service is required to setup the ORM.');
		}

		$cache = $debug ? new ArrayCache : new ApcCache;

		$config = new Configuration();

		$config->setMetadataCacheImpl($cache);
		$config->setQueryCacheImpl($cache);

		$config->setProxyDir(__DIR__ . '/../../Persistence/Proxies');
		$config->setProxyNamespace('App\Infrastructure\Persistence\Proxies');

		$driver = new \Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver([
			'src/App/Infrastructure/Persistence/ORM/Mapping/Entity' => 'App\Domain\Entity',
			'src/App/Infrastructure/Persistence/ORM/Mapping/ValueObject' => 'App\Domain\ValueObject',
		]);

		$config->setMetadataDriverImpl($driver);

		$config->setNamingStrategy(new UnderscoreNamingStrategy());

		if ($debug) {
			$config->setAutoGenerateProxyClasses(true);
		} else {
			$config->setAutoGenerateProxyClasses(false);
		}

		$connection = $container->get(Connection::class);

		$entityManager = EntityManager::create($connection, $config);

		return $entityManager;
	}
}
