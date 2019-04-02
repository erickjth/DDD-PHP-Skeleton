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
			'createdAt' => $this->identity->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
		];
	}
}
