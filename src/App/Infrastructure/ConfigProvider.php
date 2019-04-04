<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Contract\MessageBus\QueryBus;
use App\Application\Contract\MessageBus\CommandBus;
use App\Domain\Contract\IdentityRepository;
use App\Infrastructure\Container;
use App\Infrastructure\Persistence\Doctrine\OAuth2;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use League\OAuth2\Server\Grant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Zend\Expressive\Authentication\UserInterface;

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
			'authentication' => $this->getAuthentication(),
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

				UserInterface::class => Container\Factory\AuthenticatedUserFactory::class,
			],
			'aliases' => [
				'doctrine.connection' => Connection::class,
				'doctrine.entitymanager' => EntityManager::class,
				'messenger.bus.queries' => QueryBus::class,
				'messenger.bus.commands' => CommandBus::class,
				'messenger.bus.default' => CommandBus::class,

				AccessTokenRepositoryInterface::class => OAuth2\AccessTokenRepository::class,
				AuthCodeRepositoryInterface::class => OAuth2\AuthCodeRepository::class,
				ClientRepositoryInterface::class => OAuth2\ClientRepository::class,
				RefreshTokenRepositoryInterface::class => OAuth2\RefreshTokenRepository::class,
				ScopeRepositoryInterface::class => OAuth2\ScopeRepository::class,
				UserRepositoryInterface::class => OAuth2\UserRepository::class,
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

	public function getAuthentication() : array
	{
		return [
			'private_key'    => __DIR__ . '/../../../data/oauth/private.key',
			'public_key'     => __DIR__ . '/../../../data/oauth/public.key',
			'encryption_key' => require __DIR__ . '/../../../data/oauth/encryption.key',
			'access_token_expire'  => 'P1D',
			'refresh_token_expire' => 'P1M',
			'auth_code_expire'     => 'PT10M',

			// Set value to null to disable a grant
			'grants' => [
				Grant\PasswordGrant::class          => Grant\PasswordGrant::class,
				Grant\RefreshTokenGrant::class      => Grant\RefreshTokenGrant::class,
				Grant\ClientCredentialsGrant::class => Grant\ClientCredentialsGrant::class,
				Grant\AuthCodeGrant::class          => null,
				Grant\ImplicitGrant::class          => Grant\ImplicitGrant::class,
			],
		];
	}
}
