<?php

declare(strict_types=1);

namespace App\Domain\Contract;

use App\Domain\Entity\Identity;
use App\Domain\Contract\Identifier;

interface IdentityRepository
{
	public function getById(Identifier $identityId): ?Identity;

	public function getAll(): array;

	public function store(Identity $identity): void;
}
