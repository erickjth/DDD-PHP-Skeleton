<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Contract\IdentityRepository as IdentityRepositoryInterface;
use App\Domain\Contract\Identifier;
use App\Domain\Entity\Identity;
use Doctrine\ORM\EntityRepository;

class IdentityRepository extends EntityRepository implements IdentityRepositoryInterface
{
	public function getById(Identifier $identityId): ?Identity
	{
		return $this->findOneBy([
			'identityId.uuid' => (string) $identityId
		]);
	}

	public function getAll(): array
	{
		return [];
	}

	public function store(Identity $identity): void
	{
		$this->getEntityManager()->persist($identity);
	}
}
