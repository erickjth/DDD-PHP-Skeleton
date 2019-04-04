<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory;

use App\Domain\Contract\IdentityRepository;
use App\Domain\ValueObject\IdentityId;
use App\Infrastructure\Http\AuthenticatedUser;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Authentication\UserInterface;

class AuthenticatedUserFactory
{
	public function __invoke(ContainerInterface $container) : callable
	{
		$repository = $container->get(IdentityRepository::class);

		return function (string $identity, array $roles = [], array $details = []) use ($repository) : UserInterface
		{
			$identity = $repository->getById(IdentityId::fromString($identity));

			return new AuthenticatedUser($identity, $roles, $details);
		};
	}
}
