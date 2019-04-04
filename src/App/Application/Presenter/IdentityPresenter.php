<?php

declare(strict_types=1);

namespace App\Application\Presenter;

use App\Domain\Entity\Identity;

class IdentityPresenter
{
	private $identity;

	public function __construct(Identity $identity)
	{
		$this->identity = $identity;
	}

	public function present()
	{
		return [
			'uuid' => $this->identity->getIdentityId()->toString(),
			'firstName' => $this->identity->getFirstName(),
			'lastName' => $this->identity->getLastName(),
			'email' => $this->identity->getEmail(),
			'username' => $this->identity->getUsername(),
			'emailVerified' => $this->identity->getEmailVerified(),
			'createdAt' => $this->identity->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
			'updatedAt' => $this->identity->getUpdatedAt()->format('Y-m-d\TH:i:s\Z'),
		];
	}
}
