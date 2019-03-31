<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory\Repository;

use App\Domain\Contract\IdentityRepository;
use App\Domain\Entity\Identity;
use Psr\Container\ContainerInterface;

class IdentityRepositoryFactory
{
	/**
	 * Invokable method
	 *
	 * @param  ContainerInterface $container Service container
	 *
	 * @return IdentityRepository  IdentityRepository instance
	 */
	public function __invoke(ContainerInterface $container) : IdentityRepository
	{
		$repository = $container->get('doctrine.entitymanager')
			->getRepository(Identity::class);

		return $repository;
	}
}
