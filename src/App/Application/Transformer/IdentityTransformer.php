<?php

declare(strict_types=1);

namespace App\Application\Transformer;

use App\Domain\Entity\Identity;

class IdentityTransformer
{
	public function transform(Identity $identity)
	{
		return [
			'uuid' => $identity->getIdentityId()->toString(),
			'firstName' => $identity->getFirstName(),
			'lastName' => $identity->getLastName(),
			'email' => $identity->getEmail(),
			'createdAt' => $identity->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
		];
	}
}
