<?php

declare(strict_types=1);

namespace App\Application\MessageHandler;

use App\Application\Command\GetIdentityByUuidCommand;
use App\Application\Presenter\IdentityPresenter;
use App\Domain\Contract\IdentityRepository;
use App\Domain\ValueObject\IdentityId;
use App\Domain\Exception\EntityNotFoundException;

class GetIdentityByUuidHandler
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
	public function __invoke(GetIdentityByUuidCommand $message) : IdentityPresenter
	{
		$identityId = IdentityId::fromString($message->getIdentityUuid());

		$identity = $this->repository->getById($identityId);

		if (!$identity)
		{
			throw new EntityNotFoundException('Identity with id ' . $message->getIdentityUuid());
		}

		return new IdentityPresenter($identity);
	}
}
