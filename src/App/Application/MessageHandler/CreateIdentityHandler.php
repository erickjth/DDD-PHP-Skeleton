<?php

declare(strict_types=1);

namespace App\Application\MessageHandler;

use App\Application\Command\CreateIdentityCommand;
use App\Application\Presenter\IdentityPresenter;
use App\Domain\Contract\IdentityRepository;
use App\Domain\Entity\Identity;
use App\Domain\ValueObject\IdentityId;

class CreateIdentityHandler
{
	/**
	 * Identity Repository
	 *
	 * @var IdentityRepository
	 */
	private $repository;

	public function __construct(IdentityRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Handles command
	 *
	 * @param GetIdentityByUuidCommand $message
	 *
	 * @return IdentityPresenter
	 */
	public function __invoke(CreateIdentityCommand $message)
	{
		$identityId = IdentityId::generate();

		$identity = Identity::create(
			$identityId,
			$message->getFirstName(),
			$message->getLastName(),
			$message->getEmail()
		);

		// @TODO: check if email/username exists

		$identity->setLogin(
			$message->getUsername(),
			$message->getPassword()
		);

		$identity->setEmailVerified(true);

		$this->repository->store($identity);

		return new IdentityPresenter($identity);
	}
}
