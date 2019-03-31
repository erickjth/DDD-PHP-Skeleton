<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler;

use App\Domain\Contract\IdentityRepository;
use App\Domain\ValueObject\IdentityId;
use App\Application\Transformer\IdentityTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetIdentityHandler implements RequestHandlerInterface
{
	public function __construct(IdentityRepository $repository)
	{
		$this->repository = $repository;
	}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$identityId = IdentityId::fromString('0649ac98-5247-11e9-8647-d663bd873d93');

		$identity = $this->repository->getById($identityId);

		return new JsonResponse([
			'identity' => (new IdentityTransformer())->transform($identity)
		]);
	}
}
