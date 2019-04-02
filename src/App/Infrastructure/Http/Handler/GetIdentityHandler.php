<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler;

use App\Application\Command\GetIdentityByUuidCommand;
use App\Application\Contract\MessageBus\QueryBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetIdentityHandler implements RequestHandlerInterface
{
	public function __construct(QueryBus $queryBus)
	{
		$this->queryBus = $queryBus;
	}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$command = new GetIdentityByUuidCommand('0649ac98-5247-11e9-8647-d663bd873d93');

		$result = $this->queryBus->query($command);

		return new JsonResponse([
			'data' => $result->present(),
		]);
	}
}
