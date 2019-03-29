<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler;

use App\Domain\Entity\Identity;
use App\Domain\ValueObject\IdentityId;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetIdentityHandler implements RequestHandlerInterface
{
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$repository = $this->container->get('doctrine.entitymanager')->getRepository(Identity::class);

		$identityId = IdentityId::fromString('0649ac98-5247-11e9-8647-d663bd873d93');

		$identity = $repository->getById($identityId);

		return new JsonResponse([
			'identity' => $identity->toArray()
		]);
	}
}
